<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12 offset-md-2">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-font-semibold tw-text-lg"><?= $title ?></h4>
            <hr class="hr-panel-heading" />

            <?= form_open(admin_url('tour_templates/template/' . ($template->id ?? ''))) ?>

              <?= render_input('name', 'Tên Template', $template->name ?? '') ?>
              <?= render_input('view_file', 'Tên file view (nếu dùng)', $template->view_file ?? '') ?>
              <?= render_textarea('html_content', 'Nội dung template (HTML)', $template->html_content ?? '', ['class' => 'tinymce']) ?>

              <div class="checkbox checkbox-primary">
                <input type="checkbox" name="active" id="active" value="1" <?= isset($template) && $template->active ? 'checked' : '' ?>>
                <label for="active">Kích hoạt</label>
              </div>

              <div class="text-right mt-4">
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="<?= admin_url('tour_templates') ?>" class="btn btn-default">Huỷ</a>
              </div>

            <?= form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
