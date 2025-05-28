<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<table class="table dt-table table-supplier" data-order-col="0" data-order-type="asc">
  <thead>
    <tr>
      <th><?php echo _l('supplier_name'); ?></th>
      <th><?php echo _l('supplier_contact_person'); ?></th>
      <th><?php echo _l('supplier_group'); ?></th>
      <th><?php echo _l('supplier_address'); ?></th>
      <th><?php echo _l('supplier_city'); ?></th>
      <th><?php echo _l('supplier_phone'); ?></th>
      <th><?php echo _l('supplier_position'); ?></th>
      <th><?php echo _l('supplier_website'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ds_suppliers as $supplier): ?>
      <tr>
        <td>
          <?php
          echo '<a href="' . site_url('admin/suppliers/supplier/' . $supplier['id']) . '">' . $supplier['name'] . '</a>';
          echo '<div class="row-options">
                  <a href="' . site_url('admin/suppliers/supplier/' . $supplier['id']) . '">' . _l('edit') . '</a> |
                  <a href="' . site_url('admin/suppliers/delete/' . $supplier['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>
                </div>';
          ?>
        </td>
        <td><?php echo $supplier['contact_person']; ?></td>
        <td><?php echo get_typesupplier_name($supplier['typesupplier_id']); ?></td>
        <td><?php echo $supplier['address']; ?></td>
        <td><?php echo $supplier['city']; ?></td>
        <td><?php echo $supplier['phone']; ?></td>
        <td><?php echo $supplier['position']; ?></td>
        <td><?php echo $supplier['website']; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
