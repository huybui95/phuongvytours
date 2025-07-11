<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div id="vueApp">
			<div class="row">
				<div class="col-md-12 tw-mb-3 md:tw-mb-6">
					<div class="md:tw-flex md:tw-items-center">
						<div class="tw-grow">
							<h4 class="tw-my-0 tw-font-bold tw-text-xl">
								<?= _l('invoices_supplier'); ?>
							</h4>
						</div>
					</div>
				</div>
				<?php $this->load->view('admin/invoices_supplier/list_template'); ?>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<div id="modal-wrapper"></div>
<script>
	var hidden_columns = [2, 6, 7, 8];
</script>
<?php init_tail(); ?>
<script>
	$(function() {
		init_invoice();
	});
</script>
</body>

</html>