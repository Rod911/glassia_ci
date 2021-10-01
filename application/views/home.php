<?= $this->load->view('includes/header', [], true); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	<h1 class="display-6">Invoices</h1>
	<div class="btn-toolbar mb-2 mb-md-0">
		<div class="btn-group me-2">
			<a href="<?= base_url('home/form') ?>" type="button" class="btn btn-outline-primary"><i class="bi bi-plus-lg"></i> New</a>
		</div>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-striped" id="d-table" data-ajax-url="<?= base_url('ajaxtables/invoices') ?>">
		<thead>
			<tr>
				<th scope="col">Bill No</th>
				<th scope="col">Towards</th>
				<th scope="col">Total Amount</th>
				<th scope="col">Date</th>
				<th scope="col">Edit</th>
				<th scope="col">Bill</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<script src="<?= base_url('assets/plugins/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/dataTables.min.js') ?>"></script>
<!-- <script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script> -->
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<script>
	var dtable = $("#d-table").DataTable({
		bProcessing: true,
		bServerSide: true,
		ordering: false,
		sAjaxSource: $(this).attr('data-ajax-url'),
		bJQueryUI: true,
		sPaginationType: "full_numbers",
		iDisplayLength: 10,
		oLanguage: {
			sProcessing: "Loading...",
		},
		fnServerData: function(sSource, aoData, fnCallback) {
			var filter = null;
			if (typeof getFilter === "function") {
				filter = getFilter();
			}
			// console.log(aoData);
			var aoDataObj = {};
			aoData.forEach((aObj) => {
				aoDataObj[aObj.name] = aObj;
			});
			aoDataObj.columns.value.forEach((col, ci) => {
				aoDataObj.columns.value[ci].searchable = false;
				aoDataObj.columns.value[ci].orderable = false;
			});
			$.ajax({
				dataType: 'json',
				type: 'POST',
				url: $(this).attr('data-ajax-url'),
				data: {
					filter: filter,
					sEcho: '1',
					columns: aoDataObj.columns.value,
					iDisplayStart: aoDataObj.start.value,
					iDisplayLength: aoDataObj.length.value,
					search: aoDataObj.search.value,
					bRegex: 'false',
					iSortCol_0: '0',
					sSortDir_0: 'asc',
					iSortingCols: '1',
				},
				'success': fnCallback
			});
		}
	});
</script>

<?= $this->load->view('includes/footer', [], true); ?>