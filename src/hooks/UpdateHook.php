<?php

namespace WpSimpleReservation\hooks;

class UpdateHook
{
    public function __construct()
    {
        add_action('init', [$this, 'on_init']);
    }

    public function on_init()
    {
        register_setting('wp_simple_reservation', 'wp_simple_reservation');
        $version = get_option('wp_simple_reservation', '0.0.0');

        if ($version === '0.0.0') {
            $version = $this->setup_database();
        }

        if ($version === '1.0.0') {
            $version = $this->update_1_0_1();
        }

        if ($version === '1.0.1') {
            $version = $this->update_1_1_0();
        }

        update_option('wp_simple_reservation', $version);
    }

    private function setup_database(): string
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'reservations';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(254) NOT NULL,
            last_name varchar(254) NOT NULL,
            email varchar(254) NOT NULL,
            amount_of_adults tinyint(2) NOT NULL,
            amount_of_children tinyint(2) NOT NULL,
            price decimal(10, 2) NOT NULL,
            start_date datetime NOT NULL,
            end_date datetime NOT NULL,
            status tinyint(1) DEFAULT 0 NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        return '1.0.0';
    }

    private function update_1_0_1(): string
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'reservations';

        $sql = "ALTER TABLE $table_name
            ADD COLUMN lang_code varchar(254) NOT NULL DEFAULT 'en';";
        $wpdb->query($sql);

        return '1.0.1';
    }

    private function update_1_1_0(): string
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'reservation_additions';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            reservation_id mediumint(9) NOT NULL,
            name varchar(254) NOT NULL,
            price decimal(10, 2) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (reservation_id) REFERENCES {$wpdb->prefix}reservations(id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        return '1.1.0';
    }
}
