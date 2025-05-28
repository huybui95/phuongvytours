<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="type_suppliers_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('suppliers/suppliers'), ['id' => 'typesuppliers-form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title"><?php echo _l('edit_groups_supplier'); ?></span>
                    <span class="add-title"><?php echo _l('new_groups_supplier'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo render_input('name', 'name_group_supplier'); ?>
                    </div>
                    <input type="hidden" name="id" id="id">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    window.addEventListener('load',function(){
        appValidateForm($('#type_suppliers_modal'),{name:'required'});
        $('#type_suppliers_modal').on('hidden.bs.modal', function(event) {
            // $('#additional').html('');
            $('#type_suppliers_modal input[name="name"]').val('');
            $('.add-title').removeClass('hide');
            $('.edit-title').removeClass('hide');
        });
    });
    
    function new_type_suppliers() {
    $('#type_suppliers_modal').modal('show');
    $('.edit-title').addClass('hide');
    $('#id').val('');  // Clear the hidden ID field
}
function edit_typesupplier(id) {
    console.log(id);
    $.ajax({
        url:'<?php echo site_url('admin/suppliers/get_typesuppliers/'); ?>' + id ,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#type_suppliers_modal').modal('show');
            $('.add-title').addClass('hide');
            $('.edit-title').removeClass('hide');

            $('#id').val(response.id);
            $('input[name="name"]').val(response.name);
        }
    });
}
</script>