<form method="post" action="<?=admin_url('admin.php?page=wp-simple-reservations-settings&action=update&tab=pricing');?>">
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="price">
                        Standaard prijs per nacht
                    </label>
                </th>
                <td>
                    <input type="number" step="0.01" name="price" id="price" value="<?=$settings['price'];?>" required />
                </td>
            </tr>

            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="cleaning_price">
                        Schoonmaakkosten
                    </label>
                </th>
                <td>
                    <input type="number" step="0.01" name="cleaning_price" id="cleaning_price" value="<?=$settings['cleaning_price'];?>" />
                </td>
            </tr>

            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="tourist_tax">
                        Toeristenbelasting per nacht
                    </label>
                </th>
                <td>
                    <input type="number" step="0.01" name="tourist_tax" id="tourist_tax" value="<?=$settings['tourist_tax'];?>" />
                </td>
            </tr>

            <tr class="form-field form-required term-name-wrap">
                <th scope="row">
                    <label for="seasonal_prices">
                        Seizoensprijzen
                    </label>
                </th>
                <td>
                    <table class="form-table">
                        <tbody id="season-pricing">
                            <tr>
                                <th>Van</th>
                                <th>Tot</th>
                                <th>Prijs per nacht</th>
                                <th></th>
                            </tr>
                            <?php foreach ($settings['seasonal_prices'] as $index => $seasonal_price) {?>
                            <tr>
                                <td>
                                    <input type="date" name="seasonal_prices[<?=$index;?>][start_date]" value="<?=$seasonal_price->start_date;?>" required />
                                </td>
                                <td>
                                    <input type="date" name="seasonal_prices[<?=$index;?>][end_date]" value="<?=$seasonal_price->end_date;?>" required />
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="seasonal_prices[<?=$index;?>][price]" value="<?=$seasonal_price->price;?>" required />
                                </td>
                                <td>
                                    <button type="button" class="button button-secondary" data-seasonal-price-delete="">Verwijder</button>
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>

                    <button type="button" id="add-seasonal-price" class="button button-primary">Voeg seizoensprijs toe</button>
                </td>
            </tr>
        </tbody>
    </table>

    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Opslaan">
    </p>
</form>

<script>
    (() => {
        const addSeasonalPriceButton = document.getElementById('add-seasonal-price');

        const setupDeleteButtons = () => {
            const deleteButtons = document.querySelectorAll('[data-seasonal-price-delete]');

            deleteButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    button.closest('tr').remove();
                });
            });
        };

        addSeasonalPriceButton.addEventListener('click', () => {
            const table = document.querySelector('#season-pricing');
            const index = table.querySelectorAll('tr').length - 1;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <input type="date" name="seasonal_prices[${index}][start_date]" required />
                </td>
                <td>
                    <input type="date" name="seasonal_prices[${index}][end_date]" required />
                </td>
                <td>
                    <input type="number" step="0.01" name="seasonal_prices[${index}][price]" required />
                </td>
                <td>
                    <button type="button" class="button button-secondary" data-seasonal-price-delete="">Verwijder</button>
                </td>
            `;

            table.appendChild(tr);

            setupDeleteButtons();
        });

        setupDeleteButtons();
    })();
</script>
