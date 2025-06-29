<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<table class="table dt-table table-debts" data-order-col="0" data-order-type="asc">
  <thead>
    <tr>
      <th><?php echo '#'; ?></th>
      <th><?php echo _l('debts_invoice_number'); ?></th>
      <th><?php echo _l('debts_supplier'); ?></th>
      <!-- <th><?php echo _l('debts_invoice_date'); ?></th> -->
      <th><?php echo _l('debts_due_date'); ?></th>
      <th><?php echo _l('debts_amount'); ?></th>
      <th><?php echo _l('debts_paid_amount'); ?></th>
      <th><?php echo _l('debts_remaining_amount'); ?></th>
      <th><?php echo _l('debts_created_by'); ?></th>
      <th><?php echo _l('debts_status'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($ds_debts as $debt): ?>
      <tr>
        <td>
          <?php echo html_escape($debt['id']); ?>
          <div class="row-options">
              <a href="<?php echo site_url('admin/suppliers/debt_supplier/'.$debt['id']); ?>">
                  <?php echo _l('edit'); ?>
              </a> |
              <a href="<?php echo site_url('admin/suppliers/delete_debt/'.$debt['id']); ?>" class="text-danger _delete">
                  <?php echo _l('delete'); ?>
              </a>
          </div>
       </td>
        <td><?php echo html_escape("INV-".$debt['invoice_number']); ?></td>
        <td><?php echo get_supplier_name($debt['supplier_id']); ?></td>
        <!-- <td><?php echo _d($debt['invoice_date']); ?></td> -->
        <td><?php echo $debt['due_date']; ?></td>
        <td><?php echo app_format_money($debt['amount'], ''); ?></td>
        <td><?php echo app_format_money($debt['paid_amount'], ''); ?></td>
        <td><?php echo app_format_money($debt['remaining_amount'],''); ?></td>
        <td><?php echo get_staff_full_name($debt['created_by'],''); ?></td>
        <td>
        <?php
      $current_date = new DateTime();
      $three_days_later = $current_date->modify('+3 days');
      $due_date = DateTime::createFromFormat('d/m/Y', $debt['due_date']);

      if (isset($debt['remaining_amount'])) {
        if ($debt['remaining_amount'] == 0) {
            $status = 'paid';
        } elseif ($debt['remaining_amount'] > 0 && $debt['remaining_amount'] < $debt['amount'] && $due_date > $current_date) {
            $status = 'partial';
        }
        elseif ($debt['remaining_amount'] > 0 && $due_date->format('Y-m-d') == $three_days_later->format('Y-m-d')) {
            $status = 'expired';
        }elseif ($debt['remaining_amount'] > 0 && $due_date < $current_date) {
          $status = 'overdue';
        } 
         else {
            $status = 'pending';
        }
        
    } else {
        $status = 'pending';
    }
    switch ($status) {
        case 'pending':
            echo _l('debts_status_pending');
            break;
        case 'partial':
            echo '<span class="label label-warning s-status">' . _l('debts_status_partial') . '</span>';
            break;
        case 'paid':
            echo '<span class="label label-success s-status">' . _l('debts_status_paid') . '</span>';
            break;
        case 'overdue':
            echo '<span class="label label-warning s-status">' . _l('debts_status_overdue') . '</span>';
            break;
            case 'expired':
              echo '<span class="label label-warning s-status">' . _l('debts_status_expired') . '</span>';
              break;
        default:
            echo ucfirst($status);
            break;
    }
    ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
