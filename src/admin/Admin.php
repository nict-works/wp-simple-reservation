<?php

namespace WpSimpleReservation\admin;

class Admin
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        new AdminOverview();
        new AdminSettings();
    }
}
