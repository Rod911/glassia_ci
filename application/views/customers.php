<?= $this->load->view('includes/header', [], true); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1 class="display-6">Customers</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<div class="btn-group me-2">
			<a href="<?= base_url('home/form') ?>" type="button" class="btn btn-outline-primary"><i class="bi bi-plus-lg"></i> New</a>
		</div>
	</div>
</div>

<div class="table-responsive py-1">
	<table class="table table-striped" id="d-table" data-ajax-url="<?= base_url('ajaxtables/customers') ?>">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Invoice Count</th>
				<th scope="col">Total Amount</th>
				<th scope="col">Received Amount</th>
				<th scope="col">Pending Amount</th>
				<th scope="col">View</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<?= $this->load->view('includes/footer', [], true); ?>