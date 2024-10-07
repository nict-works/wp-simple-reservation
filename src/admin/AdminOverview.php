<?php

namespace WpSimpleReservation\admin;

use Error;
use WpSimpleReservation\emails\ReservationConfirmedEmail;
use WpSimpleReservation\emails\ReservationDeclinedEmail;

class AdminOverview
{
    private $allowedOrderColumns = ['status', 'start_date', 'end_date', 'created_at', 'updated_at'];

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_menu']);

    }

    public function add_menu()
    {
        add_menu_page(
            'Reserveringen',
            'Reserveringen',
            'manage_options',
            'wp-simple-reservations',
            [$this, 'render'],
            'dashicons-calendar-alt',
            40
        );
    }

    public function render()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'delete') {
            $this->delete();
        } else if (isset($_GET['action']) && $_GET['action'] === 'edit') {
            $this->render_edit();
        } else if (isset($_GET['action']) && $_GET['action'] === 'update') {
            $this->update_reservation();
        } else if (isset($_GET['action']) && $_GET['action'] === 'create') {
            $this->render_create();
        } else if (isset($_GET['action']) && $_GET['action'] === 'store') {
            $this->store_reservation();
        } else {
            $this->render_overview();
        }
    }

    private function render_edit($type = null, $message = null): void
    {
        global $wpdb;

        $id = (int) $_GET['id'];
        $reservation = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}reservations WHERE id = %d", $id));

        $reservation_additions = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}reservation_additions WHERE reservation_id = $id");

        if (!$reservation) {
            wp_redirect(admin_url('admin.php?page=wp-simple-reservations'));
            die;
        }

        $languages = get_terms([
            'taxonomy' => 'language',
            'hide_empty' => false,
        ]);

        $user_feedback_message = $message;
        $user_feedback_type = $type;

        require WP_SIMPLE_RESERVATION_DIRECTORY . 'src/admin/views/edit.php';
    }

    private function render_create($type = null, $message = null): void
    {
        $user_feedback_message = $message;
        $user_feedback_type = $type;

        require WP_SIMPLE_RESERVATION_DIRECTORY . 'src/admin/views/create.php';
    }

    private function render_overview(): void
    {
        $page = (int) (isset($_GET['paged']) ? $_GET['paged'] : 1);
        $limit = 15;
        $offset = ($page - 1) * $limit;
        $orderedBy = isset($_GET['orderby']) && in_array($_GET['orderby'], $this->allowedOrderColumns) ? $_GET['orderby'] : 'created_at';
        $orderDirection = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : (!isset($_GET['order']) && !isset($_GET['orderby']) ? 'desc' : 'asc');

        global $wpdb;

        $statement = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}reservations ORDER BY $orderedBy $orderDirection LIMIT %d OFFSET %d",
            $limit,
            $offset
        );

        $reservations = $wpdb->get_results($statement);

        $total = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}reservations");
        $lastPage = (int) ceil($total / $limit);

        require WP_SIMPLE_RESERVATION_DIRECTORY . 'src/admin/views/overview.php';
    }

    private function update_reservation(): void
    {
        global $wpdb;

        $id = (int) $_GET['id'];
        $reservation = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}reservations WHERE id = %d", $id));

        if (!$reservation) {
            wp_redirect(admin_url('admin.php?page=wp-simple-reservations'));
            die;
        }

        $payload = $this->validate_post_input([
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'email',
            'amount_of_adults' => 'number',
            'amount_of_children' => 'number',
            'price' => 'float',
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => 'number',
            'lang_code' => 'string',
        ]);

        if ($payload instanceof Error) {
            $this->render_edit('error', $payload->getMessage());

            return;
        }

        $wpdb->update(
            $wpdb->prefix . 'reservations',
            [
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'email' => $payload['email'],
                'amount_of_adults' => $payload['amount_of_adults'],
                'amount_of_children' => $payload['amount_of_children'],
                'price' => $payload['price'],
                'start_date' => $payload['start_date'],
                'end_date' => $payload['end_date'],
                'status' => $payload['status'],
                'lang_code' => $payload['lang_code'],
            ],
            ['id' => $id]
        );

        $updated_reservation = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}reservations WHERE id = %d", $id));

        $this->trigger_email_automation(
            $reservation->status,
            $updated_reservation
        );

        $this->render_edit('success', 'Wijzigingen opgeslagen!');
    }

    private function store_reservation(): void
    {
        global $wpdb;

        $payload = $this->validate_post_input([
            'start_date' => 'date',
            'end_date' => 'date',
        ]);

        if ($payload instanceof Error) {
            $this->render_create('error', $payload->getMessage());

            return;
        }

        $wpdb->insert(
            $wpdb->prefix . 'reservations',
            [
                'first_name' => 'Geblokkeerd',
                'last_name' => '',
                'email' => '',
                'amount_of_adults' => 0,
                'amount_of_children' => 0,
                'price' => 0,
                'start_date' => $payload['start_date'],
                'end_date' => $payload['end_date'],
                'status' => 2,
                'lang_code' => 'nl',
            ]
        );

        $this->render_create('success', 'Datum blokkade opgeslagen! Je kan direct een nieuwe toevoegen.');
    }

    private function delete(): void
    {}

    private function validate_post_input($variables): array | Error
    {
        $payload = [];

        foreach ($variables as $key => $type) {
            if (!isset($_POST[$key])) {
                return new Error('Missing required field: ' . $key);
            }

            if ($type === 'string') {
                $payload[$key] = sanitize_text_field($_POST[$key]);
            } else if ($type === 'email') {
                $payload[$key] = sanitize_email($_POST[$key]);
            } else if ($type === 'number') {
                $payload[$key] = (int) $_POST[$key];
            } else if ($type === 'float') {
                $payload[$key] = (float) $_POST[$key];
            } else if ($type === 'date') {
                $payload[$key] = date('Y-m-d H:i:s', strtotime($_POST[$key]));
            } else {
                return new Error('Unknown type: ' . $type);
            }
        }

        return $payload;
    }

    private function trigger_email_automation(
        $old_status,
        $reservation
    ): void {
        if ($old_status === $reservation->status) {
            return;
        }

        if ($reservation->status === '1') {
        } else if ($reservation->status === '2') {
            (new ReservationConfirmedEmail())->send_email($reservation);
        } else if ($reservation->status === '3') {
            (new ReservationDeclinedEmail())->send_email($reservation);
        }
    }
}
