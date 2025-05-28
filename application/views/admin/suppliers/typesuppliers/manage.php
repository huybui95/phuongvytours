<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                   <h3> <?php echo _l('groups_supplier'); ?></h3> 
                    <a href="#" onclick="new_type_suppliers(); return false;" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                       <?php echo _l('new_groups_supplier'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <table class="table dt-table table-electricitywater" data-order-col="0" data-order-type="asc">
                            <thead>
                                <tr>
                                    <th class="th-type"><?php echo _l('name_group_supplier'); ?></th>
                                    <th class="th-electricitywater-number-ktwater"><?php echo _l('acs_settings'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($ds_typesuppliers as $key => $ds_typesuppliers) {
                                    ?>
                                    <tr>
                                    <td><?php echo $ds_typesuppliers['name'];?></td>
                                    <td><a href="#" class="btn btn-default btn-icon" data-name=""
                                            onclick="edit_typesupplier(<?php echo $ds_typesuppliers['id']?>); return false;">
                                            <i class="fa-regular fa-pen-to-square"></i></a>
                                            <a href="<?php echo site_url('admin/suppliers/delete_typesuppliers/' . $ds_typesuppliers['id'])?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>
                                    </td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/suppliers/typesuppliers/suppliers'); ?>
<?php init_tail(); ?>
</body>

</html>