<form method="post" action="<?=admin_url('admin.php?page=wp-simple-reservations-settings&action=update&tab=general');?>">
    <?php foreach ($languages as $language) {?>
    <h2><?=$language->name;?></h2>

    <table class="form-table">
        <tbody>
            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="start_date">Doorverwijzing na reservering</label>
                </th>
                <td>
                    <select name="redirection_post_id_<?=$language->slug;?>">
                        <option value="">Selecteer een pagina</option>
                        <?php foreach ($pages[$language->slug] as $page) {?>
                            <option value="<?=$page->ID;?>" <?=$page->ID == $settings['redirection_post_id_' . $language->slug] ? 'selected' : '';?>><?=$page->post_title;?></option>
                        <?php }?>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <?php }?>

    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Opslaan">
    </p>
</form>
