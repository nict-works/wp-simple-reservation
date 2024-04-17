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
                    <strong>' . ($reservation->lang_code === 'nl' ? 'Naam' : 'Name') . ':</strong>
                </td>
                <td>
                    ' . $reservation->first_name . ' ' . $reservation->last_name . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>' . ($reservation->lang_code === 'nl' ? 'Aantal volwassenen' : 'Amount of adults') . ':</strong>
                </td>
                <td>
                    ' . $reservation->amount_of_adults . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>' . ($reservation->lang_code === 'nl' ? 'Aantal kinderen' : 'Amount of children') . ':</strong>
                </td>
                <td>
                    ' . $reservation->amount_of_children . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>' . ($reservation->lang_code === 'nl' ? 'Geboekt vanaf' : 'Start of reservation') . ':</strong>
                </td>
                <td>
                    ' . date('d-m-Y', strtotime($reservation->start_date)) . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>' . ($reservation->lang_code === 'nl' ? 'Geboekt tot' : 'End of reservation') . ':</strong>
                </td>
                <td>
                    ' . date('d-m-Y', strtotime($reservation->end_date)) . '
                </td>
            </tr>
            <tr>
                <td>
                    <strong>' . ($reservation->lang_code === 'nl' ? 'Prijs' : 'Price') . ':</strong>
                </td>
                <td>
                    &euro; ' . number_format($reservation->price, 2, ',', '.') . '
                </td>
            </tr>
        </table>
    ';
};
