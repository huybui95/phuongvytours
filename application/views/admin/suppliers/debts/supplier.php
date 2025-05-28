<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" />
    
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo _l('debts_add_new'); ?>
                </h4>
                <div class="panel_s">
                <div class="panel-body">
                        <?php echo form_open($this->uri->uri_string(), ['id' => 'debt-form']); ?>

                        <!-- Nhà cung cấp -->
                        <div class="form-group select-placeholder">
                            <label for="supplier_id" class="control-label"><?php echo _l('debts_supplier'); ?></label>
                            <select name="supplier_id" id="supplier_id" class="form-control selectpicker" data-live-search="true" required>
                                <option value=""><?php echo _l('select_supplier'); ?></option>
                                <?php foreach ($suppliers as $s): ?>
                                    <option value="<?php echo $s['id']; ?>" <?php echo (isset($debt->supplier_id) && $debt->supplier_id == $s['id']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($s['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                       
                        <!-- Số hóa đơn -->
                        <div class="form-group select-placeholder">
                            <label for="invoice_number" class="control-label"><?php echo _l('debts_invoice_number'); ?></label>
                            <select name="invoice_number" id="invoice_number"
                                class="form-control selectpicker"
                                data-live-search="true"
                                data-invoice-id="<?php echo !empty($debt->invoice_number) ? $debt->invoice_number : ''; ?>"
                                required disabled>
                                <option value=""><?php echo _l('select_invoice'); ?></option>
                            </select>

                        </div>

                        <!-- Thông tin hóa đơn -->
                        <?php 
                        // Nếu không có ngày thì mặc định là ngày hôm nay
                        $invoice_date = isset($debt->invoice_date) && !empty($debt->invoice_date) ? _d($debt->invoice_date) : date('d/m/Y');
                        $due_date = isset($debt->due_date) && !empty($debt->due_date) ? _d($debt->due_date) :'';

                        echo render_date_input('invoice_date', 'debts_invoice_date', $invoice_date, ['readonly' => 'readonly']);
                        echo render_date_input('due_date', 'debts_due_date', $due_date, ['readonly' => 'readonly']);
                        ?>
                        <?php echo render_input('amount', 'debts_amount', isset($debt->amount) ? $debt->amount : '', 'text', ['readonly' => 'readonly']); ?>
                        <?php echo render_input('paid_amount', 'debts_paid_amount', isset($debt->paid_amount) ? $debt->paid_amount : '0', 'text'); ?>
                        <?php echo render_input('remaining_amount', 'debts_remaining_amount', isset($debt->remaining_amount) ? $debt->remaining_amount : '', 'text', ['readonly' => 'readonly']); ?>

                        <!-- Trạng thái -->
                        <div class="form-group">
                            <label for="status"><?php echo _l('debts_status'); ?></label>
                            <select name="status" id="status" class="form-control">
                            <option value="pending" <?php echo (isset($debt->status) && $debt->status === 'pending') ? 'selected' : ''; ?>>
                                <?php echo _l('debts_status_pending'); ?>
                            </option>
                            <option value="partial" <?php echo (isset($debt->status) && $debt->status === 'partial') ? 'selected' : ''; ?>>
                                <?php echo _l('debts_status_partial'); ?>
                            </option>
                            <option value="paid" <?php echo (isset($debt->status) && $debt->status === 'paid') ? 'selected' : ''; ?>>
                                <?php echo _l('debts_status_paid'); ?>
                            </option>
                            <option value="overdue" <?php echo (isset($debt->status) && $debt->status === 'overdue') ? 'selected' : ''; ?>>
                                <?php echo _l('debts_status_overdue'); ?>
                            </option>

                            </select>
                        </div>

                        <!-- Ghi chú -->
                        <div class="form-group">
                            <label for="note"><?php echo _l('debts_note'); ?></label>
                            <textarea name="note" id="note" class="form-control" rows="3"><?php echo isset($debt->note) ? html_escape($debt->note) : ''; ?></textarea>
                        </div>

                        <!-- Nút submit -->
                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
<script>
$(function () {
    $('.selectpicker').selectpicker();

    // Khi chọn nhà cung cấp → load danh sách hóa đơn
    $('#supplier_id').on('changed.bs.select', function () {
        let supplierId = $(this).val();

        if (!supplierId) {
            $('#invoice_number')
                .html('<option value=""><?php echo _l('select_invoice'); ?></option>')
                .prop('disabled', true)
                .selectpicker('refresh');
            clearInvoiceFields();
            return;
        }

        $.post(admin_url + 'suppliers/get_invoices_by_supplier', { supplier_id: supplierId }, function (data) {
            let html = '<option value=""><?php echo _l('select_invoice'); ?></option>';
            data.forEach(inv => {
                html += `<option value="${inv.id}">${inv.prefix}${inv.id}</option>`;
            });

            $('#invoice_number').html(html).prop('disabled', false).selectpicker('refresh');

            // Nếu đang edit thì set lại giá trị đã lưu
            let selectedInvoiceId = $('#invoice_number').data('invoice-id');
            if (selectedInvoiceId) {
                $('#invoice_number').selectpicker('val', selectedInvoiceId).trigger('changed.bs.select');
            }
        }, 'json');
    });

    // Khi chọn hóa đơn → load chi tiết hóa đơn
    $('#invoice_number').on('changed.bs.select', function () {
        let invoiceId = $(this).val();
        if (!invoiceId) {
            clearInvoiceFields();
            return;
        }

        $.post(admin_url + 'suppliers/get_invoice_info', { invoice_id: invoiceId }, function (data) {
            let subtotal = parseFloat(data.subtotal || 0);
            let paid = parseFloat(data.paid_amount || 0);
            let remaining = subtotal - paid;

            $('#amount').val(formatVND(subtotal));
            $('#paid_amount').val(formatVND(paid));
            $('#remaining_amount').val(formatVND(remaining));
        }, 'json');
    });

    // Tự động trigger khi đang edit
    let selectedSupplierId = $('#supplier_id').val();
    if (selectedSupplierId) {
        $('#supplier_id').selectpicker('val', selectedSupplierId).trigger('changed.bs.select');
    }

    // Khi nhập paid_amount → tính remaining_amount
    $('#paid_amount').on('input', function () {
        let total = toNumber($('#amount').val());
        let paid = toNumber($(this).val());
        let remaining = total - paid;
        if (remaining < 0) remaining = 0;
        $('#remaining_amount').val(formatVND(remaining));
    });

    // Hàm xóa nội dung các ô liên quan đến hóa đơn
    function clearInvoiceFields() {
        $('#amount').val('');
        $('#paid_amount').val('');
        $('#remaining_amount').val('');
    }

    // Định dạng số thành VND (1000000 → 1.000.000)
    function formatVND(number) {
        let n = Math.round(number);
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Chuyển chuỗi VND về số (1.000.000 → 1000000)
    function toNumber(str) {
        if (!str) return 0;
        return parseFloat(str.toString().replace(/\./g, '').replace(',', '.')) || 0;
    }

    // Validate form
    appValidateForm($('#debt-form'), {
        supplier_id: 'required',
        invoice_number: 'required',
        invoice_date: 'required',
        amount: 'required'
    });
});
</script>

</body>
</html>
