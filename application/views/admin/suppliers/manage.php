<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-my-0 tw-font-bold tw-text-xl tw-mb-2"><?php echo _l('manager_supplier'); ?></h4>
                <div class="_buttons">
                    <?php if (has_permission('suppliers', '', 'create')) { ?>
                    <a href="<?php echo admin_url('suppliers/supplier'); ?>"
                        class="btn btn-primary pull-left display-block tw-mb-2 sm:tw-mb-4">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_supplier'); ?>
                    </a>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>

                <div class="panel_s tw-mt-2 sm:tw-mt-4">
                    <?php echo form_hidden('custom_view'); ?>
                    <div class="panel-body">
                        <div class="panel-table-full">
                            <?php $this->load->view('admin/suppliers/table_html'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>