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

		/* Hosting overlay */
		.print-action+div {
			visibility: hidden;
		}

		.disclaimer {
			display: none;
		}

		caption {
			caption-side: top;
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
			<?php
			if ($customer != '') {
			?>
				<h4><?= $customer ?></h4>
			<?php
			}
			?>
			<caption>Invoices</caption>
			<thead>
				<tr>
					<th class="px-2 text-nowrap">Bill No</th>
					<?php
					if ($customer == '') {
					?>
						<th class="px-2">Customer</th>
					<?php
					}
					?>
					<th class="px-2 text-end">Amount</th>
					<th class="px-2 text-end">Date</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$paid = 0;
				$total = 0;
				foreach ($statements as $si => $stm) {
					$total += $stm['invoice_total'];
				?>
					<tr>
						<td class="px-2"><?= $stm['bill_no'] ?></td>
						<?php
						if ($customer == '') {
						?>
							<td class="px-2"><?= $stm['towards'] ?></td>
						<?php
						}
						?>
						<td class="px-2 text-end font-monospace"><?= number_format($stm['invoice_total'], 2) ?></td>
						<td class="px-2 text-end text-nowrap"><?= date('d-m-Y', strtotime($stm['date'])) ?></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
		<?php
		if (count($customer_receipts) > 0) {
		?>
			<table class="table table-bordered table-sm">
				<caption>Payment Received</caption>
				<thead>
					<tr>
						<?php
						if ($customer == '') {
						?>
							<th class="px-2">Customer</th>
						<?php
						}
						?>
						<th class="px-2 text-end">Amount</th>
						<th class="px-2 text-end">Date</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($customer_receipts as $si => $stm) {
						$paid += $stm['amount'];
					?>
						<tr>
							<?php
							if ($customer == '') {
							?>
								<td class="px-2"><?= $stm['customer'] ?></td>
							<?php
							}
							?>
							<td class="px-2 text-end font-monospace"><?= number_format($stm['amount'], 2) ?></td>
							<td class="px-2 text-end text-nowrap"><?= date('d-m-Y', strtotime($stm['payment_date'])) ?></td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		<?php
		}
		?>
		<table class="table table-bordered table-sm">
			<thead>
				<tr>
					<?php
					if ($has_opening_balance) {
					?>
						<th></th>
					<?php
					}
					?>
					<th class="px-2 text-end">Invoice Total</th>
					<th class="px-2 text-end">Payment Total</th>
					<th class="px-2 text-end">Balance</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$opening_balance_amt = 0;
				if ($has_opening_balance) {
					$opening_balance_amt = $opening_balance_invoices - $opening_balance_payments;
				?>
					<tr>
						<td class="px-2">Opening (up to: <?= $opening_balance_date ?>)</td>
						<td class="px-2 text-end font-monospace"><b><?= number_format($opening_balance_invoices, 2) ?></b></td>
						<td class="px-2 text-end font-monospace"><b><?= number_format($opening_balance_payments, 2) ?></b></td>
						<td class="px-2 text-end font-monospace"><b><?= number_format($opening_balance_amt, 2) ?></b></td>
					</tr>
				<?php
				}
				?>
				<tr>
					<?php
					if ($has_opening_balance && $has_closing_balance) {
					?>
						<td class="px-2">Current (<?= $from_date ?> - <?= $to_date ?>)</td>
					<?php
					}
					?>
					<td class="px-2 text-end font-monospace"><b><?= number_format($total, 2) ?></b></td>
					<td class="px-2 text-end font-monospace"><b><?= number_format($paid, 2) ?></b></td>
					<td class="px-2 text-end font-monospace"><b><?= number_format($opening_balance_amt + $total - $paid, 2) ?></b></td>
				</tr>
			</tbody>
		</table>
		<div class="text-center d-print-none py-3">
			<button class="btn btn-info" onclick="window.history.back()">Back</button>
			<button class="btn btn-primary" onclick="window.print()">Print</button>
		</div>
	</div>
	<div class="print-action"></div>
</body>

</html>