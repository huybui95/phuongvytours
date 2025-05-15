<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div id="vueApp">
            <div class="row">
                <div class="col-md-12">
                    <?php echo form_open(admin_url('reminder_settings')); ?>
                    <div class="panel_s">
                        <div class="panel-body">
                            <h4 class="no-margin">Cài đặt lịch nhắc tự động</h4>
                            <hr />

                            <div class="form-group">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="enable_birthday" name="enable_birthday" value="1" <?php echo $settings['enable_birthday'] ? 'checked' : ''; ?>>
                                    <label for="enable_birthday">Bật nhắc sinh nhật khách hàng</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" id="enable_tour_reminder" name="enable_tour_reminder" value="1" <?php echo $settings['enable_tour_reminder'] ? 'checked' : ''; ?>>
                                    <label for="enable_tour_reminder">Bật nhắc tour sắp diễn ra</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tour_reminder_days">Số ngày trước khi tour diễn ra để nhắc:</label>
                                <input type="number" name="tour_reminder_days" id="tour_reminder_days" class="form-control" value="<?php echo html_escape($settings['tour_reminder_days']); ?>" min="1">
                            </div>

                            <div class="btn-bottom-toolbar text-right">
                            <button type="submit" class="btn btn-primary">
                                <?php echo _l('submit'); ?>
                            </button>
                        </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

</body>

</html>