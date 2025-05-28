<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo isset($supplier) ? 'Cập nhật nhà cung cấp' : 'Thêm nhà cung cấp'; ?>
                </h4>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open_multipart($this->uri->uri_string(), ['id' => 'supplier-form']); ?>

                        <?php echo render_input('name', 'supplier_name', isset($supplier) ? $supplier->name : ''); ?>

                        <?php echo render_input('contact_person', 'supplier_contact_person', isset($supplier) ? $supplier->contact_person : ''); ?>

                        <div class="form-group select-placeholder">
                            <label for="typesupplier_id" class="control-label"><?php echo _l('supplier_group'); ?></label>
                            <select name="typesupplier_id" class="form-control selectpicker" data-none-selected-text="Chọn nhóm" required>
                                <?php foreach ($ds_typesuppliers as $type): ?>
                                    <option value="<?php echo $type['id']; ?>" <?php echo (isset($supplier) && $supplier->typesupplier_id == $type['id']) ? 'selected' : ''; ?>>
                                        <?php echo $type['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php echo render_input('address', 'supplier_address', isset($supplier) ? $supplier->address : ''); ?>
                        <?php echo render_input('city', 'supplier_city', isset($supplier) ? $supplier->city : ''); ?>
                        <?php echo render_input('phone', 'supplier_phone', isset($supplier) ? $supplier->phone : ''); ?>
                        <?php echo render_input('position', 'supplier_position', isset($supplier) ? $supplier->position : ''); ?>
                        <?php echo render_input('website', 'supplier_website', isset($supplier) ? $supplier->website : ''); ?>

                        <div class="form-group">
                            <label for="link_image" class="link_image"><?php echo _l('supplier_link_image'); ?></label><br>
                            <?php if (!empty($supplier) && !empty($supplier->link_image)): ?>
                                <img src="<?= base_url('/uploads/supplier_image/' . $supplier->id . '/' . $supplier->link_image); ?>" width="50%" alt="Supplier Image"><br>
                            <?php endif; ?>

                            <input type="file" name="link_image" class="form-control " id="link_image" />
                        </div>

                        <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-primary">
                                <?php echo _l('submit'); ?>
                            </button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

<script>
$(document).ready(function () {
    $('.selectpicker').selectpicker();
});

$(function () {
    appValidateForm($('#supplier-form'), {
        name: 'required',
        contact_person: 'required',
        typesupplier_id: 'required',
        phone: 'required'
    });
});
</script>
</body>
</html>
