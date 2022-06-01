<form class="text-start fs-6" action="<?= base_url('home/submit_payment') ?>" method="POST">
	<div class="form-group row mb-3">
		<div class="col-md-4"><label for="receipt-towards" class="control-label">Towards</label></div>
		<div class="col-md-8"><?= form_dropdown('customer', $toward_options, [], ['class' => "form-control select-widget text-left", 'id' => "receipt-towards"]) ?></div>
	</div>
	<div class="form-group row mb-3">
		<div class="col-md-4"><label for="receipt-date" class="control-label">Received Date</label></div>
		<div class="col-md-8"><input type="date" class="form-control" id="receipt-date" name=date value="<?= date('Y-m-d') ?>"></div>
	</div>
	<div class="form-group row mb-3">
		<div class="col-md-4"><label for="receipt-received_amt" class="control-label">Received Amount</label></div>
		<div class="col-md-8">
			<input type="number" class="form-control numeric" data-currency id="receipt-received_amt" name=received_amt value="" required min=1>
		</div>
	</div>
	<div class="text-center mt-4">
		<button type="submit" class="swal2-confirm swal2-styled">Save</button>
		<button type="button" class="swal2-cancel swal2-styled" aria-label="" style="display: inline-block;" onclick="swal.close()">Close</button>
	</div>
</form>