<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?= form_open_multipart($this->uri->uri_string(), ['id' => 'invoic_supplier-form', 'class' => '_transaction_form invoice-form']); ?>
            <?php if (isset($invoices_supplier)) {
                echo form_hidden('isedit');
            } ?>
            <div class="col-md-12">
                
                <h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                    <span>
                        <?= e(isset($invoices_supplier) ? format_invoice_supplier_number($invoices_supplier) : _l('create_new_invoice')); ?>
                    </span>
                    <?= isset($invoices_supplier) ? format_invoice_status($invoices_supplier->status) : ''; ?>
                </h4>
                <?php $this->load->view('admin/invoices_supplier/invoice_template'); ?>
            </div>
            <?= form_close(); ?>
            <?php $this->load->view('admin/invoice_supplier_items/item'); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function() {
        validate_invoice_form();
        // Init accountacy currency symbol
        init_currency();
        // Project ajax search
        init_ajax_project_search_by_customer_id();
        // Maybe items ajax search
        init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
    });
</script>
</body>

</html>