<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12 tw-mb-2">
            <div class="_buttons sm:tw-space-x-1 rtl:sm:tw-space-x-reverse">
                <?php if (staff_can('create', 'invoices_supplier')) { ?>
                <a href="<?= admin_url('invoices_supplier/invoice_supplier'); ?>"
                    class="btn btn-primary pull-left new new-invoice-list">
                    <i class="fa-regular fa-plus tw-mr-1"></i>
                    <?= _l('create_new_invoice'); ?>
                </a>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12" id="small-table">
            <div class="panel_s">
                <div class="panel-body panel-table-full">
                    <!-- if invoiceid found in url -->
                    <?= form_hidden('invoices_supplier_id', $invoices_supplier_id); ?>
                    <?php $this->load->view('admin/invoices_supplier/table_html'); ?>
                </div>
            </div>
        </div>
        <div class="col-md-7 small-table-right-col">
            <div id="invoices_supplier" class="hide"></div>
        </div>
    </div>
</div>