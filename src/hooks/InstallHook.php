<?php

namespace WpSimpleReservation\hooks;

class InstallHook
{
    public function __construct()
    {
        register_activation_hook(WP_SIMPLE_RESERVATION_FILE, [$this, 'install']);
    }

    public function install()
    {

    }
}
