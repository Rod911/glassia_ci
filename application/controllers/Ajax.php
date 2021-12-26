<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function add_receipt() {
		$bill_no = $this->input->post('value');
		$data = $this->db->get_where('tax_invoices', ['bill_no' => $bill_no], 1)->row_array();
		$data['receipts'] = $this->db->get_where('payment_receipts', ['bill_no' => $bill_no])->result_array();
		$view = [
			'title' => 'Payment Received',
			'content' => $this->load->view('ajax/add_receipt', $data, true),
			'data' => $data,
		];
		echo json_encode($view);
	}

	public function delete_record() {
		$data = explode(',', $this->input->post('id'));
		$row = $data[0];
		$table = $data[1];
		$page_name = $this->table_pages[$table] ?? $table;
		$key = 'id';
		if (isset($data[2])) {
			$key = $data[2];
		}
		$res = $this->db->delete($table, [$key => $row]);
		echo json_encode(['success' => $res, 'error' => $this->db->error(), 'error_message' => '']);
	}

	public function get_customer_totals() {
		$towards = $this->input->get('towards');
		$where = ['towards' => $towards];
		if ($this->input->get('from') != '') {
			$where['t.date >='] = date('Y-m-d', strtotime($this->input->get('from')));
		}
		if ($this->input->get('to') != '') {
			$where['t.date <='] = date('Y-m-d 23:59:59', strtotime($this->input->get('to')));
		}

		$bill_total = $this->db
			->select_sum('invoice_total', 'invoice_total_amount')
			->where($where)
			->get('tax_invoices t')
			->row_array()['invoice_total_amount'] ?? 0;
		$received_total = $this->db
			->select_sum('received_amt', 'received_total_amount')
			->where('towards', $towards)
			->where($where)
			->from('payment_receipts r')
			->join('tax_invoices t', 'r.bill_no = t.bill_no', 'LEFT')
			->get()
			->row_array()['received_total_amount'] ?? 0;
		echo json_encode([
			'total' => number_format($bill_total, 2),
			'received' => number_format($received_total, 2),
			'pending' => number_format($bill_total - $received_total, 2),
		]);
	}

	public function placeholder_img() {

		// Dimensions
		$getsize    = isset($_GET['size']) ? $_GET['size'] : '100x100';
		$dimensions = explode('x', $getsize);
		$dim_y = 150;
		$dim_x = min($dim_y * ($dimensions[0] / $dimensions[1]), 300);

		// Create image
		$image      = imagecreate($dim_x, $dim_y);

		// Colours
		$bg         = isset($_GET['bg']) ? $_GET['bg'] : 'ccc';
		$bg         = hex2rgb($bg);
		$setbg      = imagecolorallocate($image, $bg['r'], $bg['g'], $bg['b']);

		$fg         = isset($_GET['fg']) ? $_GET['fg'] : '555';
		$fg         = hex2rgb($fg);
		$setfg      = imagecolorallocate($image, $fg['r'], $fg['g'], $fg['b']);

		// Text
		$text       = isset($_GET['text']) ? strip_tags($_GET['text']) : $getsize;
		$text       = str_replace('+', ' ', $text);

		// Text positioning
		$fontsize   = 4;
		$fontwidth  = imagefontwidth($fontsize);    // width of a character
		$fontheight = imagefontheight($fontsize);   // height of a character
		$length     = strlen($text);                // number of characters
		$textwidth  = $length * $fontwidth;         // text width
		$xpos       = (imagesx($image) - $textwidth) / 2;
		$ypos       = (imagesy($image) - $fontheight) / 2;

		// Generate text
		imagestring($image, $fontsize, $xpos, $ypos, $text, $setfg);

		// Render image
		imagepng($image);
		imagedestroy($image);
	}
}

// Convert hex to rgb (modified from csstricks.com)
function hex2rgb($colour) {
	$colour = preg_replace("/[^abcdef0-9]/i", "", $colour);
	if (strlen($colour) == 6) {
		list($r, $g, $b) = str_split($colour, 2);
		return array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
	} elseif (strlen($colour) == 3) {
		list($r, $g, $b) = array($colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2]);
		return array("r" => hexdec($r), "g" => hexdec($g), "b" => hexdec($b));
	}
	return false;
}
