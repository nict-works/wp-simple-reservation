<?php

namespace WpSimpleReservation\emails;

class ReservationDeclinedEmail
{
    public function send_email($reservation)
    {
        $to = $reservation->email;
        $subject = get_option('wp_simple_reservation_email_reservation_declined_subject_' . $reservation->lang_code);
        $body = get_option('wp_simple_reservation_email_reservation_declined_body_' . $reservation->lang_code);

        $base_email_builder = require WP_SIMPLE_RESERVATION_DIRECTORY . 'src/emails/views/base-email.php';
        $content_builder = require WP_SIMPLE_RESERVATION_DIRECTORY . 'src/emails/views/reservation-declined-email.php';
        $message = $base_email_builder([
            'title' => $subject,
            'lang_code' => $reservation->lang_code,
            'content' => $content_builder([
                'reservation' => $reservation,
                'body' => $body,
            ]),
        ]);

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
        ];

        wp_mail($to, $subject, $message, $headers);
    }
}
