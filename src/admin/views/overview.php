<div class="wrap">
  <h1 class="wp-heading-inline">
    Reserveringen

    <a href="<?=admin_url('admin.php?page=wp-simple-reservations&action=create');?>" class="page-title-action">
      Datum blokkeren
    </a>
  </h1>

  <hr class="wp-header-end">

  <table class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
      <tr>
        <th scope="col" id="name" class="manage-column column-primary" abbr="Naam">
          Naam
        </th>
        <th scope="col" id="status" class="manage-column column-primary" abbr="Boekings status">
            Boekings status
        </th>
        <th scope="col" id="created_at" class="manage-column column-primary" <?=$orderedBy === 'created_at' ? 'sorted' : 'sortable';?> <?=$orderedBy === 'created_at' && $orderDirection === 'asc' ? 'desc' : 'asc';?>" abbr="Boekingsdatum">
          <a href="<?=admin_url('admin.php?page=wp-simple-reservations&orderby=created_at&order=' . ($orderedBy === 'created_at' && $orderDirection === 'asc' ? 'desc' : 'asc'));?>">
            <span>Geboekt op</span>

            <span class="sorting-indicators">
              <span class="sorting-indicator asc" aria-hidden="true"></span>
              <span class="sorting-indicator desc" aria-hidden="true"></span>
            </span>
          </a>
        </th>
        <th scope="col" id="price" class="manage-column column-primary" abbr="Totaal prijs">
          Prijs
        </th>
        <th scope="col" id="start_date" class="manage-column column-primary <?=$orderedBy === 'start_date' ? 'sorted' : 'sortable';?> <?=$orderedBy === 'start_date' && $orderDirection === 'asc' ? 'desc' : 'asc';?>" abbr="Geboekt vanaf">
          <a href="<?=admin_url('admin.php?page=wp-simple-reservations&orderby=start_date&order=' . ($orderedBy === 'start_date' && $orderDirection === 'asc' ? 'desc' : 'asc'));?>">
            <span>Geboekt vanaf</span>

            <span class="sorting-indicators">
              <span class="sorting-indicator asc" aria-hidden="true"></span>
              <span class="sorting-indicator desc" aria-hidden="true"></span>
            </span>
          </a>
        </th>
        <th scope="col" id="end_date" class="manage-column column-primary <?=$orderedBy === 'end_date' ? 'sorted' : 'sortable';?> <?=$orderedBy === 'end_date' && $orderDirection === 'asc' ? 'desc' : 'asc';?>" abbr="Geboekt tot">
          <a href="<?=admin_url('admin.php?page=wp-simple-reservations&orderby=end_date&order=' . ($orderedBy === 'end_date' && $orderDirection === 'asc' ? 'desc' : 'asc'));?>">
            <span>Geboekt tot</span>

            <span class="sorting-indicators">
              <span class="sorting-indicator asc" aria-hidden="true"></span>
              <span class="sorting-indicator desc" aria-hidden="true"></span>
            </span>
          </a>
        </th>
      </tr>
    </thead>

    <tbody>
        <?php foreach ($reservations as $reservation) {?>
        <tr>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Naam">
                <strong>
                    <a class="row-title" href="<?=admin_url('admin.php?page=wp-simple-reservations&action=edit&id=' . $reservation->id);?>" aria-label="“<?=$reservation->first_name;?> <?=$reservation->last_name;?>” bewerken">
                        <?=$reservation->first_name;?> <?=$reservation->last_name;?>
                    </a>
                </strong>
            </td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Boekings status">
                <?php if ($reservation->status === '0') {?>
                    <span style="color: #0073aa;">Nieuwe boeking</span>
                <?php } else if ($reservation->status === '1') {?>
                    <span style="color: #0073aa;">In behandeling</span>
                <?php } else if ($reservation->status === '2') {?>
                    <span style="color: #46b450;">Goedgekeurd</span>
                <?php } else if ($reservation->status === '3') {?>
                    <span style="color: #e17055;">Afgekeurd</span>
                <?php } else {?>
                    <span style="color: #e17055;">Onbekend</span>
                <?php }?>
            </td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Boekingsdatum">
                <?=date('d-m-Y H:i', strtotime($reservation->created_at));?>
            </td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Totaal prijs">
                &euro; <?=number_format($reservation->price, 2, ',', '.');?>
            </td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Geboekt vanaf">
                <?=date('d-m-Y', strtotime($reservation->start_date));?>
            </td>
            <td class="title column-title has-row-actions column-primary page-title" data-colname="Geboekt tot">
                <?=date('d-m-Y', strtotime($reservation->end_date));?>
            </td>
        </tr>
        <?php }?>
    </tbody>
  </table>

  <div class="tablenav bottom">
    <div class="tablenav-pages">
      <span class="displaying-num"><?=$total;?> items</span>

      <span class="pagination-links">
        <?php if ($page === 1) {?>
          <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
          <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
        <?php } else {?>
          <a class="first-page button" href="<?=admin_url('admin.php?page=wp-simple-reservations&orderby=' . $orderedBy . '&order=' . $orderDirection);?>"><span class="screen-reader-text">Eerste pagina</span><span aria-hidden="true">«</span></a>
          <a class="prev-page button" href="<?=admin_url('admin.php?page=wp-simple-reservations&paged=' . ($page - 1) . '&orderby=' . $orderedBy . '&order=' . $orderDirection);?>"><span class="screen-reader-text">Vorige pagina</span><span aria-hidden="true">‹</span></a>
        <?php }?>

        <span class="screen-reader-text">Huidige pagina</span>
        <span id="table-paging" class="paging-input">
          <span class="tablenav-paging-text"><?=$page;?> van <span class="total-pages"><?=$lastPage;?></span></span>
        </span>

        <?php if ($page === $lastPage) {?>
          <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
          <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
        <?php } else {?>
          <a class="next-page button" href="<?=admin_url('admin.php?page=wp-simple-reservations&paged=' . ($page + 1) . '&orderby=' . $orderedBy . '&order=' . $orderDirection);?>"><span class="screen-reader-text">Volgende pagina</span><span aria-hidden="true">›</span></a>
          <a class="last-page button" href="<?=admin_url('admin.php?page=wp-simple-reservations&paged=' . $lastPage . '&orderby=' . $orderedBy . '&order=' . $orderDirection);?>"><span class="screen-reader-text">Laatste pagina</span><span aria-hidden="true">»</span></a>
        <?php }?>
      </span>
    </div>
    <br class="clear">
  </div>
</div>
