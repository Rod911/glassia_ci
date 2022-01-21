<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Glassia Billing">
	<meta name="author" content="Malcolm Rodrigues">
	<title>Glassia</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<meta name="theme-color" content="#7952b3">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

	<style>
		.view {
			max-width: 1000px;
			margin-left: auto;
			margin-right: auto;
			padding: 15px;
		}

		@media print {
			.view {
				padding: 0;
				max-width: none;
			}
		}
	</style>

</head>

<body>
	<div class="view">
		<div class="text-center d-print-none py-3">
			<button class="btn btn-info" onclick="window.history.back()">Back</button>
			<button class="btn btn-primary" onclick="window.print()">Print</button>
		</div>
		<table class="table table-bordered table-sm">
			<thead>
				<tr>
					<th class="px-2">Bill No</th>
					<th class="px-2">Customer</th>
					<th class="px-2 text-end">Amount</th>
					<th class="px-2 text-end">Date</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($statements as $si => $stm) {
				?>
					<tr>
						<td class="px-2"><?= $stm['bill_no'] ?></td>
						<td class="px-2"><?= $stm['towards'] ?></td>
						<td class="px-2 text-end font-monospace"><?= $stm['invoice_total'] ?></td>
						<td class="px-2 text-end"><?= date('d-m-Y', strtotime($stm['date'])) ?></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		<div class="text-center d-print-none py-3">
			<button class="btn btn-info" onclick="window.history.back()">Back</button>
			<button class="btn btn-primary" onclick="window.print()">Print</button>
		</div>
	</div>
</body>

</html>