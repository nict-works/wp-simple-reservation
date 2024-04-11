<?php

namespace WpSimpleReservation\hooks;

class UninstallHook
{
    public function __construct()
    {
    }

    public static function uninstall()
    {
        // Delete options
        delete_option('wp_simple_reservation');
    }
}
