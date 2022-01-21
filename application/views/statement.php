<?= $this->load->view('includes/header', [], true); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1 class="display-6">Print Statement</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<div class="btn-group me-2">
			<!-- <button class="btn btn-outline-primary" id="" data-popup-view="add_receipt" data-no-btn="1" data-modal-size="swal-wide" data-id=""><i class="bi bi-cash"></i> Add</button> -->
		</div>
	</div>
</div>
<form method="GET" action="<?= base_url('home/get_statement') ?>">
	<div class="p-2 col-md-6">
		<label class="mb-1">Customer</label>
		<div class="select2-input mb-2">
			<?= form_dropdown(
				['name' => 'customer', 'class' => 'form-control select-widget', 'id' => 'select-towards'],
				$toward_options,
				$this->input->get('towards'),
			) ?>
		</div>
	</div>
	<div class="p-2 col-md-6">
		<label class="mb-1">From date | To date</label>
		<div class="input-group">
			<input type="date" class="form-control date-widget" placeholder="From Date" name="from_date" id="input-from">
			<input type="date" class="form-control date-widget" placeholder="To Date" name="to_date" id="input-to">
		</div>
	</div>
	<div class="p-2 col-md-6">
		<button class="btn btn-primary" type="submit">Print</button>
	</div>
</form>

<?= $this->load->view('includes/footer', [], true); ?>