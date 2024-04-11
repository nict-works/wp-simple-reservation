<?php

namespace WpSimpleReservation\emails;

class NewReservationConfirmationEmail
{
    public function send_email($reservation)
    {
        $to = $reservation->email;
        $subject = get_option('wp_simple_reservation_email_reservation_confirmation_subject_' . $reservation->lang_code);
        $body = get_option('wp_simple_reservation_email_reservation_confirmation_body_' . $reservation->lang_code);

        $base_email_builder = require WP_SIMPLE_RESERVATION_DIRECTORY . 'src/emails/views/base-email.php';
        $content_builder = require WP_SIMPLE_RESERVATION_DIRECTORY . 'src/emails/views/new-reservation-confirmation-email.php';
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
