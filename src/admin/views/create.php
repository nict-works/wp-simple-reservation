<div class="wrap">
    <h1 class="wp-heading-inline">
        Datum blokkeren
    </h1>

    <?php if ($user_feedback_message) {?>
        <div class="notice notice-<?=$user_feedback_type;?> is-dismissible">
            <p>
                <?=$user_feedback_message;?>
            </p>
        </div>
    <?php }?>

    <hr class="wp-header-end">

    <form method="post" action="<?=admin_url('admin.php?page=wp-simple-reservations&action=store')?>">
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="start_date">Start datum</label>
                    </th>
                    <td>
                        <input name="start_date" type="date" id="start_date" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th scope="row">
                        <label for="end_date">Eind datum</label>
                    </th>
                    <td>
                        <input name="end_date" type="date" id="end_date" aria-required="true" autocapitalize="words" autocorrect="off" maxlength="200">
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Datum blokkeren">
        </p>
    </form>
</div>
