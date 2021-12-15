<?= $this->load->view('includes/header', [], true); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1 class="display-6">Receipts</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<div class="btn-group me-2">
			<!-- <button class="btn btn-outline-primary" id="" data-popup-view="add_receipt" data-no-btn="1" data-modal-size="swal-wide" data-id=""><i class="bi bi-cash"></i> Add</button> -->
		</div>
	</div>
</div>
<div class="container-fluid mb-3">
	<div class="row">
		<div class="col-md-6">
			<div class="select2-input">
				<?= form_dropdown(
					['class' => 'form-control select-widget', 'id' => 'select-towards'],
					$toward_options,
					$this->input->get('towards'),
				) ?>
			</div>
		</div>
	</div>
</div>
<div class="table-responsive py-1">
	<table class="table table-striped" id="d-table" data-ajax-url="<?= base_url('ajaxtables/receipts') ?>">
		<thead>
			<tr>
				<th scope="col">Bill No</th>
				<th scope="col">Total Amount</th>
				<th scope="col">Date</th>
				<th scope="col">Received Date</th>
				<th scope="col">Received Amount</th>
				<th scope="col">Pending Amount</th>
				<th scope="col">Bill</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<script>
	function getFilter() {
		return {
			towards: $("#select-towards").val(),
		};
	}

	let queryParams = new URLSearchParams(window.location.search);
	$("#select-towards").on('change', function() {
		dtable.ajax.reload();
		queryParams.set('towards', $(this).val());
		history.replaceState(null, null, "?" + queryParams.toString());
	});

	$(document).on('input', "#receipt-received_amt", function() {
		var received = $(this).val();
		var billAmt = $("#receipt-bill_amt").val();
		var receivedValue = parseFloat(received);
		if (!received || !receivedValue) {
			$("#receipt-pending_amt").val('');
			return;
		}
		var billValue = parseFloat(billAmt);
		var pendingValue = billValue - receivedValue;
		if (pendingValue < 0) {
			Swal.showValidationMessage(`Maximum receivable amount: Rs.${billValue}`)
		} else {
			Swal.resetValidationMessage();
		}
		$("#receipt-pending_amt").val(pendingValue)
	});
</script>

<?= $this->load->view('includes/footer', [], true); ?>