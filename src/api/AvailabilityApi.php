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

        $reservations = [];
        $current_date = $start_date;

        while ($current_date <= $end_date) {
            $reservation = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}reservations WHERE start_date <= %s AND end_date >= %s AND status IN (0, 1, 2) LIMIT 1",
                date('Y-m-d', $current_date),
                date('Y-m-d', $current_date)
            ));

            $reservations[] = [
                'date' => date('Y-m-d', $current_date),
                'availability' => ($reservation ? (
                    date('Y-m-d', strtotime($reservation->start_date)) === date('Y-m-d', $current_date) ? 2 : (
                        date('Y-m-d', strtotime($reservation->end_date)) === date('Y-m-d', $current_date) ? 3 : 0
                    )
                ) : 1),
                'status' => ($reservation ? ($reservation->status === '2' ? 0 : 2) : 1),
            ];

            $current_date = strtotime('+1 day', $current_date);
        }

        return $reservations;
    }
}
