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
                        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'debt-form']); ?>

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
                       <!-- Số hóa đơn -->
                        <div class="form-group select-placeholder">
                            <label for="invoice_number" class="control-label"><?php echo _l('debts_invoice_number'); ?></label>
                            <select name="invoice_number" id="invoice_number"
                                class="form-control selectpicker"
                                data-live-search="true"
                                multiple
                                data-none-selected-text="<?php echo _l('select_invoice'); ?>"
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
                        <div class="form-group">
                            <label for="link_file" class="link_file"><?php echo _l('supplier_link_file'); ?> (Cho phép các định dạng file: Word, PDF, Excel)</label><br>
                            <?php if (!empty($debt) && !empty($debt->link_file)){ ?>
                            <?php if($debt->type_file=='doc'||$debt->type_file=='docx')
                                {
                                    ?>
                                    <a href="<?= base_url('/uploads/supplier_debt/' . $debt->id . '/' . $debt->link_file); ?>"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="28" height="28" viewBox="0 0 48 48">
                                    <linearGradient id="Q7XamDf1hnh~bz~vAO7C6a_pGHcje298xSl_gr1" x1="28" x2="28" y1="14.966" y2="6.45" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#42a3f2"></stop><stop offset="1" stop-color="#42a4eb"></stop></linearGradient><path fill="url(#Q7XamDf1hnh~bz~vAO7C6a_pGHcje298xSl_gr1)" d="M42,6H14c-1.105,0-2,0.895-2,2v7.003h32V8C44,6.895,43.105,6,42,6z"></path><linearGradient id="Q7XamDf1hnh~bz~vAO7C6b_pGHcje298xSl_gr2" x1="28" x2="28" y1="42" y2="33.054" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#11408a"></stop><stop offset="1" stop-color="#103f8f"></stop></linearGradient><path fill="url(#Q7XamDf1hnh~bz~vAO7C6b_pGHcje298xSl_gr2)" d="M12,33.054V40c0,1.105,0.895,2,2,2h28c1.105,0,2-0.895,2-2v-6.946H12z"></path><linearGradient id="Q7XamDf1hnh~bz~vAO7C6c_pGHcje298xSl_gr3" x1="28" x2="28" y1="-15.46" y2="-15.521" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#3079d6"></stop><stop offset="1" stop-color="#297cd2"></stop></linearGradient><path fill="url(#Q7XamDf1hnh~bz~vAO7C6c_pGHcje298xSl_gr3)" d="M12,15.003h32v9.002H12V15.003z"></path><linearGradient id="Q7XamDf1hnh~bz~vAO7C6d_pGHcje298xSl_gr4" x1="12" x2="44" y1="28.53" y2="28.53" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#1d59b3"></stop><stop offset="1" stop-color="#195bbc"></stop></linearGradient><path fill="url(#Q7XamDf1hnh~bz~vAO7C6d_pGHcje298xSl_gr4)" d="M12,24.005h32v9.05H12V24.005z"></path><path d="M22.319,13H12v24h10.319C24.352,37,26,35.352,26,33.319V16.681C26,14.648,24.352,13,22.319,13z" opacity=".05"></path><path d="M22.213,36H12V13.333h10.213c1.724,0,3.121,1.397,3.121,3.121v16.425	C25.333,34.603,23.936,36,22.213,36z" opacity=".07"></path><path d="M22.106,35H12V13.667h10.106c1.414,0,2.56,1.146,2.56,2.56V32.44C24.667,33.854,23.52,35,22.106,35z" opacity=".09"></path><linearGradient id="Q7XamDf1hnh~bz~vAO7C6e_pGHcje298xSl_gr5" x1="4.744" x2="23.494" y1="14.744" y2="33.493" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#256ac2"></stop><stop offset="1" stop-color="#1247ad"></stop></linearGradient><path fill="url(#Q7XamDf1hnh~bz~vAO7C6e_pGHcje298xSl_gr5)" d="M22,34H6c-1.105,0-2-0.895-2-2V16c0-1.105,0.895-2,2-2h16c1.105,0,2,0.895,2,2v16	C24,33.105,23.105,34,22,34z"></path><path fill="#fff" d="M18.403,19l-1.546,7.264L15.144,19h-2.187l-1.767,7.489L9.597,19H7.641l2.344,10h2.352l1.713-7.689	L15.764,29h2.251l2.344-10H18.403z"></path>
                                    </svg></a>  
                                    <?php } elseif($debt->type_file=='pdf') { ?>
                                        <a href="<?= base_url('/uploads/supplier_debt/' . $debt->id . '/' . $debt->link_file); ?>"><svg width="28px" height="28px" viewBox="-4 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M25.6686 26.0962C25.1812 26.2401 24.4656 26.2563 23.6984 26.145C22.875 26.0256 22.0351 25.7739 21.2096 25.403C22.6817 25.1888 23.8237 25.2548 24.8005 25.6009C25.0319 25.6829 25.412 25.9021 25.6686 26.0962ZM17.4552 24.7459C17.3953 24.7622 17.3363 24.7776 17.2776 24.7939C16.8815 24.9017 16.4961 25.0069 16.1247 25.1005L15.6239 25.2275C14.6165 25.4824 13.5865 25.7428 12.5692 26.0529C12.9558 25.1206 13.315 24.178 13.6667 23.2564C13.9271 22.5742 14.193 21.8773 14.468 21.1894C14.6075 21.4198 14.7531 21.6503 14.9046 21.8814C15.5948 22.9326 16.4624 23.9045 17.4552 24.7459ZM14.8927 14.2326C14.958 15.383 14.7098 16.4897 14.3457 17.5514C13.8972 16.2386 13.6882 14.7889 14.2489 13.6185C14.3927 13.3185 14.5105 13.1581 14.5869 13.0744C14.7049 13.2566 14.8601 13.6642 14.8927 14.2326ZM9.63347 28.8054C9.38148 29.2562 9.12426 29.6782 8.86063 30.0767C8.22442 31.0355 7.18393 32.0621 6.64941 32.0621C6.59681 32.0621 6.53316 32.0536 6.44015 31.9554C6.38028 31.8926 6.37069 31.8476 6.37359 31.7862C6.39161 31.4337 6.85867 30.8059 7.53527 30.2238C8.14939 29.6957 8.84352 29.2262 9.63347 28.8054ZM27.3706 26.1461C27.2889 24.9719 25.3123 24.2186 25.2928 24.2116C24.5287 23.9407 23.6986 23.8091 22.7552 23.8091C21.7453 23.8091 20.6565 23.9552 19.2582 24.2819C18.014 23.3999 16.9392 22.2957 16.1362 21.0733C15.7816 20.5332 15.4628 19.9941 15.1849 19.4675C15.8633 17.8454 16.4742 16.1013 16.3632 14.1479C16.2737 12.5816 15.5674 11.5295 14.6069 11.5295C13.948 11.5295 13.3807 12.0175 12.9194 12.9813C12.0965 14.6987 12.3128 16.8962 13.562 19.5184C13.1121 20.5751 12.6941 21.6706 12.2895 22.7311C11.7861 24.0498 11.2674 25.4103 10.6828 26.7045C9.04334 27.3532 7.69648 28.1399 6.57402 29.1057C5.8387 29.7373 4.95223 30.7028 4.90163 31.7107C4.87693 32.1854 5.03969 32.6207 5.37044 32.9695C5.72183 33.3398 6.16329 33.5348 6.6487 33.5354C8.25189 33.5354 9.79489 31.3327 10.0876 30.8909C10.6767 30.0029 11.2281 29.0124 11.7684 27.8699C13.1292 27.3781 14.5794 27.011 15.985 26.6562L16.4884 26.5283C16.8668 26.4321 17.2601 26.3257 17.6635 26.2153C18.0904 26.0999 18.5296 25.9802 18.976 25.8665C20.4193 26.7844 21.9714 27.3831 23.4851 27.6028C24.7601 27.7883 25.8924 27.6807 26.6589 27.2811C27.3486 26.9219 27.3866 26.3676 27.3706 26.1461ZM30.4755 36.2428C30.4755 38.3932 28.5802 38.5258 28.1978 38.5301H3.74486C1.60224 38.5301 1.47322 36.6218 1.46913 36.2428L1.46884 3.75642C1.46884 1.6039 3.36763 1.4734 3.74457 1.46908H20.263L20.2718 1.4778V7.92396C20.2718 9.21763 21.0539 11.6669 24.0158 11.6669H30.4203L30.4753 11.7218L30.4755 36.2428ZM28.9572 10.1976H24.0169C21.8749 10.1976 21.7453 8.29969 21.7424 7.92417V2.95307L28.9572 10.1976ZM31.9447 36.2428V11.1157L21.7424 0.871022V0.823357H21.6936L20.8742 0H3.74491C2.44954 0 0 0.785336 0 3.75711V36.2435C0 37.5427 0.782956 40 3.74491 40H28.2001C29.4952 39.9997 31.9447 39.2143 31.9447 36.2428Z" fill="#EB5757"></path> </g></svg></a>
                                    <?php }elseif($debt->type_file=='xls'|| $debt->type_file=='xlsx'){?>
                                        <a href="<?= base_url('/uploads/supplier_debt/' . $debt->id . '/' . $debt->link_file); ?>"><svg width="28px" height="28px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>file_type_excel2</title><path d="M28.781,4.405H18.651V2.018L2,4.588V27.115l16.651,2.868V26.445H28.781A1.162,1.162,0,0,0,30,25.349V5.5A1.162,1.162,0,0,0,28.781,4.405Zm.16,21.126H18.617L18.6,23.642h2.487v-2.2H18.581l-.012-1.3h2.518v-2.2H18.55l-.012-1.3h2.549v-2.2H18.53v-1.3h2.557v-2.2H18.53v-1.3h2.557v-2.2H18.53v-2H28.941Z" style="fill:#20744a;fill-rule:evenodd"></path><rect x="22.487" y="7.439" width="4.323" height="2.2" style="fill:#20744a"></rect><rect x="22.487" y="10.94" width="4.323" height="2.2" style="fill:#20744a"></rect><rect x="22.487" y="14.441" width="4.323" height="2.2" style="fill:#20744a"></rect><rect x="22.487" y="17.942" width="4.323" height="2.2" style="fill:#20744a"></rect><rect x="22.487" y="21.443" width="4.323" height="2.2" style="fill:#20744a"></rect><polygon points="6.347 10.673 8.493 10.55 9.842 14.259 11.436 10.397 13.582 10.274 10.976 15.54 13.582 20.819 11.313 20.666 9.781 16.642 8.248 20.513 6.163 20.329 8.585 15.666 6.347 10.673" style="fill:#ffffff;fill-rule:evenodd"></polygon></g></svg></a>
                                    <?php
                                }
                            }  ?>

                            <input type="file" name="link_file" class="form-control " id="link_file" />
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
                $('#invoice_number').selectpicker('val', selectedInvoiceId.split(',')).trigger('changed.bs.select');
            }
        }, 'json');
    });

    // Khi chọn hóa đơn → load chi tiết hóa đơn
    $('#invoice_number').on('changed.bs.select', function () {
        let invoiceIds = $(this).val(); // Lấy danh sách ID hóa đơn
        if (!invoiceIds || invoiceIds.length === 0) {
            clearInvoiceFields();
            return;
        }

        // Gửi yêu cầu AJAX để lấy thông tin nhiều hóa đơn
        $.post(admin_url + 'suppliers/get_invoices_info', { invoice_ids: invoiceIds }, function (data) {
            let totalSubtotal = 0;
            let totalPaid = 0;
            let totalRemaining = 0;

            // Tính tổng các giá trị từ dữ liệu trả về
            data.forEach(invoice => {
                let subtotal = parseFloat(invoice.subtotal || 0);
                let paid = parseFloat(invoice.paid_amount || 0);
                let remaining = subtotal - paid;

                totalSubtotal += subtotal;
                totalPaid += paid;
                totalRemaining += remaining;
            });

            // Cập nhật các trường
            $('#amount').val(formatVND(totalSubtotal));
            $('#paid_amount').val(formatVND(totalPaid));
            $('#remaining_amount').val(formatVND(totalRemaining));
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
