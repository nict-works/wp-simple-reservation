<div class="wrap">
    <h1 class="wp-heading-inline">
        Reserveringen #<?=$reservation->id;?>
    </h1>

    <?php if ($user_feedback_message) {?>
        <div class="notice notice-<?=$user_feedback_type;?> is-dismissible">
            <p>
                <?=$user_feedback_message;?>
            </p>
        </div>
    <?php }?>

    <hr class="wp-header-end">

    <form method="post" action="<?=admin_url('admin.php?page=wp-simple-reservations&action=update&id=' . $reservation->id);?>">
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="first_name">Voornaam</label>
                    </th>
                    <td>
                        <input name="first_name" type="text" id="first_name" value="<?=$reservation->first_name;?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="last_name">Achternaam</label>
                    </th>
                    <td>
                        <input name="last_name" type="text" id="last_name" value="<?=$reservation->last_name;?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="email">E-mail</label>
                    </th>
                    <td>
                        <input name="email" type="email" id="email" value="<?=$reservation->email;?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="amount_of_adults">Aantal volwassenen</label>
                    </th>
                    <td>
                        <input name="amount_of_adults" type="number" id="amount_of_adults" value="<?=$reservation->amount_of_adults;?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="amount_of_children">Aantal kinderen</label>
                    </th>
                    <td>
                        <input name="amount_of_children" type="number" id="amount_of_children" value="<?=$reservation->amount_of_children;?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="price">Prijs</label>
                    </th>
                    <td>
                        <input name="price" type="number" id="price" value="<?=$reservation->price;?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="start_date">Start datum</label>
                    </th>
                    <td>
                        <input name="start_date" type="date" id="start_date" value="<?=date('Y-m-d', strtotime($reservation->start_date));?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="end_date">Eind datum</label>
                    </th>
                    <td>
                        <input name="end_date" type="date" id="end_date" value="<?=date('Y-m-d', strtotime($reservation->end_date));?>" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="status">Status</label>
                    </th>
                    <td>
                        <select name="status" id="status">
                            <option value="0" <?=$reservation->status === '0' ? 'selected' : '';?>>Nieuwe boeking</option>
                            <option value="1" <?=$reservation->status === '1' ? 'selected' : '';?>>In behandeling</option>
                            <option value="2" <?=$reservation->status === '2' ? 'selected' : '';?>>Goedgekeurd</option>
                            <option value="3" <?=$reservation->status === '3' ? 'selected' : '';?>>Afgekeurd</option>
                        </select>

                        <p class="description">
                            Let op: Bij het wijzigen van de status wordt er een e-mail verstuurd naar de klant.
                        </p>
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="lang_code">Taal</label>
                    </th>
                    <td>
                        <select name="lang_code" id="lang_code">
                            <?php foreach ($languages as $language) {?>
                                <option value="<?=$language->slug;?>" <?=$reservation->lang_code === $language->slug ? 'selected' : '';?>><?=$language->name;?></option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Opslaan">
        </p>
    </form>

    <h2>Geboekte toevoegingen</h2>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" id="name" class="manage-column column-name column-primary">Naam</th>
                <th scope="col" id="price" class="manage-column column-price">Prijs</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($reservation_additions as $addition) {?>
                <tr id="addition-<?=$addition->id;?>" class="iedit">
                    <td class="name column-name has-row-actions column-primary">
                        <strong>
                            <?=$addition->name;?>
                        </strong>
                    </td>
                    <td class="price column-price">
                        <?=$addition->price;?>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>
