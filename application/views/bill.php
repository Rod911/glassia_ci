<?php
function getIndianCurrency(float $number) {
	$decimal = round($number - ($no = floor($number)), 2) * 100;
	$hundred = null;
	$digits_length = strlen($no);
	$i = 0;
	$str = array();
	$words = array(
		0 => '',
		1 => 'one',
		2 => 'two',
		3 => 'three',
		4 => 'four',
		5 => 'five',
		6 => 'six',
		7 => 'seven',
		8 => 'eight',
		9 => 'nine',
		10 => 'ten',
		11 => 'eleven',
		12 => 'twelve',
		13 => 'thirteen',
		14 => 'fourteen',
		15 => 'fifteen',
		16 => 'sixteen',
		17 => 'seventeen',
		18 => 'eighteen',
		19 => 'nineteen',
		20 => 'twenty',
		30 => 'thirty',
		40 => 'forty',
		50 => 'fifty',
		60 => 'sixty',
		70 => 'seventy',
		80 => 'eighty',
		90 => 'ninety'
	);
	$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
	while ($i < $digits_length) {
		$divider = ($i == 2) ? 10 : 100;
		$number = floor($no % $divider);
		$no = floor($no / $divider);
		$i += $divider == 10 ? 1 : 2;
		if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
		} else $str[] = null;
	}
	$Rupees = ucfirst(implode('', array_reverse($str)));
	$paise = ($decimal > 0) ? ". " . ucfirst($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	return ($Rupees ? $Rupees . 'only' : '');
	//  . $paise;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Glassia</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= as_base_url('admin/css/bill.css?v=' . css_version()) ?>">
</head>

<body>
	<header class="bill-header">
		<?php
		if ($invoice['invoice_type'] == 't') {
		?>
			<p>TAX INVOICE</p>
		<?php
		} else {
		?>
			<p>PROFORMA INVOICE</p>
		<?php
		}
		?>
	</header>
	<table class="main">
		<thead>
			<tr>
				<td colspan="3" class="top-cell">
					<table class="top-table">
						<tbody>
							<tr>
								<td rowspan="3" class="top-table-left">
									<img src="<?= as_base_url('admin/img/logo.png') ?>" alt="" class="top-img">
									<!-- <p>Glassia Solutions</p> -->
									<p>D. No. 3-2(1) Nandadeep Industries Compound, Maryhill, Mangaluru 575008</p>
									<p>GSTIN: 29ACBPH6632C1ZX</p>
								</td>
								<td class="top-table-right top-table-1">BILL NO: <span><strong><?= $invoice['bill_no'] ?></strong></span></td>
							</tr>
							<tr>
								<td class="top-table-right">Date: <span><?= date('d-m-Y', strtotime($invoice['date'])) ?></span></td>
							</tr>
							<tr>
								<td class="top-table-right">Time: <span><?= $invoice['time'] ?></span></td>
							</tr>
							<tr>
								<td class="top-table-2">
									<p>To: <span><?= $invoice['towards'] ?></span></p>
									<p><?= $invoice['address'] ?></p>
									<p>Buyer's TIN: <span><?= $invoice['buyer_tin'] ?></span></p>
								</td>
								<td>
									<p>Delivery To: <span><?= $invoice['worksite'] ?></span></p>
								</td>
							</tr>
						</tbody>
					</table>
					<!-- <div class="top">
						<div class="top-label">
							<?php
							if ($invoice['invoice_type'] == 't') {
							?>
								<p>TAX INVOICE</p>
							<?php
							}
							?>
						</div>
						<div class="top-head">
							<img src="<?= as_base_url('admin/img/logo.png') ?>" alt="">
						</div>
						<div class="top-bill">
							<?php
							if ($invoice['invoice_type'] == 't') {
							?>
								<p class="bill-no dotted">BILL NO <span><strong><?= $invoice['bill_no'] ?></strong></span></p>
							<?php
							}
							?>
						</div>
					</div> -->
				</td>
			</tr>
			<!-- <tr>
				<td colspan="3">
					<div class="address">
						<p>D. No. 3-2(1) Nandadeep Industries Compound, Maryhill, Mangaluru 575008</p>
					</div>
				</td>
			</tr> -->
			<!-- <tr>
				<td colspan="3">
					<div class="mid-1">
						<div class="mid-gst">
							<p>GSTIN: 29ACBPH6632C1ZX</p>
						</div>
						<div class="mid-datetime">
							<p class="dotted">Date: <span><?= date('d-m-Y', strtotime($invoice['date'])) ?></span></p>
							<p class="dotted">Time: <span><?= $invoice['time'] ?></span></p>
						</div>
					</div>
					<div class="mid-2">
						<div class="mid-row">
							<p class="dotted mid-left">To: <span><?= $invoice['towards'] ?></span></p>
							<p class="dotted mid-right">Buyer's TIN: <span><?= $invoice['buyer_tin'] ?></span></p>
						</div>
						<p class="dotted">Worksite: <span><?= $invoice['worksite'] ?></span></p>
					</div>
				</td>
			</tr> -->
		</thead>
		<tbody>
			<tr>
				<td colspan="3" class="particulars-cell">
					<table class="particulars">
						<thead>
							<tr>
								<th>Sl. No</th>
								<th class="particulars-col">Particulars</th>
								<th>Qnty</th>
								<th>SFT</th>
								<th>Rate</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($particulars as $pi => $item) {
								$particulars_item = $item['name'];
								$qty = $item['qty'];
								$price = $item['sft'];
								$rate = $item['rate'];
								$amount = $item['amount'];
							?>
								<tr>
									<td class="sl-cell"><?= $pi + 1 ?></td>
									<td><?= $particulars_item ?></td>
									<td class="amt-cell"><?= number_format($qty, 0) ?></td>
									<td class="amt-cell"><?= number_format($price, 2) ?></td>
									<td class="amt-cell"><?= number_format($rate, 2) ?></td>
									<td class="amt-cell"><?= number_format($amount, 2) ?></td>
								</tr>
							<?php
							}
							?>
							<tr class="spacer-row">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td class="amt-cell">Sub Total</td>
								<td></td>
								<td></td>
								<td></td>
								<td class="amt-cell"><?= number_format($invoice['sub_total'], 2) ?></td>
							</tr>
							<?php
							if ($invoice['tax_type'] == 'c') {
							?>
								<tr>
									<td></td>
									<td class="amt-cell">CGST</td>
									<td></td>
									<td></td>
									<td class="amt-cell">9%</td>
									<td class="amt-cell"><?= number_format($invoice['csgst'], 2) ?></td>
								</tr>
								<tr>
									<td></td>
									<td class="amt-cell">SGST</td>
									<td></td>
									<td></td>
									<td class="amt-cell">9%</td>
									<td class="amt-cell"><?= number_format($invoice['csgst'], 2) ?></td>
								</tr>
							<?php
							} else if ($invoice['tax_type'] == 'i') {
							?>
								<tr>
									<td></td>
									<td class="amt-cell">IGST</td>
									<td></td>
									<td></td>
									<td class="amt-cell">18%</td>
									<td class="amt-cell"><?= number_format($invoice['igst'], 2) ?></td>
								</tr>
							<?php
							}
							?>
							<tr>
								<td colspan="3">
									<p class="dotted">Rs. <span><?= getIndianCurrency(round($invoice['invoice_total'], 2)) ?></span></p>
								</td>
								<td colspan="2" class="amt-cell">
									<p><strong>TOTAL</strong></p>
								</td>
								<td class="amt-cell"><strong><?= number_format($invoice['invoice_total'], 2) ?></strong></td>
							</tr>
						</tfoot>
					</table>
				</td>
			</tr>
		</tbody>
		<tfoot class="main-foot">
			<tr>
				<td>
					<p class="dotted"><strong>Vehicle No:</strong> <span><?= $invoice['vehicle'] ?></span></p>
					<p><small><strong>Terms:</strong> Interest @24% will be charged on the due bills. <br> Goods once sold will not be taken back or exchanged. <br> Subject to Mangalore Jurisdiction</small></p>
					<p><strong>E.&O.E</strong></p>
				</td>
				<td>
					<p>Name: <strong>GLASSIA Solutions</strong></p>
					<p>A/c Number: <strong>40093101536</strong></p>
					<p>SBI Mallikatta Branch</p>
					<p>IFSC: <strong>SBIN0003823</strong></p>
				</td>
				<td>
					<p>For <img src="<?= as_base_url('admin/img/logo.png') ?>" alt=""></p>
					<p class="dotted"><span><img src="" alt="" height="40px" style="border: 0; display: block; margin: auto; height: 40px;"></span></p>
					<p style="text-align: center;">Authorised Signatory</p>
				</td>
			</tr>
		</tfoot>
	</table>
	<div class="print-action">
		<a class="btn btn-dark px-4" href="<?= base_url() ?>">List</a>
		<a class="btn btn-primary px-4" href="<?= base_url('home/form?edit=' . $invoice['bill_no']) ?>">Edit</a>
		<button class="btn btn-success px-4" onclick="window.print()">Print</button>
	</div>
</body>

</html>