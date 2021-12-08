<?= $this->load->view('includes/header', [], true); ?>
<form action="<?= base_url('home/bill') ?>" method="POST">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1 class="display-6">Glassia Invoice Form</h1>
		<div class="btn-toolbar mb-2 mb-md-0">
			<div class="btn-group me-2">
				<a href="<?= base_url('home/form') ?>" type="button" class="btn btn-outline-primary"><i class="bi bi-plus-lg"></i> New</a>
			</div>
		</div>
	</div>
	<!-- <pre><?= json_encode($invoice, JSON_PRETTY_PRINT) ?></pre> -->
	<div class="mb-3 row">
		<label class="col-sm-2 col-form-label">Invoice Type</label>
		<div class="col-sm-10">
			<input type="radio" class="btn-check" name="invoice_type" id="type_tax" autocomplete="off" value="t" required <?= ($invoice['invoice_type'] ?? '') == 't' ? 'checked' : '' ?>>
			<label class="btn btn-outline-primary" for="type_tax">Tax Invoice</label>

			<input type="radio" class="btn-check" name="invoice_type" id="type_proforma" autocomplete="off" value="i" required <?= ($invoice['invoice_type'] ?? '') == 'i' ? 'checked' : '' ?>>
			<label class="btn btn-outline-primary" for="type_proforma">Proforma Invoice</label>
		</div>
	</div>
	<div class="mb-3 row">
		<label for="bill_no" class="col-sm-2 col-form-label">Bill No</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="bill_no" name="bill_no" value="<?= $invoice['bill_no'] ?? '' ?>">
		</div>
	</div>
	<div class="mb-3 row">
		<label for="date" class="col-sm-2 col-form-label">Date</label>
		<div class="col-sm-10">
			<input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d', strtotime($invoice['date'] ?? date('Y-m-d'))) ?>">
		</div>
	</div>
	<div class="mb-3 row">
		<label for="time" class="col-sm-2 col-form-label">Time</label>
		<div class="col-sm-10">
			<input type="time" class="form-control" id="time" name="time" value="<?= $invoice['time'] ?? '' ?>">
		</div>
	</div>
	<div class="mb-3 row">
		<label for="to" class="col-sm-2 col-form-label">To</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="to" name="to" value="<?= $invoice['towards'] ?? '' ?>">
		</div>
	</div>
	<div class="mb-3 row">
		<label for="buyer_tin" class="col-sm-2 col-form-label">Buyer's TIN</label>
		<div class="col-sm-10">
			<input type="text" name="buyer_tin" class="form-control" id="buyer_tin" value="<?= $invoice['buyer_tin'] ?? '' ?>">
		</div>
	</div>
	<div class="mb-3 row">
		<label for="address" class="col-sm-2 col-form-label">Address</label>
		<div class="col-sm-10">
			<textarea name="address" id="address" rows="3" class="form-control auto-grow"><?= $invoice['address'] ?? '' ?></textarea>
		</div>
	</div>
	<div class="mb-3 row">
		<label for="worksite" class="col-sm-2 col-form-label">Delivery To</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="worksite" name="worksite" value="<?= $invoice['worksite'] ?? '' ?>">
		</div>
	</div>
	<div class="mb-3">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th style="width: 50%;">Particulars</th>
					<th>Qty</th>
					<th>SFT</th>
					<th>Rate</th>
					<th></th>
				</tr>
			</thead>
			<tbody class="particulars-rows">
				<?php
				$pi = 0;
				do {
				?>
					<tr>
						<td>
							<textarea name="particulars_items[]" rows="1" class="form-control auto-grow" required><?= $particulars[$pi]['name'] ?? '' ?></textarea>
						</td>
						<td>
							<input type="text" class="form-control numeric" data-currency="" name="particulars_qty[]" required value="<?= $particulars[$pi]['qty'] ?? '' ?>">
						</td>
						<td>
							<input type="text" class="form-control numeric" data-currency="" name="particulars_price[]" required value="<?= $particulars[$pi]['sft'] ?? '' ?>">
						</td>
						<td>
							<input type="text" class="form-control numeric" data-currency="" name="particulars_rate[]" required value="<?= $particulars[$pi]['rate'] ?? '' ?>">
						</td>
						<td>
							<button class="btn btn-outline-danger remove-row-btn" type="button"><i class="bi bi-x-lg"></i></button>
						</td>
					</tr>
				<?php
					$pi += 1;
				} while ($particulars[$pi] ?? false);
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="5">
						<button type="button" class="btn btn-outline-primary add-row-btn"><i class="bi bi-plus-lg"></i> Add Item</button>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div class="mb-3 row">
		<label for="vehicle" class="col-sm-2 col-form-label">Vehicle No</label>
		<div class="col-sm-10">
			<input type="text" name="vehicle" class="form-control" id="vehicle" value="<?= $invoice['vehicle'] ?? '' ?>">
		</div>
	</div>
	<div class="mb-3 row">
		<label class="col-sm-2 col-form-label">Tax Type</label>
		<div class="col-sm-10">
			<input type="radio" class="btn-check" name="tax_type" id="type_c" autocomplete="off" value="c" required <?= ($invoice['tax_type'] ?? '') == 'c' ? 'checked' : '' ?>>
			<label class="btn btn-outline-primary" for="type_c">CGST+SGST</label>

			<input type="radio" class="btn-check" name="tax_type" id="type_i" autocomplete="off" value="i" required <?= ($invoice['tax_type'] ?? '') == 'i' ? 'checked' : '' ?>>
			<label class="btn btn-outline-primary" for="type_i">IGST</label>
		</div>
	</div>
	<div class="text-center">
		<button class="btn btn-success btn-lg px-4"><i class="bi bi-save"></i> Save</button>
	</div>
</form>
<script>
	function auto_grow(element) {
		element.style.height = "auto";
		element.style.height = (element.scrollHeight + 2) + "px";
	}

	$(function() {
		$(document).on('click', '.add-row-btn', function() {
			var row = $('.particulars-rows tr:last-child').clone();
			$('.form-control', row).val('');
			$('.particulars-rows').append(row);
			$('.form-control:first-child', row)[0].focus();
		});
		$(document).on('click', '.remove-row-btn', function() {
			var rows = $('.particulars-rows tr').length;
			if (rows > 1) {
				$(this).parents('.particulars-rows tr').remove();
			}
		});
		$(document).on('input', '.auto-grow', function() {
			auto_grow(this);
		});


		$("body").on("input", ".numeric", function(e) {
			var currencyType = this.hasAttribute("data-currency");
			var input = e.target.value;

			if (currencyType) {
				input = input.replace(/[^0-9.]/gi, '')
				var ex = /^[0-9]+\.?[0-9]{0,2}$/;
				if (ex.test(input) == false) {
					input = input.substring(0, input.length - 1);
				}
			} else {
				input = input.replace(/\D/g, "");
			}

			e.target.value = input;
		});

		$("body").on("input", ".alphanumeric", function(e) {
			var input = e.target.value;

			input = input.replace(/[^0-9a-z]/gi, '')

			e.target.value = input;
		});

		$("body").on("input", ".alphabetic", function(e) {
			var input = e.target.value;

			input = input.replace(/[^a-z]/gi, '')

			e.target.value = input;
		});
	});
</script>
<?= $this->load->view('includes/footer', [], true); ?>