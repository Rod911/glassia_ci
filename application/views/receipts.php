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
		<div class="col-md-5">
			<div class="select2-input mb-2">
				<?= form_dropdown(
					['class' => 'form-control select-widget', 'id' => 'select-towards'],
					$toward_options,
					$this->input->get('towards'),
				) ?>
			</div>
			<label class="mb-1">From date | To date</label>
			<div class="input-group">
				<input type="date" class="form-control date-widget" placeholder="From Date" name="from_date" id="input-from">
				<input type="date" class="form-control date-widget" placeholder="To Date" name="to_date" id="input-to">
			</div>
		</div>
		<div class="col-md-7">
			<div class="row">
				<div class="col-md-4">
					<p class="mb-0">Total Bill Amount</p>
					<p class="mb-0" id="total-bill-amount"></p>
				</div>
				<div class="col-md-4">
					<p class="mb-0">Total Received Amount</p>
					<p class="mb-0" id="total-received-amount"></p>
				</div>
				<div class="col-md-4">
					<p class="mb-0">Total Pending Amount</p>
					<p class="mb-0" id="total-pending-amount"></p>
				</div>
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
				<th scope="col">Receipt</th>
				<th scope="col">Bill</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<script>
	function getFilter() {
		var towards = $("#select-towards").val();
		var from = $("#input-from").val();
		var to = $("#input-to").val();
		return {
			towards: towards,
			from: from,
			to: to,
		};
	}

	let queryParams = new URLSearchParams(window.location.search);
	$("#select-towards, .date-widget").on('change', function() {
		dtable.ajax.reload();
		queryParams.set('towards', $("#select-towards").val());
		queryParams.set('from', $("#input-from").val());
		queryParams.set('to', $("#input-to").val());
		history.replaceState(null, null, "?" + queryParams.toString());
		updateTotals();
	});

	function updateTotals() {
		var towards = $("#select-towards").val();
		$.getJSON(
			BASEURL + 'ajax/get_customer_totals',
			getFilter(),
			function(res) {
				$("#total-bill-amount").text(res.total)
				$("#total-received-amount").text(res.received)
				$("#total-pending-amount").text(res.pending)
			}
		)
	}

	updateTotals();

	$(document).on('input', "#receipt-received_amt", function() {
		var received = $(this).val();
		var pending = $(this).attr('max');
		if (received == '') {
			received = 0;
		}
		var receivedValue = parseFloat(received);
		var pendingValue = parseFloat(pending);
		var balance = pendingValue - receivedValue;
		if (receivedValue > pendingValue) {
			Swal.showValidationMessage(`Maximum receivable amount: Rs.${pendingValue}`)
		} else {
			Swal.resetValidationMessage();
		}
		$("#receipt-pending_amt").val(balance)
	});
</script>

<?= $this->load->view('includes/footer', [], true); ?>