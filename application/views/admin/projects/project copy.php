<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?= form_open($this->uri->uri_string(), ['id' => 'project_form']); ?>

        <div class="tw-mx-auto">
            <h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700">
                <?= e($title); ?>
            </h4>
            <div class="panel_s">
                <div class="panel-body">
                    <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tab_project" aria-controls="tab_project" role="tab" data-toggle="tab">
                                        <?= _l('Thông tin cơ bản'); ?>
                                    </a>
                                </li>
                                <!-- <li role="presentation">
                                    <a href="#tab_settings" aria-controls="tab_settings" role="tab" data-toggle="tab">
                                        <?= _l('project_settings'); ?>
                                    </a>
                                </li> -->
                                <li role="presentation" >
                                    <a href="#tab_expense" aria-controls="tab_expense" role="tab" data-toggle="tab">
                                        <?= _l('Quản lý chi phí'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content tw-mt-3">
                        <div role="tabpanel" class="tab-pane active" id="tab_project">


                            <?php
                        $disable_type_edit = '';
if (isset($project)) {
    if ($project->billing_type != 1) {
        if (total_rows(db_prefix() . 'tasks', ['rel_id' => $project->id, 'rel_type' => 'project', 'billable' => 1, 'billed' => 1]) > 0) {
            $disable_type_edit = 'disabled';
        }
    }
}
?>
<div class="row">
                                <div class="col-md-6">
                            <?php $value = (isset($project) ? $project->name : ''); ?>
                            <?= render_input('name', 'project_name', $value); ?>
</div>
<div class="col-md-6">
                            <div class="form-group select-placeholder">
                                <label for="clientid"
                                    class="control-label"><?= _l('project_customer'); ?></label>
                                <select id="clientid" name="clientid" data-live-search="true" data-width="100%"
                                    class="ajax-search"
                                    data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                                    <?php $selected = (isset($project) ? $project->clientid : '');
if ($selected == '') {
    $selected = ($customer_id ?? '');
}
if ($selected != '') {
    $rel_data = get_relation_data('customer', $selected);
    $rel_val  = get_relation_values($rel_data, 'customer');
    echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
} ?>
                                </select>
                            </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                <?php
                                    $selected = [];
                                    if (isset($project_members)) {
                                        foreach ($project_members as $member) {
                                            array_push($selected, $member['staff_id']);
                                        }
                                    } else {
                                        array_push($selected, get_staff_user_id());
                                    }
                                    echo render_select('project_members[]', $staff, ['staffid', ['firstname', 'lastname']], 'project_members', $selected, ['multiple' => true, 'data-actions-box' => true], [], '', '', false);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group select-placeholder">
                                        <label
                                            for="status"><?= _l('project_status'); ?></label>
                                        <div class="clearfix"></div>
                                        <select name="status" id="status" class="selectpicker" data-width="100%"
                                            data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>">
                                            <?php foreach ($statuses as $status) { ?>
                                            <option
                                                value="<?= e($status['id']); ?>"
                                                <?php if (! isset($project) && $status['id'] == 2 || (isset($project) && $project->status == $status['id'])) {
                                                    echo 'selected';
                                                } ?>><?= e($status['name']); ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php $value = (isset($project) ? _d($project->start_date) : _d(date('Y-m-d'))); ?>
                                    <?= render_date_input('start_date', 'project_start_date', $value); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php $value = (isset($project) ? _d($project->deadline) : ''); ?>
                                    <?= render_date_input('deadline', 'project_deadline', $value); ?>
                                </div>
                            </div>
                            <?php if (isset($project) && $project->date_finished != null && $project->status == 4) { ?>
                            <?= render_datetime_input('date_finished', 'project_completed_date', _dt($project->date_finished)); ?>
                            <?php } ?>
                            <?php $rel_id_custom_field = (isset($project) ? $project->id : false); ?>
                            <?= render_custom_fields('projects', $rel_id_custom_field); ?>
                            <p class="bold">
                                <?= _l('project_description'); ?>
                            </p>
                            <?php $contents = '';
if (isset($project)) {
    $contents = $project->description;
} ?>
                            <?= render_textarea('description', '', $contents, [], [], '', 'tinymce'); ?>

                            <?php if (isset($estimate)) {?>
                            <hr class="hr-panel-separator" />
                            <h5 class="font-medium">
                                <?= _l('estimate_items_convert_to_tasks') ?>
                            </h5>
                            <input type="hidden" name="estimate_id"
                                value="<?= $estimate->id ?>">
                            <div class="row">
                                <?php foreach ($estimate->items as $item) { ?>
                                <div class="col-md-8 border-right">
                                    <div class="checkbox mbot15">
                                        <input type="checkbox" name="items[]"
                                            value="<?= $item['id'] ?>"
                                            checked
                                            id="item-<?= $item['id'] ?>">
                                        <label
                                            for="item-<?= $item['id'] ?>">
                                            <h5 class="no-mbot no-mtop text-uppercase">
                                                <?= $item['description'] ?>
                                            </h5>
                                            <span
                                                class="text-muted"><?= $item['long_description'] ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div data-toggle="tooltip"
                                        title="<?= _l('task_single_assignees_select_title'); ?>">
                                        <?= render_select('items_assignee[]', $staff, ['staffid', ['firstname', 'lastname']], '', get_staff_user_id(), ['data-actions-box' => true], [], '', '', false); ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                            <hr class="hr-panel-separator" />

                            <?php if (is_email_template_active('assigned-to-project')) { ?>
                            <!-- <div class="checkbox checkbox-primary tw-mb-0">
                                <input type="checkbox" name="send_created_email" id="send_created_email">
                                <label
                                    for="send_created_email"><?= _l('project_send_created_email'); ?></label>
                            </div> -->
                            <?php } ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tab_settings">
                            <div id="project-settings-area">
                                <div class="form-group select-placeholder">
                                    <label for="contact_notification" class="control-label">
                                        <span class="text-danger">*</span>
                                        <?= _l('projects_send_contact_notification'); ?>
                                    </label>
                                    <select name="contact_notification" id="contact_notification"
                                        class="form-control selectpicker"
                                        data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>"
                                        required>
                                        <?php
                    $options = [
                        ['id' => 1, 'name' => _l('project_send_all_contacts_with_notifications_enabled')],
                        ['id' => 2, 'name' => _l('project_send_specific_contacts_with_notification')],
                        ['id' => 0, 'name' => _l('project_do_not_send_contacts_notifications')],
                    ];

foreach ($options as $option) { ?>
                                        <option
                                            value="<?= e($option['id']); ?>"
                                            <?php if ((isset($project) && $project->contact_notification == $option['id'])) {
                                                echo ' selected';
                                            } ?>><?= e($option['name']); ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!-- hide class -->
                                <div class="form-group select-placeholder <?= (isset($project) && $project->contact_notification == 2) ? '' : 'hide' ?>"
                                    id="notify_contacts_wrapper">
                                    <label for="notify_contacts" class="control-label"><span
                                            class="text-danger">*</span>
                                        <?= _l('project_contacts_to_notify') ?></label>
                                    <select name="notify_contacts[]" data-id="notify_contacts" id="notify_contacts"
                                        class="ajax-search" data-width="100%" data-live-search="true"
                                        data-none-selected-text="<?= _l('dropdown_non_selected_tex'); ?>"
                                        multiple>
                                        <?php
                                            $notify_contact_ids = isset($project) ? unserialize($project->notify_contacts) : [];

foreach ($notify_contact_ids as $contact_id) {
    $rel_data = get_relation_data('contact', $contact_id);
    $rel_val  = get_relation_values($rel_data, 'contact');
    echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
}
?>
                                    </select>
                                </div>
                                <?php foreach ($settings as $setting) {
                                    $checked = ' checked';
                                    if (isset($project)) {
                                        if ($project->settings->{$setting} == 0) {
                                            $checked = '';
                                        }
                                    } else {
                                        foreach ($last_project_settings as $last_setting) {
                                            if ($setting == $last_setting['name']) {
                                                // hide_tasks_on_main_tasks_table is not applied on most used settings to prevent confusions
                                                if ($last_setting['value'] == 0 || $last_setting['name'] == 'hide_tasks_on_main_tasks_table') {
                                                    $checked = '';
                                                }
                                            }
                                        }
                                        if (count($last_project_settings) == 0 && $setting == 'hide_tasks_on_main_tasks_table') {
                                            $checked = '';
                                        }
                                    } ?>
                                <?php if ($setting != 'available_features') { ?>
                                <div class="checkbox">
                                    <input type="checkbox"
                                        name="settings[<?= e($setting); ?>]"
                                        <?= e($checked); ?>
                                    id="<?= e($setting); ?>">
                                    <label for="<?= e($setting); ?>">
                                        <?php if ($setting == 'hide_tasks_on_main_tasks_table') { ?>
                                        <?= _l('hide_tasks_on_main_tasks_table'); ?>
                                        <?php } else { ?>
                                        <?= e(_l('project_allow_client_to', _l('project_setting_' . $setting))); ?>
                                        <?php } ?>
                                    </label>
                                </div>
                                <?php } else { ?>
                                <div class="form-group mtop15 select-placeholder project-available-features">
                                    <label
                                        for="available_features"><?= _l('visible_tabs'); ?></label>
                                    <select
                                        name="settings[<?= e($setting); ?>][]"
                                        id="<?= e($setting); ?>"
                                        multiple="true" class="selectpicker" id="available_features" data-width="100%"
                                        data-actions-box="true" data-hide-disabled="true">
                                        <?php foreach (get_project_tabs_admin() as $tab) {
                                            $selected = '';
                                            if (isset($tab['collapse'])) { ?>
                                        <optgroup
                                            label="<?= e($tab['name']); ?>">
                                            <?php foreach ($tab['children'] as $tab_dropdown) {
                                                $selected = '';
                                                if (isset($project) && (
                                                    (isset($project->settings->available_features[$tab_dropdown['slug']])
                                                                && $project->settings->available_features[$tab_dropdown['slug']] == 1)
                                                            || ! isset($project->settings->available_features[$tab_dropdown['slug']])
                                                )) {
                                                    $selected = ' selected';
                                                } elseif (! isset($project) && count($last_project_settings) > 0) {
                                                    foreach ($last_project_settings as $last_project_setting) {
                                                        if ($last_project_setting['name'] == $setting) {
                                                            if (isset($last_project_setting['value'][$tab_dropdown['slug']])
                                                                    && $last_project_setting['value'][$tab_dropdown['slug']] == 1) {
                                                                $selected = ' selected';
                                                            }
                                                        }
                                                    }
                                                } elseif (! isset($project)) {
                                                    $selected = ' selected';
                                                } ?>
                                            <option
                                                value="<?= e($tab_dropdown['slug']); ?>"
                                                <?= e($selected); ?><?php if (isset($tab_dropdown['linked_to_customer_option']) && is_array($tab_dropdown['linked_to_customer_option']) && count($tab_dropdown['linked_to_customer_option']) > 0) { ?>
                                                data-linked-customer-option="<?= implode(',', $tab_dropdown['linked_to_customer_option']); ?>"
                                                <?php } ?>><?= e($tab_dropdown['name']); ?>
                                            </option>
                                            <?php
                                            } ?>
                                        </optgroup>
                                        <?php } else {
                                            if (isset($project) && (
                                                (isset($project->settings->available_features[$tab['slug']])
                             && $project->settings->available_features[$tab['slug']] == 1)
                            || ! isset($project->settings->available_features[$tab['slug']])
                                            )) {
                                                $selected = ' selected';
                                            } elseif (! isset($project) && count($last_project_settings) > 0) {
                                                foreach ($last_project_settings as $last_project_setting) {
                                                    if ($last_project_setting['name'] == $setting) {
                                                        if (isset($last_project_setting['value'][$tab['slug']])
                                    && $last_project_setting['value'][$tab['slug']] == 1) {
                                                            $selected = ' selected';
                                                        }
                                                    }
                                                }
                                            } elseif (! isset($project)) {
                                                $selected = ' selected';
                                            } ?>
                                        <option
                                            value="<?= e($tab['slug']); ?>"
                                            <?php if ($tab['slug'] == 'project_overview') {
                                                echo ' disabled selected';
                                            } ?>
                                            <?= e($selected); ?>
                                            <?php if (isset($tab['linked_to_customer_option']) && is_array($tab['linked_to_customer_option']) && count($tab['linked_to_customer_option']) > 0) { ?>
                                            data-linked-customer-option="<?= implode(',', $tab['linked_to_customer_option']); ?>"
                                            <?php } ?>>
                                            <?= e($tab['name']); ?>
                                        </option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                                <?php } ?>
                                <hr class="tw-my-3 -tw-mx-8" />
                                <?php } ?>
                            </div>
                        </div>
                        <style>
    .tab-pane { max-width: 1200px; margin: 0 auto; }
    #tab_expense .section { margin-bottom: 10px; background-color: #fff; padding: 10px; border-bottom: 1px solid #ddd; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
    #tab_expense .section-title { font-size: 1.2em; font-weight: bold; color: #333; }
    #tab_expense .dropdown { font-size: 1em; transition: transform 0.3s; }
    #tab_expense .content { display: none; padding: 10px 0; }
    #tab_expense .content.active { display: block; }
    #tab_expense .title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    #tab_expense .add-btn { background-color: #000; color: #fff; border: none; padding: 5px 10px; cursor: pointer; height: 30px; display: inline-flex; align-items: center; border-radius: 4px; }
    #tab_expense .table { width: 100%; border-collapse: collapse; }
    #tab_expense .table th, #tab_expense .table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    #tab_expense .table input, #tab_expense .table select { width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 4px; }
    #tab_expense .remove-btn { background: none; border: none; cursor: pointer; }
    #tab_expense .total { font-size: 1.2em; font-weight: bold; color: #1a0dab; text-align: right; margin-top: 20px; }
</style>

<div role="tabpanel" class="tab-pane" id="tab_expense"></div>

<script>
    const suppliers = <?php echo json_encode($ds_suppliers); ?>;

    const selectOptions = {
        loaixe: [
            { id: 1, label: 'Xe 45 chỗ' },
            { id: 2, label: 'Xe 29 chỗ' },
            { id: 3, label: 'Xe 16 chỗ' },
            { id: 4, label: 'Xe 7 chỗ' }
        ],
        tieuchuan: [
            { id: 4, label: '4 sao' },
            { id: 3, label: '3 sao' },
            { id: 2, label: '2 sao' },
            { id: 1, label: '1 sao' }
        ],
        buaan: [
            { id: 1, label: 'Trưa' },
            { id: 2, label: 'Chiều' },
            { id: 3, label: 'Tối' }
        ],
        loaiGuide: [
            { id: 1, label: 'HDV' },
            { id: 2, label: 'MC' }
        ]
    };

    const services = [
        { key: 'transport', label: 'Vận chuyển', columns: ['Loại xe', 'Số lượng', 'Đơn giá', 'Nhà xe'], supplierType: 6 },
        { key: 'hotel', label: 'Khách sạn', columns: ['Tiêu chuẩn', 'Số phòng', 'Đơn giá', 'Số đêm', 'Tên khách sạn'], supplierType: 2 },
        { key: 'restaurant', label: 'Nhà hàng', columns: ['Bữa ăn', 'Tên nhà hàng', 'Ngày ăn', 'Đơn giá', 'Số khách', 'Thành tiền'], supplierType: 4 },
        { key: 'ticket', label: 'Vé tham quan', columns: ['Tên điểm tham quan', 'Đơn giá', 'Số khách', 'Thành tiền'], supplierType: 3 },
        { key: 'guide', label: 'HDV/MC', columns: ['Loại', 'Đơn giá', 'Số lượng', 'Số ngày'] },
        { key: 'other', label: 'khác', columns: ['Nội dung', 'Số lượng', 'Đơn giá', 'Ghi chú'] },
    ];

    document.addEventListener("DOMContentLoaded", () => {
        const container = document.getElementById("tab_expense");
        services.forEach(service => container.innerHTML += renderSection(service));
        container.innerHTML += `<div class="total_dichvu" id="grandTotal" style=" text-align: right; color: #000; font-size: 15px; margin-top: 20px; font-weight: 700;">Tổng tiền tất cả: 0 VNĐ</div>`;
        services.forEach(service => addRow(`${service.key}Table`, service.key));
    });

    function renderSection(service) {
        const tableId = `${service.key}Table`;
        const contentId = `${service.key}Content`;
        const dropdownId = `${service.key}Dropdown`;
        return `
            <div class="section" onclick="toggleContent('${contentId}', '${dropdownId}')">
                <span class="section-title">${service.label}</span>
                <span class="dropdown" id="${dropdownId}">▼</span>
            </div>
            <div class="content" id="${contentId}">
                <div class="title-row">
                    <div class="title">Chi phí ${service.label}</div>
                    <button class="add-btn" onclick="addRow('${tableId}', '${service.key}')">Thêm</button>
                </div>
                <table class="table" id="${tableId}">
                    <thead>
                        <tr>
                            ${service.columns.map(col => `<th>${col}</th>`).join('')}
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        `;
    }

    function toggleContent(contentId, dropdownId) {
        const content = document.getElementById(contentId);
        const dropdown = document.getElementById(dropdownId);
        content.classList.toggle("active");
        dropdown.textContent = content.classList.contains("active") ? "▲" : "▼";
    }

    function addRow(tableId, serviceKey) {
        const service = services.find(s => s.key === serviceKey);
        const table = document.getElementById(tableId).querySelector('tbody');
        const rowIndex = table.rows.length;
        const baseName = `${serviceKey}[${rowIndex}]`;
        let inputs = '';

        if (serviceKey === 'transport') {
            const filtered = suppliers.filter(s => s.typesupplier_id == 6);
            inputs = `
                <td><select name="${baseName}[loaixe]">
                    ${selectOptions.loaixe.map(opt => `<option value="${opt.id}">${opt.label}</option>`).join('')}
                </select></td>
                <td><input type="number" name="${baseName}[soluong]" onchange="calculateRow(this)"></td>
                <td><input type="text" name="${baseName}[dongia]" onchange="calculateRow(this)"></td>
                <td><select name="${baseName}[nhaxe]">
                    ${filtered.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                </select></td>`;
        } else if (serviceKey === 'hotel') {
            const filtered = suppliers.filter(s => s.typesupplier_id == 2);
            inputs = `
                <td><select name="${baseName}[tieuchuan]">
                    ${selectOptions.tieuchuan.map(opt => `<option value="${opt.id}">${opt.label}</option>`).join('')}
                </select></td>
                <td><input type="number" name="${baseName}[sophong]" onchange="calculateRow(this)"></td>
                <td><input type="text" name="${baseName}[dongia]" onchange="calculateRow(this)"></td>
                <td><input type="number" name="${baseName}[sodem]" onchange="calculateRow(this)"></td>
                <td><select name="${baseName}[tenkhachsan]">
                    ${filtered.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                </select></td>`;
        } else if (serviceKey === 'restaurant') {
            const filtered = suppliers.filter(s => s.typesupplier_id == 4);
            inputs = `
                <td><select name="${baseName}[buaan]">
                    ${selectOptions.buaan.map(opt => `<option value="${opt.id}">${opt.label}</option>`).join('')}
                </select></td>
                <td><select name="${baseName}[tennhahang]">
                    ${filtered.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                </select></td>
                <td><input type="date" name="${baseName}[ngayan]" onchange="calculateRow(this)"></td>
                <td><input type="text" name="${baseName}[dongia]" onchange="calculateRow(this)"></td>
                <td><input type="number" name="${baseName}[sokhach]" onchange="calculateRow(this)"></td>
                <td><input type="text" name="${baseName}[thanhtien]" readonly></td>`;
        } else if (serviceKey === 'ticket') {
            const filtered = suppliers.filter(s => s.typesupplier_id == 3);
            inputs = `
                <td><select name="${baseName}[tendiemthamquan]">
                    ${filtered.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                </select></td>
                <td><input type="text" name="${baseName}[dongia]" onchange="calculateRow(this)"></td>
                <td><input type="number" name="${baseName}[sokhach]" onchange="calculateRow(this)"></td>
                <td><input type="text" name="${baseName}[thanhtien]" readonly></td>`;
        } else if (serviceKey === 'guide') {
            inputs = `
                <td><select name="${baseName}[loai]">
                    ${selectOptions.loaiGuide.map(opt => `<option value="${opt.id}">${opt.label}</option>`).join('')}
                </select></td>
                <td><input type="text" name="${baseName}[dongia]" onchange="calculateRow(this)"></td>
                <td><input type="number" name="${baseName}[soluong]" onchange="calculateRow(this)"></td>
                <td><input type="number" name="${baseName}[songay]" onchange="calculateRow(this)"></td>`;
        } else if (serviceKey === 'other') {
            inputs = `
                <td><input type="text" name="${baseName}[noidung]" onchange="calculateRow(this)"></td>
                <td><input type="number" name="${baseName}[soluong]" onchange="calculateRow(this)"></td>
                <td><input type="text" name="${baseName}[dongia]" onchange="calculateRow(this)"></td>
                <td><input type="text" name="${baseName}[ghichu]"></td>`;
        }

        const row = table.insertRow();
        row.innerHTML = `${inputs}<td><button class="remove-btn" onclick="removeRow(this)">🗑️</button></td>`;
    }

    function removeRow(btn) {
        const row = btn.closest("tr");
        const tbody = row.closest("tbody");
        const tableId = row.closest("table").id;
        const serviceKey = tableId.replace("Table", "");
        row.remove();
        const rows = tbody.querySelectorAll("tr");
        rows.forEach((row, index) => {
            row.querySelectorAll("input, select").forEach(input => {
                const field = input.name.match(/\[([^\]]+)\]$/)[1];
                input.name = `${serviceKey}[${index}][${field}]`;
            });
        });
        calculateGrandTotal();
    }

    function calculateRow(input) {
        const row = input.closest("tr");
        const inputs = row.querySelectorAll("input");
        let dongia = 0, soluong = 0, songay = 1, thanhtien = 0;
        const tableId = row.closest("table").id;

        switch (tableId) {
            case 'transportTable':
                dongia = parseInt(inputs[2].value.replace(/\D/g, '')) || 0;
                soluong = parseInt(inputs[1].value) || 0;
                thanhtien = soluong * dongia;
                break;
            case 'hotelTable':
                dongia = parseInt(inputs[2].value.replace(/\D/g, '')) || 0;
                soluong = parseInt(inputs[1].value) || 0;
                songay = parseInt(inputs[3].value) || 1;
                thanhtien = soluong * dongia * songay;
                break;
            case 'restaurantTable':
            case 'ticketTable':
                dongia = parseInt(inputs[3].value.replace(/\D/g, '')) || 0;
                soluong = parseInt(inputs[4].value) || 0;
                thanhtien = soluong * dongia;
                break;
            case 'guideTable':
                dongia = parseInt(inputs[1].value.replace(/\D/g, '')) || 0;
                soluong = parseInt(inputs[2].value) || 0;
                songay = parseInt(inputs[3].value) || 1;
                thanhtien = soluong * dongia * songay;
                break;
            case 'otherTable':
                dongia = parseInt(inputs[2].value.replace(/\D/g, '')) || 0;
                soluong = parseInt(inputs[1].value) || 0;
                thanhtien = soluong * dongia;
                break;
        }

        if (inputs[inputs.length - 1]) {
            inputs[inputs.length - 1].value = thanhtien.toLocaleString('vi-VN') + ' VNĐ';
        }
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        services.forEach(service => {
            const table = document.getElementById(`${service.key}Table`);
            if (!table) return;
            const rows = table.querySelectorAll("tbody tr");
            rows.forEach(row => {
                const inputs = row.querySelectorAll("input");
                const value = inputs[inputs.length - 1]?.value?.replace(/\D/g, '') || 0;
                grandTotal += parseInt(value);
            });
        });
        document.getElementById("grandTotal").textContent = `Tổng tiền tất cả: ${grandTotal.toLocaleString('vi-VN')} VNĐ`;
    }
</script>

                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button type="submit" data-form="#project_form" class="btn btn-primary" autocomplete="off"
                        data-loading-text="<?= _l('wait_text'); ?>">
                        <?= _l('submit'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>
<?php init_tail(); ?>
<script>
    <?php if (isset($project)) { ?>
    var original_project_status = '<?= e($project->status); ?>';
    <?php } ?>

    $(function() {

        $contacts_select = $('#notify_contacts'),
            $contacts_wrapper = $('#notify_contacts_wrapper'),
            $clientSelect = $('#clientid'),
            $contact_notification_select = $('#contact_notification');

        init_ajax_search('contacts', $contacts_select, {
            rel_id: $contacts_select.val(),
            type: 'contacts',
            extra: {
                client_id: function() {
                    return $clientSelect.val();
                }
            }
        });

        if ($clientSelect.val() == '') {
            $contacts_select.prop('disabled', true);
            $contacts_select.selectpicker('refresh');
        } else {
            $contacts_select.siblings().find('input[type="search"]').val(' ').trigger('keyup');
        }

        $clientSelect.on('changed.bs.select', function() {
            if ($clientSelect.selectpicker('val') == '') {
                $contacts_select.prop('disabled', true);
            } else {
                $contacts_select.siblings().find('input[type="search"]').val(' ').trigger('keyup');
                $contacts_select.prop('disabled', false);
            }
            deselect_ajax_search($contacts_select[0]);
            $contacts_select.find('option').remove();
            $contacts_select.selectpicker('refresh');
        });

        $contact_notification_select.on('changed.bs.select', function() {
            if ($contact_notification_select.selectpicker('val') == 2) {
                $contacts_select.siblings().find('input[type="search"]').val(' ').trigger('keyup');
                $contacts_wrapper.removeClass('hide');
            } else {
                $contacts_wrapper.addClass('hide');
                deselect_ajax_search($contacts_select[0]);
            }
        });

        $('select[name="billing_type"]').on('change', function() {
            var type = $(this).val();
            if (type == 1) {
                $('#project_cost').removeClass('hide');
                $('#project_rate_per_hour').addClass('hide');
            } else if (type == 2) {
                $('#project_cost').addClass('hide');
                $('#project_rate_per_hour').removeClass('hide');
            } else {
                $('#project_cost').addClass('hide');
                $('#project_rate_per_hour').addClass('hide');
            }
        });

        appValidateForm($('form'), {
            name: 'required',
            clientid: 'required',
            start_date: 'required',
            // billing_type: 'required',
            // 'notify_contacts[]': {
            //     required: {
            //         depends: function() {
            //             return !$contacts_wrapper.hasClass('hide');
            //         }
            //     }
            // },
        });

        $('select[name="status"]').on('change', function() {
            var status = $(this).val();
            var mark_all_tasks_completed = $('.mark_all_tasks_as_completed');
            var notify_project_members_status_change = $('.notify_project_members_status_change');
            mark_all_tasks_completed.removeClass('hide');
            if (typeof(original_project_status) != 'undefined') {
                if (original_project_status != status) {

                    mark_all_tasks_completed.removeClass('hide');
                    notify_project_members_status_change.removeClass('hide');

                    if (status == 4 || status == 5 || status == 3) {
                        $('.recurring-tasks-notice').removeClass('hide');
                        var notice =
                            "<?= _l('project_changing_status_recurring_tasks_notice'); ?>";
                        notice = notice.replace('{0}', $(this).find('option[value="' + status + '"]')
                            .text()
                            .trim());
                        $('.recurring-tasks-notice').html(notice);
                        $('.recurring-tasks-notice').append(
                            '<input type="hidden" name="cancel_recurring_tasks" value="true">');
                        mark_all_tasks_completed.find('input').prop('checked', true);
                    } else {
                        $('.recurring-tasks-notice').html('').addClass('hide');
                        mark_all_tasks_completed.find('input').prop('checked', false);
                    }
                } else {
                    mark_all_tasks_completed.addClass('hide');
                    mark_all_tasks_completed.find('input').prop('checked', false);
                    notify_project_members_status_change.addClass('hide');
                    $('.recurring-tasks-notice').html('').addClass('hide');
                }
            }

            if (status == 4) {
                $('.project_marked_as_finished').removeClass('hide');
            } else {
                $('.project_marked_as_finished').addClass('hide');
                $('.project_marked_as_finished').prop('checked', false);
            }
        });

        $('form').on('submit', function() {
            $('select[name="billing_type"]').prop('disabled', false);
            $('#available_features,#available_features option').prop('disabled', false);
            $('input[name="project_rate_per_hour"]').prop('disabled', false);
        });

        var progress_input = $('input[name="progress"]');
        var progress_from_tasks = $('#progress_from_tasks');
        var progress = progress_input.val();

        $('.project_progress_slider').slider({
            min: 0,
            max: 100,
            value: progress,
            disabled: progress_from_tasks.prop('checked'),
            slide: function(event, ui) {
                progress_input.val(ui.value);
                $('.label_progress').html(ui.value + '%');
            }
        });

        progress_from_tasks.on('change', function() {
            var _checked = $(this).prop('checked');
            $('.project_progress_slider').slider({
                disabled: _checked
            });
        });

        $('#project-settings-area input').on('change', function() {
            if ($(this).attr('id') == 'view_tasks' && $(this).prop('checked') == false) {
                $('#create_tasks').prop('checked', false).prop('disabled', true);
                $('#edit_tasks').prop('checked', false).prop('disabled', true);
                $('#view_task_comments').prop('checked', false).prop('disabled', true);
                $('#comment_on_tasks').prop('checked', false).prop('disabled', true);
                $('#view_task_attachments').prop('checked', false).prop('disabled', true);
                $('#view_task_checklist_items').prop('checked', false).prop('disabled', true);
                $('#upload_on_tasks').prop('checked', false).prop('disabled', true);
                $('#view_task_total_logged_time').prop('checked', false).prop('disabled', true);
            } else if ($(this).attr('id') == 'view_tasks' && $(this).prop('checked') == true) {
                $('#create_tasks').prop('disabled', false);
                $('#edit_tasks').prop('disabled', false);
                $('#view_task_comments').prop('disabled', false);
                $('#comment_on_tasks').prop('disabled', false);
                $('#view_task_attachments').prop('disabled', false);
                $('#view_task_checklist_items').prop('disabled', false);
                $('#upload_on_tasks').prop('disabled', false);
                $('#view_task_total_logged_time').prop('disabled', false);
            }
        });

        // Auto adjust customer permissions based on selected project visible tabs
        // Eq Project creator disable TASKS tab, then this function will auto turn off customer project option Allow customer to view tasks

        $('#available_features').on('change', function() {
            $("#available_features option").each(function() {
                if ($(this).data('linked-customer-option') && !$(this).is(':selected')) {
                    var opts = $(this).data('linked-customer-option').split(',');
                    for (var i = 0; i < opts.length; i++) {
                        var project_option = $('#' + opts[i]);
                        project_option.prop('checked', false);
                        if (opts[i] == 'view_tasks') {
                            project_option.trigger('change');
                        }
                    }
                }
            });
        });
        $("#view_tasks").trigger('change');
        <?php if (! isset($project)) { ?>
        $('#available_features').trigger('change');
        <?php } ?>
    });
</script>
</body>

</html>