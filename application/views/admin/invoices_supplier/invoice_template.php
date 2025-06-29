<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s invoice accounting-template">
    <div class="additional"></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="f_client_id">
                    <div class="form-group select-placeholder">
                        <div class="form-group select-placeholder">
                            <label for="supplierid" class="control-label"><small class="req text-danger">* </small><?php echo _l('suppliers'); ?></label>
                            <select name="supplierid" class="form-control selectpicker" data-live-search="true" data-none-selected-text="Chọn nhà cung cấp" required>
                             <option value="">Không có mục nào được chọn</option>
                              
                            <?php foreach ($ds_suppliers as $type): ?>
                                    <option value="<?php echo $type['id']; ?>" <?php echo (isset($invoices_supplier) && $invoices_supplier->supplierid == $type['id']) ? 'selected' : ''; ?>>
                                        <?php echo $type['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr class="hr-10" />
                        <a href="#" class="edit_shipping_billing_info" data-toggle="modal"
                            data-target="#billing_and_shipping_details"><i class="fa-regular fa-pen-to-square"></i></a>
                        <?php include_once APPPATH . 'views/admin/invoices_supplier/billing_and_shipping_template.php'; ?>
                    </div>
                    <div class="col-md-6">
                        <p class="bold">
                            <?= _l('invoice_bill_to'); ?>
                        </p>
                        <address>
                            <span class="billing_street">
                                <?php $billing_street = (isset($invoices_supplier) ? $invoices_supplier->billing_street : '--'); ?>
                                <?php $billing_street = ($billing_street == '' ? '--' : $billing_street); ?>
                                <?= process_text_content_for_display($billing_street); ?></span><br>
                            <span class="billing_city">
                                <?php $billing_city = (isset($invoices_supplier) ? $invoices_supplier->billing_city : '--'); ?>
                                <?php $billing_city = ($billing_city == '' ? '--' : $billing_city); ?>
                                <?= e($billing_city); ?></span>,
                            <span class="billing_state">
                                <?php $billing_state = (isset($invoices_supplier) ? $invoices_supplier->billing_state : '--'); ?>
                                <?php $billing_state = ($billing_state == '' ? '--' : $billing_state); ?>
                                <?= e($billing_state); ?></span>
                            <br />
                            <span class="billing_country">
                                <?php $billing_country = (isset($invoices_supplier) ? get_country_short_name($invoices_supplier->billing_country) : '--'); ?>
                                <?php $billing_country = ($billing_country == '' ? '--' : $billing_country); ?>
                                <?= e($billing_country); ?></span>,
                            <span class="billing_zip">
                                <?php $billing_zip = (isset($invoices_supplier) ? $invoices_supplier->billing_zip : '--'); ?>
                                <?php $billing_zip = ($billing_zip == '' ? '--' : $billing_zip); ?>
                                <?= e($billing_zip); ?></span>
                        </address>
                    </div>
                    <div class="col-md-6">
                        <p class="bold">
                            <?= _l('ship_to'); ?>
                        </p>
                        <address>
                            <span class="shipping_street">
                                <?php $shipping_street = (isset($invoices_supplier) ? $invoices_supplier->shipping_street : '--'); ?>
                                <?php $shipping_street = ($shipping_street == '' ? '--' : $shipping_street); ?>
                                <?= process_text_content_for_display($shipping_street); ?></span><br>
                            <span class="shipping_city">
                                <?php $shipping_city = (isset($invoices_supplier) ? $invoices_supplier->shipping_city : '--'); ?>
                                <?php $shipping_city = ($shipping_city == '' ? '--' : $shipping_city); ?>
                                <?= e($shipping_city); ?></span>,
                            <span class="shipping_state">
                                <?php $shipping_state = (isset($invoices_supplier) ? $invoices_supplier->shipping_state : '--'); ?>
                                <?php $shipping_state = ($shipping_state == '' ? '--' : $shipping_state); ?>
                                <?= e($shipping_state); ?></span>
                            <br />
                            <span class="shipping_country">
                                <?php $shipping_country = (isset($invoices_supplier) ? get_country_short_name($invoices_supplier->shipping_country) : '--'); ?>
                                <?php $shipping_country = ($shipping_country == '' ? '--' : $shipping_country); ?>
                                <?= e($shipping_country); ?></span>,
                            <span class="shipping_zip">
                                <?php $shipping_zip = (isset($invoices_supplier) ? $invoices_supplier->shipping_zip : '--'); ?>
                                <?php $shipping_zip = ($shipping_zip == '' ? '--' : $shipping_zip); ?>
                                <?= e($shipping_zip); ?></span>
                        </address>
                    </div>
                </div>
                <?php $next_invoice_number = get_option('next_invoice_number'); ?>
                <?php $format              = get_option('invoice_number_format'); ?>
                <?php
if (isset($invoices_supplier)) {
    $format = $invoices_supplier->number_format;
}

$prefix = get_option('invoice_prefix');

if ($format == 1) {
    $__number = $next_invoice_number;

    if (isset($invoices_supplier)) {
        $__number = $invoices_supplier->number;
        $prefix   = '<span id="prefix">' . $invoices_supplier->prefix . '</span>';
    }
} elseif ($format == 2) {
    if (isset($invoices_supplier)) {
        $__number = $invoices_supplier->number;
        $prefix   = $invoices_supplier->prefix;
        $prefix   = '<span id="prefix">' . $prefix . '</span><span id="prefix_year">' . date('Y', strtotime($invoices_supplier->date)) . '</span>/';
    } else {
        $__number = $next_invoice_number;
        $prefix   = $prefix . '<span id="prefix_year">' . date('Y') . '</span>/';
    }
} elseif ($format == 3) {
    if (isset($invoices_supplier)) {
        $yy       = date('y', strtotime($invoices_supplier->date));
        $__number = $invoices_supplier->number;
        $prefix   = '<span id="prefix">' . $invoices_supplier->prefix . '</span>';
    } else {
        $yy       = date('y');
        $__number = $next_invoice_number;
    }
} elseif ($format == 4) {
    if (isset($invoices_supplier)) {
        $yyyy     = date('Y', strtotime($invoices_supplier->date));
        $mm       = date('m', strtotime($invoices_supplier->date));
        $__number = $invoices_supplier->number;
        $prefix   = '<span id="prefix">' . $invoices_supplier->prefix . '</span>';
    } else {
        $yyyy     = date('Y');
        $mm       = date('m');
        $__number = $next_invoice_number;
    }
}


$_is_draft            = (isset($invoices_supplier) && $invoices_supplier->status == Invoices_supplier_model::STATUS_DRAFT) ? true : false;
$_invoice_number      = str_pad($__number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
$isedit               = isset($invoices_supplier) ? 'true' : 'false';
$data_original_number = isset($invoices_supplier) ? $invoices_supplier->number : 'false';

?>
                <div class="row">
                    <div class="col-md-6">
                        <?php $value      = isset($invoices_supplier) ? _d($invoices_supplier->date) : _d(date('Y-m-d')); ?>
                        <?php $date_attrs = (isset($invoices_supplier) && $invoices_supplier->recurring > 0 && $invoices_supplier->last_recurring_date != null) ? ['disabled' => true] : []; ?>
                        <?= render_date_input('date', 'invoice_add_edit_date', $value, $date_attrs); ?>
                    </div>
                    <div class="col-md-6">
                        <?php $value = isset($invoices_supplier) ? _d($invoices_supplier->duedate) : (get_option('invoice_due_after') != 0 ? _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY'))) : ''); ?>
                        <?= render_date_input('duedate', 'invoice_add_edit_duedate', $value); ?>
                    </div>
                </div>
                <?php $rel_id = (isset($invoices_supplier) ? $invoices_supplier->id : false); ?>
                <?php if (isset($custom_fields_rel_transfer)) {
                    $rel_id = $custom_fields_rel_transfer;
                } ?>
                <?= render_custom_fields('invoice', $rel_id); ?>
            </div>
            <div class="col-md-6">
                <div class="tw-ml-3">
                    <div class="form-group">
                        <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i>
                            <?= _l('tags'); ?></label>
                        <input type="text" class="tagsinput" id="tags" name="tags"
                            value="<?= isset($invoices_supplier) ? prep_tags_input(get_tags_in($invoices_supplier->id, 'invoice')) : ''; ?>"
                            data-role="tagsinput">
                    </div>
                    <div
                        class="form-group mbot15<?= count($payment_modes) > 0 ? ' select-placeholder' : ''; ?>">
                        <label for="allowed_payment_modes"
                            class="control-label"><?= _l('invoice_add_edit_allowed_payment_modes'); ?></label>
                        <br />
                        <?php if (count($payment_modes) > 0) { ?>
                        <select class="selectpicker"
                            data-toggle="<?= $this->input->get('allowed_payment_modes'); ?>"
                            name="allowed_payment_modes[]" data-actions-box="true" multiple="true" data-width="100%"
                            data-title="<?= _l('dropdown_non_selected_tex'); ?>">
                            <?php foreach ($payment_modes as $mode) {
                                $selected = '';
                                if (isset($invoices_supplier)) {
                                    if ($invoices_supplier->allowed_payment_modes) {
                                        $inv_modes = unserialize($invoices_supplier->allowed_payment_modes);
                                        if (is_array($inv_modes)) {
                                            foreach ($inv_modes as $_allowed_payment_mode) {
                                                if ($_allowed_payment_mode == $mode['id']) {
                                                    $selected = ' selected';
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($mode['selected_by_default'] == 1) {
                                        $selected = ' selected';
                                    }
                                } ?>
                            <option
                                value="<?= e($mode['id']); ?>"
                                <?= e($selected); ?>>
                                <?= e($mode['name']); ?>
                            </option>
                            <?php
                            } ?>
                        </select>
                        <?php } else { ?>
                        <p class="tw-text-neutral-500">
                            <?= _l('invoice_add_edit_no_payment_modes_found'); ?>
                        </p>
                        <a class="btn btn-primary btn-sm"
                            href="<?= admin_url('paymentmodes'); ?>">
                            <?= _l('new_payment_mode'); ?>
                        </a>
                        <?php } ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?php
                                     $currency_attr = ['disabled' => true, 'data-show-subtext' => true];
$currency_attr                                      = apply_filters_deprecated('invoice_currency_disabled', [$currency_attr], '2.3.0', 'invoice_currency_attributes');

foreach ($currencies as $currency) {
    if ($currency['isdefault'] == 1) {
        $currency_attr['data-base'] = $currency['id'];
    }
    if (isset($invoices_supplier)) {
        if ($currency['id'] == $invoices_supplier->currency) {
            $selected = $currency['id'];
        }
    } else {
        if ($currency['isdefault'] == 1) {
            $selected = $currency['id'];
        }
    }
}
$currency_attr = hooks()->apply_filters('invoice_currency_attributes', $currency_attr);
?>
                            <?= render_select('currency', $currencies, ['id', 'name', 'symbol'], 'invoice_add_edit_currency', $selected, $currency_attr); ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                                $selected = isset($invoices_supplier) ? $invoices_supplier->sale_agent : (get_option('automatically_set_logged_in_staff_sales_agent') == '1' ? get_staff_user_id() : '');

foreach ($staff as $member) {
    if (isset($invoices_supplier) && $invoices_supplier->sale_agent == $member['staffid']) {
        $selected = $member['staffid'];
        break;
    }
}

echo render_select('sale_agent', $staff, ['staffid', ['firstname', 'lastname']], 'sale_agent_string', $selected);
?>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group select-placeholder" <?php if (isset($invoices_supplier) && ! empty($invoices_supplier->is_recurring_from)) { ?>
                                data-toggle="tooltip"
                                data-title="<?= _l('create_recurring_from_child_error_message', [_l('invoice_lowercase'), _l('invoice_lowercase'), _l('invoice_lowercase')]); ?>"
                                <?php } ?>>
                                <label for="recurring" class="control-label">
                                    <?= _l('invoice_add_edit_recurring'); ?>
                                </label>
                                <select class="selectpicker" data-width="100%" name="recurring"
                                    data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>"
                                    <?php
                        // The problem is that this invoice was generated from previous recurring invoice
                        // Then this new invoice you set it as recurring but the next invoice date was still taken from the previous invoice.
                        if (isset($invoices_supplier) && ! empty($invoices_supplier->is_recurring_from)) {
                            echo 'disabled';
                        } ?>>
                                    <?php for ($i = 0; $i <= 12; $i++) { ?>
                                    <?php
                              $selected = '';
                                        if (isset($invoices_supplier)) {
                                            if ($invoices_supplier->custom_recurring == 0) {
                                                if ($invoices_supplier->recurring == $i) {
                                                    $selected = 'selected';
                                                }
                                            }
                                        }
                                        if ($i == 0) {
                                            $reccuring_string = _l('invoice_add_edit_recurring_no');
                                        } elseif ($i == 1) {
                                            $reccuring_string = _l('invoice_add_edit_recurring_month', $i);
                                        } else {
                                            $reccuring_string = _l('invoice_add_edit_recurring_months', $i);
                                        }
                                        ?>
                                    <option value="<?= e($i); ?>" <?= e($selected); ?>>
                                        <?= e($reccuring_string); ?>
                                    </option>
                                    <?php } ?>
                                    <option value="custom" <?= isset($invoices_supplier) && $ininvoices_suppliervoice->recurring != 0 && $invoices_supplier->custom_recurring == 1 ? 'selected' : ''; ?>>
                                        <?= _l('recurring_custom'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group select-placeholder">
                                <label for="discount_type"
                                    class="control-label"><?= _l('discount_type'); ?></label>
                                <select name="discount_type" class="selectpicker" data-width="100%"
                                    data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                                    <option value="" selected>
                                        <?= _l('no_discount'); ?>
                                    </option>
                                    <option value="before_tax" <?= isset($invoices_supplier) && $invoices_supplier->discount_type == 'before_tax' ? 'selected' : ''; ?>>
                                        <?= _l('discount_type_before_tax'); ?>
                                    </option>
                                    <option value="after_tax" <?= isset($invoices_supplier) && $invoices_supplier->discount_type == 'after_tax' ? 'selected' : ''; ?>>
                                        <?= _l('discount_type_after_tax'); ?>
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div
                            class="recurring_custom<?= (isset($invoices_supplier) && $invoices_supplier->custom_recurring != 1) || (! isset($invoices_supplier)) ? ' hide' : ''; ?>">
                            <div class="col-md-6">
                                <?php $value = (isset($invoices_supplier) && $invoices_supplier->custom_recurring == 1 ? $invoices_supplier->recurring : 1); ?>
                                <?= render_input('repeat_every_custom', '', $value, 'number', ['min' => 1]); ?>
                            </div>
                            <div class="col-md-6">
                                <select name="repeat_type_custom" id="repeat_type_custom" class="selectpicker"
                                    data-width="100%"
                                    data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                                    <?php $selectedType = isset($invoices_supplier) && $invoices_supplier->custom_recurring == 1 ? $invoices_supplier->recurring_type : ''; ?>
                                    <option value="day" <?= $selectedType == 'day' ? 'selected' : ''; ?>><?= _l('invoice_recurring_days'); ?>
                                    </option>
                                    <option value="week" <?= $selectedType == 'week' ? 'selected' : ''; ?>><?= _l('invoice_recurring_weeks'); ?>
                                    </option>
                                    <option value="month" <?= $selectedType == 'month' ? 'selected' : ''; ?>><?= _l('invoice_recurring_months'); ?>
                                    </option>
                                    <option value="year" <?= $selectedType == 'year' ? 'selected' : ''; ?>><?= _l('invoice_recurring_years'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div id="cycles_wrapper"
                            class="<?= ! isset($invoices_supplier) || (isset($invoices_supplier) && $invoices_supplier->recurring == 0) ? 'hide' : ''; ?>">
                            <div class="col-md-12">
                                <?php $value = (isset($invoices_supplier) ? $invoices_supplier->cycles : 0); ?>
                                <div class="form-group recurring-cycles">
                                    <label
                                        for="cycles"><?= _l('recurring_total_cycles'); ?>
                                        <?php if (isset($invoices_supplier) && $invoices_supplier->total_cycles > 0) {
                                            echo '<small>' . e(_l('cycles_passed', $invoices_supplier->total_cycles)) . '</small>';
                                        } ?>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control"
                                            <?= $value == 0 ? 'disabled' : ''; ?>
                                        name="cycles" id="cycles"
                                        value="<?= e($value); ?>"
                                        <?php if (isset($invoices_supplier) && $invoices_supplier->total_cycles > 0) {
                                            echo 'min="' . e($invoices_supplier->total_cycles) . '"';
                                        } ?>>
                                        <div class="input-group-addon">
                                            <div class="checkbox">
                                                <input type="checkbox"
                                                    <?= $value == 0 ? 'checked' : ''; ?>
                                                id="unlimited_cycles">
                                                <label
                                                    for="unlimited_cycles"><?= _l('cycles_infinity'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
            <?php $value = (isset($invoices_supplier) ? $invoices_supplier->adminnote : ''); ?>
                    <?= render_textarea('adminnote', 'invoice_add_edit_admin_note', $value); ?>
            </div>
        </div>
    </div>

    <hr class="hr-panel-separator" />

    <div class="panel-body">
        <?php if (isset($invoice_from_project)) {
            echo '<hr class="no-mtop" />';
        } ?>
        <div class="table-responsive s_table">
            <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop">
                <thead>
                    <tr>
                        <th></th>
                        <th width="15%" align="left"><i class="fa-solid fa-circle-exclamation tw-mr-1"
                                aria-hidden="true" data-toggle="tooltip"
                                data-title="<?= _l('item_description_new_lines_notice'); ?>"></i>
                            <?= _l('invoice_table_item_heading'); ?>
                        </th>
                        <th width="15%" align="left">
                            <?= _l('invoice_table_item_description'); ?>
                        </th>
                        <?php
                  $custom_fields = get_custom_fields('items');

foreach ($custom_fields as $cf) {
    echo '<th width="15%" align="left" class="custom_field">' . e($cf['name']) . '</th>';
}
$qty_heading = _l('invoice_table_quantity_heading');
if (isset($invoices_supplier) && $invoices_supplier->show_quantity_as == 2 || isset($hours_quantity)) {
    $qty_heading = _l('invoice_table_hours_heading');
} elseif (isset($invoices_supplier) && $invoices_supplier->show_quantity_as == 3) {
    $qty_heading = _l('invoice_table_quantity_heading') . '/' . _l('invoice_table_hours_heading');
}
?>
                        <th width="10%" align="right" class="qty">
                            <?= e($qty_heading); ?>
                        </th>
                        <th width="10%" align="right">
                            <?= _l('invoice_table_rate_heading'); ?>
                        </th>
                        <th width="15%" align="right">
                            <?= _l('invoice_table_tax_heading'); ?>
                        </th>
                        <th width="15%" align="right">
                            <?= _l('Hình ảnh'); ?>
                        </th>
                        <th width="10%" align="right">
                            <?= _l('invoice_table_amount_heading'); ?>
                        </th>
                        
                        <th align="center"><i class="fa fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="main">
                        <td></td>
                        <td>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="<?= _l('item_description_placeholder'); ?>"></textarea>
                        </td>
                        <td>
                            <textarea name="long_description" rows="4" class="form-control"
                                placeholder="<?= _l('item_long_description_placeholder'); ?>"></textarea>
                        </td>
                        <?= render_custom_fields_items_table_add_edit_preview(); ?>
                        <td>
                            <input type="number" name="quantity" min="0" value="1" class="form-control"
                                placeholder="<?= _l('item_quantity_placeholder'); ?>">
                            <input type="text"
                                placeholder="<?= _l('unit'); ?>"
                                data-toggle="tooltip" data-title="e.q kg, lots, packs" name="unit"
                                class="form-control input-transparent text-right">
                        </td>
                        
                        <td>
                            <input type="number" name="rate" class="form-control"
                                placeholder="<?= _l('item_rate_placeholder'); ?>">
                        </td>
                        <td>
                            <?php
   $default_tax = unserialize(get_option('default_tax'));
$select         = '<select class="selectpicker display-block tax main-tax" data-width="100%" name="taxname" multiple data-none-selected-text="' . _l('no_tax') . '">';

//  $select .= '<option value=""'.(count($default_tax) == 0 ? ' selected' : '').'>'._l('no_tax').'</option>';
foreach ($taxes as $tax) {
    $selected = '';
    if (is_array($default_tax)) {
        if (in_array($tax['name'] . '|' . $tax['taxrate'], $default_tax)) {
            $selected = ' selected ';
        }
    }
    $select .= '<option value="' . $tax['name'] . '|' . $tax['taxrate'] . '"' . $selected . 'data-taxrate="' . $tax['taxrate'] . '" data-taxname="' . $tax['name'] . '" data-subtext="' . $tax['name'] . '">' . $tax['taxrate'] . '%</option>';
}
$select .= '</select>';
echo $select;
?>
                        </td>
                        <td>
                        <div class="image_file image_file_preview">
                    <span for="imageUpload" id="imagePreview">
                        <img src="" alt="" height="100" width="100">
                    </span>
                    </div>
<div class="main main_image_invoice">
    <input type="hidden" name="link_image" id="link_image">
    <div class="drag-area">
        <div class="row">
            <div class="col-md-12" style="text-align:right;">
                <div class="ajax-upload-dragdrop">
                    <div class="upload" style="position: relative; overflow: hidden; cursor: pointer;">
                        <i class="fa fa-camera" aria-hidden="true"></i>
                        <input type="file" id="imageUpload" name="profile_boss_photo" class="imageUpload form-control" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 34px; left: 0px; z-index: 100; opacity: 0;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                        </td>
                        <td>
</td>
                        <td>
                            <?php $new_item = ! isset($invoices_supplier) ? 'undefined' : true; ?>
                            <button type="button"
                                onclick="add_item_to_table_supplier('undefined','undefined',<?= e($new_item); ?>); return false;"
                                class="btn pull-right btn-primary"><i class="fa fa-check"></i></button>
                        </td>
                    </tr>
                    <?php if (isset($invoices_supplier) || isset($add_items)) {
                        $i               = 1;
                        $items_indicator = 'newitems';
                        if (isset($invoices_supplier)) {
                            $add_items       = $invoices_supplier->items;
                            $items_indicator = 'items';
                        }

                        foreach ($add_items as $item) {
                            $manual    = false;
                            $table_row = '<tr class="sortable item">';
                            $table_row .= '<td class="dragger">';
                            if (! is_numeric($item['qty'])) {
                                $item['qty'] = 1;
                            }
                            $invoice_item_taxes = get_invoice_supplier_item_taxes($item['id']);
                            // passed like string
                            if ($item['id'] == 0) {
                                $invoice_item_taxes = $item['taxname'];
                                $manual             = true;
                            }
                            $table_row .= form_hidden('' . $items_indicator . '[' . $i . '][itemid]', $item['id']);
                            $amount = $item['rate'] * $item['qty'];
                            $link_image=$item['link_image'] ?? '';
                            if ($link_image) {
                                $link_image = '<a href="' . $link_image . '" target="_blank"><img src="' . $link_image . '" class="invoice-item-image" alt="Image" style="width:100%;max-height:500px;"></a>';
                            } else {
                                $link_image = '';
                            }
                            $amount = app_format_number($amount);
                            // order input
                            $table_row .= '<input type="hidden" class="order" name="' . $items_indicator . '[' . $i . '][order]">';
                            $table_row .= '</td>';
                            $table_row .= '<td class="bold description"><textarea name="' . $items_indicator . '[' . $i . '][description]" class="form-control" rows="5">' . clear_textarea_breaks($item['description']) . '</textarea></td>';
                            $table_row .= '<td><textarea name="' . $items_indicator . '[' . $i . '][long_description]" class="form-control" rows="5">' . clear_textarea_breaks($item['long_description']) . '</textarea></td>';

                            $table_row .= render_custom_fields_items_table_in($item, $items_indicator . '[' . $i . ']');

                            $table_row .= '<td><input type="number" min="0" onblur="calculate_total();" onchange="calculate_total();" data-quantity name="' . $items_indicator . '[' . $i . '][qty]" value="' . $item['qty'] . '" class="form-control">';

                            $unit_placeholder = '';
                            if (! $item['unit']) {
                                $unit_placeholder = _l('unit');
                                $item['unit']     = '';
                            }

                            $table_row .= '<input type="text" placeholder="' . $unit_placeholder . '" name="' . $items_indicator . '[' . $i . '][unit]" class="form-control input-transparent text-right" value="' . $item['unit'] . '">';

                            $table_row .= '</td>';
                            $table_row .= '<td class="rate"><input type="number" data-toggle="tooltip" title="' . _l('numbers_not_formatted_while_editing') . '" onblur="calculate_total();" onchange="calculate_total();" name="' . $items_indicator . '[' . $i . '][rate]" value="' . $item['rate'] . '" class="form-control"></td>';
                            $table_row .= '<td class="taxrate">' . $this->misc_model->get_taxes_dropdown_template('' . $items_indicator . '[' . $i . '][taxname][]', $invoice_item_taxes, 'invoice', $item['id'], true, $manual) . '</td>';
                            $table_row .= '<td class="image">';
                            $table_row .= '<div class="image_file">';
                            $table_row .= '<span for="imageUpload' . $i . '" id="imagePreview' . $i . '">';
                            if ($item['link_image'] && $item['link_image'] !== '') {
                                $table_row .= '<a href="' . htmlspecialchars($item['link_image']) . '" target="_blank">';
                                $table_row .= '<img src="' . htmlspecialchars($item['link_image']) . '" alt="" height="100" width="100%" style="object-fit: contain;">';
                                $table_row .= '</a>';
                            } else {
                                $table_row .= '<span></span>';
                            }
                            $table_row .= '</span>';
                            $table_row .= '</div>';
                            $table_row .= '<div class="drag-area">';
                            $table_row .= '<input type="hidden" name="' . $items_indicator . '[' . $i . '][link_image]" id="link_image' . $i . '" value="' . htmlspecialchars($item['link_image'] ?? '') . '">';
                            $table_row .= '<div class="row">';
                            $table_row .= '<div class="col-md-12" style="text-align:right;">';
                            $table_row .= '<div class="ajax-upload-dragdrop">';
                            $table_row .= '<div class="upload" style="position: relative; overflow: hidden; cursor: pointer;">';
                            $table_row .= '<i class="fa fa-camera" aria-hidden="true"></i>';
                            $table_row .= '<input type="file" id="imageUpload' . $i . '" name="' . $items_indicator . '[' . $i . '][profile_boss_photo]" class="imageUpload form-control" style="position: absolute; cursor: pointer; top: 0px; width: 100%; height: 34px; left: 0px; z-index: 100; opacity: 0;" aria-invalid="false">';
                            $table_row .= '</div>';
                            $table_row .= '</div>';
                            $table_row .= '</div>';
                            $table_row .= '</div>';
                            $table_row .= '</td>';
                            $table_row .= '<td class="amount" align="right">' . $amount . '</td>';
                            $table_row .= '<td><a href="#" class="btn btn-danger pull-left !tw-px-3" onclick="delete_item(this,' . $item['id'] . '); return false;"><i class="fa fa-times"></i></a></td>';
                            if (isset($item['task_id'])) {
                                if (! is_array($item['task_id'])) {
                                    $table_row .= form_hidden('billed_tasks[' . $i . '][]', $item['task_id']);
                                } else {
                                    foreach ($item['task_id'] as $task_id) {
                                        $table_row .= form_hidden('billed_tasks[' . $i . '][]', $task_id);
                                    }
                                }
                            } elseif (isset($item['expense_id'])) {
                                $table_row .= form_hidden('billed_expenses[' . $i . '][]', $item['expense_id']);
                            }
                            $table_row .= '</tr>';
                            echo $table_row;
                            $i++;
                        }
                    }
?>
                </tbody>
            </table>
        </div>
        <div class="col-md-8 col-md-offset-4">
            <table class="table text-right">
                <tbody>
                    <tr id="subtotal">
                        <td>
                            <span
                                class="bold tw-text-neutral-700"><?= _l('invoice_subtotal'); ?>
                                :</span>
                        </td>
                        <td class="subtotal">
                        </td>
                    </tr>
                    <tr id="discount_area">
                        <td>
                            <div class="row">
                                <div class="col-md-7">
                                    <span class="bold tw-text-neutral-700">
                                        <?= _l('invoice_discount'); ?>
                                    </span>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group" id="discount-total">

                                        <input type="number"
                                            value="<?= isset($invoices_supplier) ? $invoices_supplier->discount_percent : 0; ?>"
                                            class="form-control pull-left input-discount-percent<?= isset($invoices_supplier) && ! is_sale_discount($invoices_supplier, 'percent') && is_sale_discount_applied($invoices_supplier) ? ' hide' : ''; ?>"
                                            min="0" max="100" name="discount_percent">

                                        <input type="number" data-toggle="tooltip"
                                            data-title="<?= _l('numbers_not_formatted_while_editing'); ?>"
                                            value="<?= isset($invoices_supplier) ? $invoices_supplier->discount_total : 0; ?>"
                                            class="form-control pull-left input-discount-fixed<?= ! isset($invoices_supplier) || (isset($invoices_supplier) && ! is_sale_discount($invoices_supplier, 'fixed')) ? ' hide' : ''; ?>"
                                            min="0" name="discount_total">

                                        <div class="input-group-addon">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" id="dropdown_menu_tax_total_type"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <span class="discount-total-type-selected">
                                                        <?php if (! isset($invoices_supplier) || isset($invoices_supplier) && (is_sale_discount($invoices_supplier, 'percent') || ! is_sale_discount_applied($invoices_supplier))) {
                                                            echo '%';
                                                        } else {
                                                            echo _l('discount_fixed_amount');
                                                        } ?>
                                                    </span>
                                                    <span class="caret"></span>
                                                </a>
                                                <ul class="dropdown-menu" id="discount-total-type-dropdown"
                                                    aria-labelledby="dropdown_menu_tax_total_type">
                                                    <li>
                                                        <a href="#"
                                                            class="discount-total-type discount-type-percent<?= (! isset($invoices_supplier) || (isset($invoices_supplier) && is_sale_discount($invoices_supplier, 'percent')) || (isset($invoices_supplier) && ! is_sale_discount_applied($invoices_supplier))) ? ' selected' : ''; ?>">%</a>
                                                    </li>
                                                    <li>
                                                        <a href="#"
                                                            class="discount-total-type discount-type-fixed<?= (isset($invoices_supplier) && is_sale_discount($invoices_supplier, 'fixed')) ? ' selected' : ''; ?>">
                                                            <?= _l('discount_fixed_amount'); ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="discount-total"></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-md-7">
                                    <span
                                        class="bold tw-text-neutral-700"><?= _l('invoice_adjustment'); ?></span>
                                </div>
                                <div class="col-md-5">
                                    <input type="number" data-toggle="tooltip"
                                        data-title="<?= _l('numbers_not_formatted_while_editing'); ?>"
                                        value="<?= isset($invoices_supplier) ? $invoices_supplier->adjustment : 0; ?>"
                                        class="form-control pull-left" name="adjustment">
                                </div>
                            </div>
                        </td>
                        <td class="adjustment"></td>
                    </tr>
                    <tr>
                        <td><span
                                class="bold tw-text-neutral-700"><?= _l('invoice_total'); ?>
                                :</span>
                        </td>
                        <td class="total">
                        </td>
                    </tr>
                    <?php hooks()->do_action('after_admin_invoice_form_total_field', $invoices_supplier ?? null); ?>
                </tbody>
            </table>
        </div>
        <div id="removed-items"></div>
        <div id="billed-tasks"></div>
        <div id="billed-expenses"></div>
        <?= form_hidden('task_id'); ?>
        <?= form_hidden('expense_id'); ?>

    </div>

    <hr class="hr-panel-separator" />

    <div class="panel-body">
        <?php $value = (isset($invoices_supplier) ? $invoices_supplier->clientnote : get_option('predefined_clientnote_invoice')); ?>
        <?= render_textarea('clientnote', 'Ghi chú nhà cung cấp', $value); ?>
        <?php $value = (isset($invoices_supplier) ? $invoices_supplier->terms : get_option('predefined_terms_invoice')); ?>
        <?= render_textarea('terms', 'terms_and_conditions', $value, [], [], 'mtop15'); ?>
    </div>

    <?php hooks()->do_action('after_render_invoice_template', $invoices_supplier ?? false); ?>
</div>

<div class="btn-bottom-pusher"></div>
<div class="btn-bottom-toolbar text-right">
    <?php if (! isset($invoices_supplier)) { ?>
    <button class="btn-tr btn btn-default mright5 text-right invoice-form-submit save-as-draft transaction-submit">
        <?= _l('save_as_draft'); ?>
    </button>
    <?php } ?>
    <div class="btn-group dropup">
        <button type="button"
            class="btn-tr btn btn-primary invoice-form-submit transaction-submit"><?= _l('submit'); ?></button>
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right width200">
            <li>
                <a href="#" class="invoice-form-submit save-and-send transaction-submit">
                    <?= _l('save_and_send'); ?>
                </a>
            </li>
            <?php if (! isset($invoices_supplier)) { ?>
            <li>
                <a href="#" class="invoice-form-submit save-and-send-later transaction-submit">
                    <?= _l('save_and_send_later'); ?>
                </a>
            </li>
            <li>
                <a href="#" class="invoice-form-submit save-and-record-payment transaction-submit">
                    <?= _l('save_and_record_payment'); ?>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
<style>
    .image_file
    {
        
        text-align: right;
    }
    .image_file_preview
    {
        display: none;
    }
    .main_image_invoice
    {
        margin: 0!important;
        border: 0!important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CSRF token
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    // Helper function to show modal
    function showModal(modalElement) {
        if (!modalElement.classList.contains('show')) {
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            modalElement.setAttribute('aria-hidden', 'false');
            document.body.classList.add('modal-open');
            document.body.style.paddingRight = '17px';
            var backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    }

    // Helper function to hide modal
    function hideModal(modalElement) {
        if (modalElement.classList.contains('show')) {
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            modalElement.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        }
    }

    // Handle image upload via AJAX
    function handleImageUpload(file, rowIndex, isMain = false) {
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn tệp hình ảnh');
            return;
        }
        var formData = new FormData();
        formData.append('profile_boss_photo', file);
        formData.append(csrfName, csrfHash);

        $.ajax({
            url: '<?php echo site_url('admin/invoices_supplier/image'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    var imageLink = response.image_url; // Changed from base64data to image_url
                    if (isMain) {
                        // Update .main inputs
                        $('.main input[name="link_image"]').val(imageLink);
                        $('.image_file_preview').css('display', 'block');
                        
                        $('.main #imagePreview').html('<img src="' + imageLink + '" style="width:100%;max-height:500px;">');
                    } else {
                        // Update table row inputs
                        var imageInput = document.getElementById('link_image' + rowIndex);
                        if (imageInput) imageInput.value = imageLink;
                        var imagePreview = document.getElementById('imagePreview' + rowIndex);
                        if (imagePreview) {
                            imagePreview.innerHTML = '<img src="' + imageLink + '" alt="" height="100" width="100">';
                        }
                    }
                    // Update modal preview
                    var imageSave = document.getElementById('image_save');
                    if (imageSave) {
                        imageSave.innerHTML = '<img src="' + imageLink + '" style="width:100%;max-height:500px;">';
                    }
                    // Style drag-area labels
                    var labels = document.querySelectorAll('.drag-area label');
                    labels.forEach(function(label) {
                        label.style.color = '#fff';
                        label.style.fontSize = '14px';
                    });
                    // Show modal
                    var imagesaveModal = document.getElementById('modal-image');
                    if (imagesaveModal) showModal(imagesaveModal);
                    // Update CSRF hash
                    if (response.csrfHash) {
                        csrfHash = response.csrfHash;
                    }
                    // Re-validate field
                    var validator = $('#invoice-form').data('validator');
                    if (validator) {
                        if (isMain) {
                            validator.element('.main input[name="link_image"]');
                        } else {
                            validator.element('#link_image' + rowIndex);
                        }
                    }
                } else {
                    alert(response.error || 'Lỗi khi upload hình ảnh');
                }
            },
            error: function(xhr) {
                console.error('Upload error:', xhr);
                alert('Lỗi khi upload hình ảnh: ' + (xhr.statusText || 'Unknown error'));
            }
        });
    }

    // File input change event for table rows
    $(document).on('change', '.imageUpload', function(e) {
        var rowIndex = this.id.replace('imageUpload', '');
        var files = e.target.files;
        if (files && files.length > 0) {
            handleImageUpload(files[0], rowIndex);
        }
    });

    // Drag-and-drop support for table rows
    $(document).on('dragover', '.ajax-upload-dragdrop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('backgroundColor', '#e0e0e0');
    });
    $(document).on('dragleave', '.ajax-upload-dragdrop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('backgroundColor', '');
    });
    $(document).on('drop', '.ajax-upload-dragdrop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('backgroundColor', '');
        var rowIndex = $(this).closest('tr').find('.imageUpload').attr('id').replace('imageUpload', '');
        var files = e.originalEvent.dataTransfer.files;
        if (files && files.length > 0) {
            handleImageUpload(files[0], rowIndex);
            var imageUploadInput = document.getElementById('imageUpload' + rowIndex);
            if (imageUploadInput) imageUploadInput.files = files;
        }
    });

    // Cancel button
    document.querySelectorAll('.btn-light1').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var imagesaveModal = document.getElementById('modal-image');
            if (imagesaveModal) hideModal(imagesaveModal);
            document.querySelectorAll('.imageUpload').forEach(function(input) {
                input.value = '';
            });
        });
    });

    // Handle .main image upload
    $(document).on('change', '.main .imageUpload', function(e) {
        var files = e.target.files;
        if (files && files.length > 0) {
            handleImageUpload(files[0], null, true);
        }
    });

    // Initialize form validation
    if ($('#invoice-form').length) {
        $('#invoice-form').validate({
            ignore: [], // Validate hidden inputs
            rules: {
                'description': { required: true },
                'quantity': { required: true, number: true },
                'rate': { required: true, number: true },
                'link_image': { required: false } // Optional for .main
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    }
});
</script>