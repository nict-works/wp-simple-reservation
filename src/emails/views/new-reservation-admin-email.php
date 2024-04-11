<?php

return function ($context): string {
    $reservation = $context['reservation'];
    $body = $context['body'];

    return '
        <p style="text-align: center; color: #444444; font-size: 1rem;">
            ' . $body . '
        </p>

        <table style="margin: 16px 0 0 0; width: 100%;">
            <tr>
                <td>
                    <strong>Naam:</strong>
                </td>
                <td>
                    ' . $reservation->first_name . ' ' . $reservation->last_name . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>E-mail:</strong>
                </td>
                <td>
                    ' . $reservation->email . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Aantal volwassenen:</strong>
                </td>
                <td>
                    ' . $reservation->amount_of_adults . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Aantal kinderen:</strong>
                </td>
                <td>
                    ' . $reservation->amount_of_children . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Prijs:</strong>
                </td>
                <td>
                    ' . $reservation->price . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Geboekt vanaf:</strong>
                </td>
                <td>
                    ' . date('d-m-Y', strtotime($reservation->start_date)) . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Geboekt tot:</strong>
                </td>
                <td>
                    ' . date('d-m-Y', strtotime($reservation->end_date)) . '
                </td>
            </tr>
        </table>

        <div style="margin: 24px 0 0 0; text-align: center;">
            <a href="' . admin_url('admin.php?page=wp-simple-reservations&action=edit&id=' . $reservation->id) . '" style="padding: 8px 32px; color: #fff; display: inline-block; background: #444444; text-decoration: none; line-height: 1.5; font-size: 1rem; text-align: center; border-radius: 0; border: none;">Bekijk reservering</a>
        </div>
    ';
};
