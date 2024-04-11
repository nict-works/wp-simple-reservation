<?php

namespace WpSimpleReservation\api;

class RestApi
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes(): void
    {
        $availabilityApi = new AvailabilityApi();

        register_rest_route('wp-simple-reservation/v1', '/availability', [
            'methods' => 'GET',
            'callback' => [$availabilityApi, 'check_availability'],
            'permission_callback' => '__return_true',
        ]);

        $reservationApi = new ReservationApi();

        register_rest_route('wp-simple-reservation/v1', '/reservations', [
            'methods' => 'POST',
            'callback' => [$reservationApi, 'store_reservation'],
            'permission_callback' => '__return_true',
        ]);
    }
}
