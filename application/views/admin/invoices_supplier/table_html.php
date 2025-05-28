<?php defined('BASEPATH') or exit('No direct script access allowed');
?>
<table class="table dt-table table-invoices_supplier" data-order-col="0" data-order-type="desc">
            <thead>
                <tr>
                    <th class="th-invoice-number"><?php echo _l('clients_invoice_dt_number'); ?></th>
                    <th class="th-invoice-supplier"><?php echo _l('suppliers'); ?></th>
                    <th class="th-invoice-total"><?php echo _l('invoice_dt_table_heading_amount'); ?></th>
                    <th class="th-invoice-date"><?php echo _l('clients_invoice_dt_date'); ?></th>  
                    <th class="th-invoice-duedate"><?php echo _l('clients_invoice_dt_duedate'); ?></th>                 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dsinvoices_supplier as $invoice) { ?>
                <tr>
                    <td data-order="<?php echo $invoice['id']; ?>"><a
                            href="<?php echo site_url('invoice_supplier/' . $invoice['id'] . '/' . $invoice['hash']); ?>"
                            class="invoice-number"><?php echo $invoice['prefix']; ?><?php echo $invoice['id']; ?></a>
                            <div class="row-options"><a href="<?php echo site_url('admin/invoices_supplier/invoice_supplier/' . $invoice['id']); ?>" target="_blank">Xem</a> | <a href=" <?php echo site_url('admin/invoices_supplier/invoice_supplier/' . $invoice['id']); ?>">Chỉnh sửa </a></div>
                          </td>
                        <td data-order="<?php echo $invoice['supplierid']; ?>">
                            <?php echo get_supplier_name($invoice['supplierid']); ?></td>    
                     <td data-order="<?php echo $invoice['total']; ?>">
                            <?php echo $invoice['total']; ?></td>       
                    <td data-order="<?php echo $invoice['date']; ?>"><?php echo _d($invoice['date']); ?></td>
                    
                    <td data-order="<?php echo $invoice['duedate']; ?>"><?php echo _d($invoice['duedate']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>