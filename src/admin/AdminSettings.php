<?php

namespace WpSimpleReservation\admin;

class AdminSettings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_menu']);
    }

    public function add_menu()
    {
        add_submenu_page(
            'wp-simple-reservations',
            'Instellingen',
            'Instellingen',
            'manage_options',
            'wp-simple-reservations-settings',
            [$this, 'render_settings']
        );
    }

    public function render_settings()
    {
        $this->check_for_update();

        $languages = get_terms([
            'taxonomy' => 'language',
            'hide_empty' => false,
        ]);

        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
        $settings = [
            'email_reservation_admin_subject' => get_option('wp_simple_reservation_email_reservation_admin_subject'),
            'email_reservation_admin_body' => get_option('wp_simple_reservation_email_reservation_admin_body'),

            // Pricing
            'price' => get_option('wp_simple_reservation_price'),
            'tourist_tax' => get_option('wp_simple_reservation_tourist_tax'),
            'seasonal_prices' => json_decode(get_option('wp_simple_reservation_seasonal_prices', '[]')),
            'price_additions' => json_decode(get_option('wp_simple_reservation_price_additions', '[]')),
        ];

        foreach ($languages as $language) {
            $settings['redirection_post_id_' . $language->slug] = get_option('wp_simple_reservation_redirection_post_id_' . $language->slug);

            $settings['email_reservation_confirmation_subject_' . $language->slug] = get_option('wp_simple_reservation_email_reservation_confirmation_subject_' . $language->slug);
            $settings['email_reservation_confirmation_body_' . $language->slug] = get_option('wp_simple_reservation_email_reservation_confirmation_body_' . $language->slug);
            $settings['email_reservation_confirmed_subject_' . $language->slug] = get_option('wp_simple_reservation_email_reservation_confirmed_subject_' . $language->slug);
            $settings['email_reservation_confirmed_body_' . $language->slug] = get_option('wp_simple_reservation_email_reservation_confirmed_body_' . $language->slug);
            $settings['email_reservation_declined_subject_' . $language->slug] = get_option('wp_simple_reservation_email_reservation_declined_subject_' . $language->slug);
            $settings['email_reservation_declined_body_' . $language->slug] = get_option('wp_simple_reservation_email_reservation_declined_body_' . $language->slug);
        }

        if ($tab === 'general') {
            $pages = [];

            foreach ($languages as $language) {
                $pages[$language->slug] = get_pages([
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'lang' => $language->slug,
                ]);
            }
        }

        require_once WP_SIMPLE_RESERVATION_DIRECTORY . 'src/admin/views/settings.php';
    }

    private function check_for_update()
    {
        if (!isset($_GET['action']) || $_GET['action'] !== 'update') {
            return;
        }

        $languages = get_terms([
            'taxonomy' => 'language',
            'hide_empty' => false,
        ]);

        foreach ($languages as $language) {
            $this->check_and_update_option('redirection_post_id_' . $language->slug);

            $this->check_and_update_option('email_reservation_confirmation_subject_' . $language->slug);
            $this->check_and_update_option('email_reservation_confirmation_body_' . $language->slug);
            $this->check_and_update_option('email_reservation_confirmed_subject_' . $language->slug);
            $this->check_and_update_option('email_reservation_confirmed_body_' . $language->slug);
            $this->check_and_update_option('email_reservation_declined_subject_' . $language->slug);
            $this->check_and_update_option('email_reservation_declined_body_' . $language->slug);
        }

        $this->check_and_update_option('email_reservation_admin_subject');
        $this->check_and_update_option('email_reservation_admin_body');

        $this->check_and_update_option('price');
        $this->check_and_update_option('cleaning_price');
        $this->check_and_update_option('tourist_tax');

        if (isset($_POST['seasonal_prices'])) {
            $seasonal_prices = [];

            foreach ($_POST['seasonal_prices'] as $seasonal_price) {
                if (!empty($seasonal_price['start_date']) && !empty($seasonal_price['end_date']) && !empty($seasonal_price['price'])) {
                    $seasonal_prices[] = (object) $seasonal_price;

                }
            }

            update_option('wp_simple_reservation_seasonal_prices', json_encode($seasonal_prices));
        }

        if (isset($_POST['price_additions'])) {
            $price_additions = [];

            foreach ($_POST['price_additions'] as $price_addition) {
                if (!empty($price_addition['type']) && !empty($price_addition['price'])) {
                    $price_addition_object = [
                        'type' => $price_addition['type'],
                        'optional' => isset($price_addition['optional']),
                        'price' => $price_addition['price'],
                        'identifier' => uniqid(),
                    ];

                    foreach ($languages as $language) {
                        $price_addition_object['name_' . $language->slug] = $price_addition['name_' . $language->slug];
                    }

                    $price_additions[] = (object) $price_addition_object;
                }
            }

            update_option('wp_simple_reservation_price_additions', json_encode($price_additions));
        }
    }

    private function check_and_update_option($key): void
    {
        if (isset($_POST[$key])) {
            update_option("wp_simple_reservation_{$key}", $_POST[$key]);
        }
    }
}
