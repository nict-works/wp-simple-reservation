<div class="wrap">
    <h1 class="wp-heading-inline">
        Instellingen
    </h1>

  <!-- Settings tabs -->
    <h2 class="nav-tab-wrapper">
        <a href="<?=admin_url('admin.php?page=wp-simple-reservations-settings&tab=general');?>" class="nav-tab <?=$tab === 'general' ? 'nav-tab-active' : '';?>">Algemeen</a>
        <a href="<?=admin_url('admin.php?page=wp-simple-reservations-settings&tab=pricing');?>" class="nav-tab <?=$tab === 'pricing' ? 'nav-tab-active' : '';?>">Prijzen</a>
        <a href="<?=admin_url('admin.php?page=wp-simple-reservations-settings&tab=email');?>" class="nav-tab <?=$tab === 'email' ? 'nav-tab-active' : '';?>">E-mails</a>
    </h2>

    <hr class="wp-header-end" />

<?php
if ($tab === 'general') {
    require_once WP_SIMPLE_RESERVATION_DIRECTORY . 'src/admin/views/settings-general.php';
} elseif ($tab === 'pricing') {
    require_once WP_SIMPLE_RESERVATION_DIRECTORY . 'src/admin/views/settings-pricing.php';
} elseif ($tab === 'email') {
    require_once WP_SIMPLE_RESERVATION_DIRECTORY . 'src/admin/views/settings-email.php';
}
?>
</div>
