<form class="text-start fs-6" action="<?= base_url('home/submit_receipt') ?>" method="POST">
	<div class="form-group row mb-3">
		<div class="col-md-4"><label for="receipt-bill_no" class="control-label">Bill No</label></div>
		<div class="col-md-8"><input type="number" class="form-control numeric" id="receipt-bill_no" readonly name=bill_no value="<?= $bill_no ?>"></div>
	</div>
	<div class="form-group row mb-3">
		<div class="col-md-4"><label for="receipt-towards" class="control-label">Towards</label></div>
		<div class="col-md-8"><input type="text" class="form-control" id="receipt-towards" name=towards readonly value="<?= $towards ?>"></div>
	</div>
	<div class="form-group row mb-3">
		<div class="col-md-4"><label for="receipt-date" class="control-label">Received Date</label></div>
		<div class="col-md-8"><input type="date" class="form-control" id="receipt-date" name=date value="<?= date('Y-m-d', strtotime($date ?? date('Y-m-d'))) ?>"></div>
	</div>
	<div class="form-group row mb-3">
		<div class="col-md-4"><label for="receipt-bill_amt" class="control-label">Bill Amount</label></div>
		<div class="col-md-8"><input type="number" class="form-control numeric" data-currency id="receipt-bill_amt" name=bill_amt readonly value="<?= $invoice_total ?>"></div>
	</div>
	<div class="form-group row mb-3">
		<div class="col-md-4"><label for="receipt-received_amt" class="control-label">Received Amount</label></div>
		<div class="col-md-8"><input type="number" class="form-control numeric" data-currency id="receipt-received_amt" name=received_amt value="" max="<?= $invoice_total ?>" required></div>
	</div>
	<div class="form-group row mb-3">
		<div class="col-md-4"><label for="receipt-pending_amt" class="control-label">Pending Amount</label></div>
		<div class="col-md-8"><input type="number" class="form-control numeric" data-currency id="receipt-pending_amt" name=pending_amt readonly value=""></div>
	</div>

	<div class="text-center mt-4">
		<button type="submit" class="swal2-confirm swal2-styled">Save</button>
		<button type="button" class="swal2-cancel swal2-styled" aria-label="" style="display: inline-block;" onclick="swal.close()">Close</button>
	</div>
</form>