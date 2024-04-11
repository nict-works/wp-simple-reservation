<?php

namespace WpSimpleReservation;

class WpSimpleReservation
{

    public function __construct()
    {
        new hooks\InstallHook();
        new hooks\UninstallHook();
        new hooks\UpdateHook();

        new admin\Admin();

        new api\RestApi();
    }
}
