<?php

namespace WpSimpleReservation\emails;

class NewReservationAdminEmail
{
    public function send_email($reservation)
    {
        $to = get_option('admin_email');
        $subject = get_option('wp_simple_reservation_email_reservation_admin_subject');
        $body = get_option('wp_simple_reservation_email_reservation_admin_body');

        $base_email_builder = require WP_SIMPLE_RESERVATION_DIRECTORY . 'src/emails/views/base-email.php';
        $content_builder = require WP_SIMPLE_RESERVATION_DIRECTORY . 'src/emails/views/new-reservation-admin-email.php';
        $message = $base_email_builder([
            'title' => $subject,
            'lang_code' => 'nl',
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
