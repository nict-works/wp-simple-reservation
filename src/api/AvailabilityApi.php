<?php

namespace WpSimpleReservation\api;

class AvailabilityApi
{
    /**
     * availability: 0 = fully booked, 1 = available, 2 = available for departing date, 3 = available for arriving date
     * status: 0 = not available, 1 = available, 2 = not available but not confirmed
     */
    public function check_availability()
    {

        $start_date = sanitize_text_field(isset($_GET['start_date']) ? $_GET['start_date'] : '');
        $end_date = sanitize_text_field(isset($_GET['end_date']) ? $_GET['end_date'] : '');

        if (empty($start_date) || strtotime($start_date) === false) {
            $start_date = strtotime(date('Y-m-01'));
        } else {
            $start_date = strtotime($start_date);
        }

        if (empty($end_date) || strtotime($end_date) === false) {
            $end_date = strtotime(date('Y-m-t'));
        } else {
            $end_date = strtotime($end_date);
        }

        return rest_ensure_response([
            'code' => 'success',
            'data' => $this->get_reservations_between($start_date, $end_date),
        ]);
    }

    public function get_reservations_between(
        $start_date,
        $end_date
    ) {
        global $wpdb;

        $dates = [];
        $current_date = $start_date;

        $default_price = get_option('wp_simple_reservation_price', 0);
        $seasonal_prices = json_decode(get_option('wp_simple_reservation_seasonal_prices', '[]'));

        while ($current_date <= $end_date) {
            $reservations = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}reservations WHERE start_date <= %s AND end_date >= %s AND status IN (0, 1, 2)",
                date('Y-m-d', $current_date),
                date('Y-m-d', $current_date)
            ));
            $reservations_count = count($reservations);

            $dates[] = [
                'date' => date('Y-m-d', $current_date),
                'price' => $this->get_price(date('Y-m-d', $current_date), $default_price, $seasonal_prices),
                'availability' => ($reservations_count > 0 ? (
                    date('Y-m-d', strtotime($reservations[0]->start_date)) === date('Y-m-d', $current_date) && $reservations_count === 1 ? 2 : (
                        date('Y-m-d', strtotime($reservations[0]->end_date)) === date('Y-m-d', $current_date) && $reservations_count === 1 ? 3 : 0
                    )
                ) : 1),
                'status' => ($reservations[0] ? ($reservations[0]->status === '2' ? 0 : 2) : 1),
            ];

            $current_date = strtotime('+1 day', $current_date);
        }

        return $dates;
    }

    private function get_price($date, $default_price, $seasonal_prices)
    {
        foreach ($seasonal_prices as $seasonal_price) {
            if (strtotime($date) >= strtotime($seasonal_price->start_date) && strtotime($date) <= strtotime($seasonal_price->end_date)) {
                return (float) $seasonal_price->price;
            }
        }

        return (float) $default_price;
    }
}
