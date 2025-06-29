<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <h4 class="tw-font-semibold tw-text-lg tw-text-neutral-700">
          <?= $title ?>
          <a href="<?= admin_url('tour_templates/template') ?>" class="btn btn-primary pull-right">Thêm mới</a>
        </h4>
        <hr class="hr-panel-heading" />
        <div class="panel-body">
          <?= render_datatable([
            'Tên template',
            'File View',
            'Kích hoạt',
            'Thao tác'
          ], 'tour_templates') ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  initDataTable('.table-tour_templates', admin_url + 'tour_templates/table', [1], [1]);
</script>
