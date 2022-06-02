<?= $this->load->view('includes/header', [], true); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1 class="display-6">Invoices</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<div class="btn-group me-2">
			<a href="<?= base_url('home/form') ?>" type="button" class="btn btn-outline-primary"><i class="bi bi-plus-lg"></i> New</a>
		</div>
	</div>
</div>

<div class="table-responsive py-1">
	<table class="table table-striped" id="d-table" data-ajax-url="<?= base_url('ajaxtables/invoices') ?>">
		<thead>
			<tr>
				<th scope="col">Bill No</th>
				<th scope="col">Towards</th>
				<th scope="col">Total Amount</th>
				<th scope="col">Date</th>
				<!-- <th scope="col">Receipt</th> -->
				<th scope="col">Edit</th>
				<th scope="col">Bill</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<script>
	$(document).on('input', "#receipt-received_amt", function() {
		var received = $(this).val();
		var pending = $(this).attr('max');
		if (received == '') {
			$("#receipt-pending_amt").val('')
			return;
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