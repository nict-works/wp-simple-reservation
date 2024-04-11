<form method="post" action="<?=admin_url('admin.php?page=wp-simple-reservations-settings&action=update&tab=general');?>">
    <?php foreach ($languages as $language) {?>
    <h2><?=$language->name;?></h2>

    <h3>Klant emails</h3>
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="start_date">Reservering confirmatie titel</label>
                </th>
                <td>
                    <input name="email_reservation_confirmation_subject_<?=$language->slug;?>" type="text" id="email_reservation_confirmation_subject_<?=$language->slug;?>" value="<?=$settings['email_reservation_confirmation_subject_' . $language->slug];?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                </td>
            </tr>
            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="start_date">Reservering confirmatie bericht</label>
                </th>
                <td>
                    <textarea name="email_reservation_confirmation_body_<?=$language->slug;?>" id="email_reservation_confirmation_body_<?=$language->slug;?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200"><?=$settings['email_reservation_confirmation_body_' . $language->slug];?></textarea>
                </td>
            </tr>

            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="start_date">Reservering bevestigd titel</label>
                </th>
                <td>
                    <input name="email_reservation_confirmed_subject_<?=$language->slug;?>" type="text" id="email_reservation_confirmed_subject_<?=$language->slug;?>" value="<?=$settings['email_reservation_confirmed_subject_' . $language->slug];?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                </td>
            </tr>
            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="start_date">Reservering bevestigd bericht</label>
                </th>
                <td>
                    <textarea name="email_reservation_confirmed_body_<?=$language->slug;?>" id="email_reservation_confirmed_body_<?=$language->slug;?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200"><?=$settings['email_reservation_confirmed_body_' . $language->slug];?></textarea>
                </td>
            </tr>

            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="start_date">Reservering geweigerd titel</label>
                </th>
                <td>
                    <input name="email_reservation_declined_subject_<?=$language->slug;?>" type="text" id="email_reservation_declined_subject_<?=$language->slug;?>" value="<?=$settings['email_reservation_declined_subject_' . $language->slug];?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                </td>
            </tr>
            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="start_date">Reservering geweigerd bericht</label>
                </th>
                <td>
                    <textarea name="email_reservation_declined_body_<?=$language->slug;?>" id="email_reservation_declined_body_<?=$language->slug;?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200"><?=$settings['email_reservation_declined_body_' . $language->slug];?></textarea>
                </td>
            </tr>
        </tbody>
    </table>
    <?php }?>

    <h2>Admin emails</h2>
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="start_date">Reservering ontvangen titel</label>
                </th>
                <td>
                    <input name="email_reservation_admin_subject" type="text" id="email_reservation_admin_subject" value="<?=$settings['email_reservation_admin_subject'];?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                </td>
            </tr>
            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="start_date">Reservering ontvangen bericht</label>
                </th>
                <td>
                    <textarea name="email_reservation_admin_body" id="email_reservation_admin_body" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200"><?=$settings['email_reservation_admin_body'];?></textarea>
                </td>
            </tr>
        </tbody>
    </table>

    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Opslaan">
    </p>
</form>
