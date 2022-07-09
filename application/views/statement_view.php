<?php
$unfied_list = array_merge($statements, $customer_receipts);
function compare($a, $b) {
	$date1 = strtotime($a['date']);
	$date2 = strtotime($b['date']);
	return $date1 - $date2;
}
uasort($unfied_list, 'compare');

if ($has_opening_balance) {
	$opening_balance_amt = $opening_balance_invoices - $opening_balance_payments;
} else {
	$opening_balance_amt = 0;
	$opening_balance_invoices = 0;
	$opening_balance_payments = 0;
}
?>
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
			<thead>
				<tr>
					<th class="px-2 text-end">Date</th>
					<th class="px-2 text-nowrap text-end">Bill No</th>
					<?php
					if ($customer == '') {
					?>
						<th class="px-2">Customer</th>
					<?php
					}
					?>
					<th class="px-2 text-end">Bill Amount</th>
					<th class="px-2 text-end">Received</th>
					<th class="px-2 text-end">Balance</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($has_opening_balance) {
				?>
					<tr>
						<td class="px-2 text-end">Opening (up to: <?= $opening_balance_date ?>)</td>
						<td></td>
						<td class="px-2 text-end font-monospace"><b><?= number_format($opening_balance_invoices, 2) ?></b></td>
						<td class="px-2 text-end font-monospace"><b><?= number_format($opening_balance_payments, 2) ?></b></td>
						<!-- <td class="px-2 text-end font-monospace"><b><?= number_format($opening_balance_amt, 2) ?></b></td> -->
					</tr>
				<?php
				}
				?>
				<?php
				$paid = 0;
				$total = 0;
				$active_balance = $opening_balance_amt;
				foreach ($unfied_list as $si => $stm) {
				?>
					<tr>
						<td class="px-2 text-end text-nowrap"><?= date('d-m-Y', strtotime($stm['date'])) ?></td>
						<td class="px-2 text-end font-monospace"><?= $stm['bill_no'] ?></td>
						<?php
						if ($customer == '') {
						?>
							<td class="px-2"><?= $stm['towards'] ?></td>
						<?php
						}
						?>
						<?php
						if ($stm['type'] == "i") {
							$total += $stm['invoice_total'];
							$active_balance += $stm['invoice_total'];
						?>
							<td class="px-2 text-end font-monospace"><?= number_format($stm['invoice_total'], 2) ?></td>
							<td></td>
						<?php
						} elseif ($stm['type'] == "r") {
							$paid += $stm['amount'];
							$active_balance -= $stm['amount'];
						?>
							<td></td>
							<td class="px-2 text-end font-monospace"><?= number_format($stm['invoice_total'], 2) ?></td>
						<?php
						}
						?>
						<td class="px-2 text-end font-monospace"><?= number_format($active_balance, 2) ?></td>
					</tr>
				<?php
				}
				$current_invoices = $opening_balance_invoices + $total;
				$current_payments = $opening_balance_payments + $paid;
				$current_balance = $opening_balance_amt + $total - $paid;
				$balance_sheet = $current_invoices > $current_payments ? $current_invoices : $current_payments;
				?>
				<tr>
					<td></td>
					<td></td>
					<td class="px-2 text-end font-monospace"><b><?= number_format($total, 2) ?></b></td>
					<td class="px-2 text-end font-monospace"><b><?= number_format($paid, 2) ?></b></td>
					<td></td>
				</tr>
				<tr>
					<td class="px-2 text-end">Closing Balance</td>
					<td></td>
					<?php
					if ($current_balance > 0) {
					?>
						<td></td>
						<td class="px-2 text-end font-monospace"><b><?= number_format($current_balance, 2) ?></b></td>
					<?php
					} else {
					?>
						<td class="px-2 text-end font-monospace"><b><?= number_format($current_balance, 2) ?></b></td>
						<td></td>
					<?php
					}
					?>
					<td></td>
				</tr>
				<tr class="bg-light">
					<td></td>
					<td></td>
					<td class="px-2 text-end font-monospace"><b><?= number_format($balance_sheet, 2)  ?></b></td>
					<td class="px-2 text-end font-monospace"><b><?= number_format($balance_sheet, 2) ?></b></td>
					<td></td>
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