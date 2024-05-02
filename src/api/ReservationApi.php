<?php

namespace WpSimpleReservation\api;

use Error;
use WpSimpleReservation\emails\NewReservationAdminEmail;
use WpSimpleReservation\emails\NewReservationConfirmationEmail;
use WP_Error;

class ReservationApi
{
    public function store_reservation($request)
    {
        global $wpdb;

        $payload = $this->validate_input($request->get_json_params(), [
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'email',
            'amount_of_adults' => 'number',
            'amount_of_children' => 'number',
            'start_date' => 'date',
            'end_date' => 'date',
            'lang_code' => 'string',
            'additions' => 'array',
        ]);

        if ($payload instanceof Error) {
            return new WP_Error(
                'error',
                $payload->getMessage(),
                ['status' => 400]
            );
        }

        $availabilityApi = new AvailabilityApi();
        $reservations = $availabilityApi->get_reservations_between(
            strtotime($payload['start_date']),
            strtotime($payload['end_date'])
        );
        $availability = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
        ];

        $duration_price = 0;
        $nights = count($reservations) - 1;

        foreach ($reservations as $index => $reservation) {
            $availability[$reservation['availability']]++;

            if ($index !== $nights) {
                $duration_price += $reservation['price'];
            }
        }

        if ($availability[0] > 0 || $availability[2] > 1 || $availability[3] > 1 || $payload['start_date'] >= $payload['end_date'] || $payload['start_date'] < strtotime(date('Y-m-d'))) {
            return new WP_Error(
                'error',
                'Fully booked. Please select another date.',
                ['status' => 400]
            );
        }

        $price_additions = json_decode(get_option('wp_simple_reservation_price_additions', '[]'));
        $addition_price = 0;
        $linked_additions = [];

        $booking_addition_identifiers = [];
        foreach ($payload['additions'] as $addition) {
            $booking_addition_identifiers[] = $addition['identifier'];
        }

        foreach ($price_additions as $price_addition) {
            if ($price_addition->optional && !in_array($price_addition->identifier, $booking_addition_identifiers)) {
                continue;
            }

            $price = ($price_addition->type === 'per_night' ? ($price_addition->price * $nights) : $price_addition->price);
            $addition_price += $price;

            $linked_additions[] = [
                'name' => $price_addition->name_nl ?? $price_addition->name_en,
                'price' => $price,
            ];
        }

        $tourist_tax = get_option('wp_simple_reservation_tourist_tax');
        $guests = $payload['amount_of_adults'] + $payload['amount_of_children'];

        $price = $duration_price + ($cleaning_price ?? 0) + (($tourist_tax ?? 0) * $nights * $guests) + $addition_price;

        $wpdb->insert(
            $wpdb->prefix . 'reservations',
            [
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'email' => $payload['email'],
                'amount_of_adults' => $payload['amount_of_adults'],
                'amount_of_children' => $payload['amount_of_children'],
                'start_date' => $payload['start_date'],
                'end_date' => $payload['end_date'],
                'lang_code' => $payload['lang_code'],
                'price' => $price,
            ]
        );

        $reservation_id = $wpdb->insert_id;

        foreach ($linked_additions as $addition) {
            $wpdb->insert(
                $wpdb->prefix . 'reservation_additions',
                [
                    'reservation_id' => $reservation_id,
                    'name' => $addition['name'],
                    'price' => $addition['price'],
                ]
            );
        }

        $reservation = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}reservations WHERE id = %d", $wpdb->insert_id));

        (new NewReservationConfirmationEmail())->send_email($reservation);
        (new NewReservationAdminEmail())->send_email($reservation);

        $confirmation_post_id = get_option('wp_simple_reservation_redirection_post_id_' . $payload['lang_code']);
        $post_slug = get_post($confirmation_post_id)?->post_name ?? '';

        return rest_ensure_response([
            'code' => 'success',
            'data' => [
                'redirect_url' => $post_slug,
            ],
        ]);
    }

    private function validate_input($input, $variables): array | Error
    {
        $payload = [];

        foreach ($variables as $key => $type) {
            if (!isset($input[$key])) {
                return new Error('Missing required field: ' . $key);
            }

            if ($type === 'string') {
                $payload[$key] = sanitize_text_field($input[$key]);
            } else if ($type === 'email') {
                $payload[$key] = sanitize_email($input[$key]);
            } else if ($type === 'number') {
                $payload[$key] = (int) $input[$key];
            } else if ($type === 'double') {
                $payload[$key] = (double) $input[$key];
            } else if ($type === 'date') {
                $payload[$key] = date('Y-m-d', strtotime($input[$key]));
            } else if ($type === 'array') {
                $payload[$key] = $input[$key];
            } else {
                return new Error('Unknown type: ' . $type);
            }
        }

        return $payload;
    }
}
