<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajaxtables extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('datatables');
	}

	private function add_action_col($cols) {
		$action_col = '<div class="btn-group" role="group" aria-label="Button group">';
		foreach ($cols as $col_name => $col_value) {
			if ($col_name == 'edit') {
				$action_col .= '<a href="' . base_url($col_value . '?edit=$1') . '" class="btn btn-warning btn-sm"><i class="fa fa-fw fa-pencil-alt"></i></a>';
			}
			if ($col_name == 'delete') {
				$action_col .= '<button data-id="' . $col_value . '" class="btn btn-danger btn-sm delete-record"><i class="fa fa-fw fa-trash"></i></button>';
			}
			if ($col_name == 'status') {
				$action_col .= '<button data-id="' . $col_value . '" data-status="$2" class="btn btn-success btn-sm update-status"><i class="fa fa-fw fa-check"></i></button>';
			}
		}
		$action_col .= '</div>';
		return $action_col;
	}

	private function add_image_col($path) {
		return '<img src="' . a_base_url($path . '/$1') . '" style="max-width: 100px; max-height: 80px;">';
	}

	private function setSearchableColumns(array $columns) {
		foreach ($columns as $ci) {
			$_POST['columns'][$ci]['searchable'] = true;
		}
	}

	public function invoices() {
		$this->db->order_by('bill_no', 'DESC');
		$this->datatables
			->select('bill_no, towards, invoice_total, date')
			->add_column(
				'receipt',
				'<button class="btn btn-info btn-sm" data-popup-view="add_receipt" data-no-btn=1 data-modal-size="swal-wide" data-id=$1><i class="bi bi-cash"></i> Receipt</button>',
				'bill_no'
			)
			->add_column(
				'edit',
				'<a href="' . base_url('home/form?edit=$1') . '" class="btn btn-primary btn-sm"><i class="bi bi-receipt-cutoff"></i> Edit</a>',
				'bill_no'
			)
			->add_column(
				'bill',
				'<a href="' . base_url('home/bill_view/$1') . '" class="btn btn-success btn-sm"><i class="bi bi-save"></i> Bill</a>',
				'bill_no'
			)
			->from('tax_invoices');
		$this->setSearchableColumns([0, 1, 3]);
		echo $this->datatables->generate();
	}

	public function receipts() {
		$this->db->order_by('bill_no', 'DESC');
		$this->datatables->where('towards', $this->input->post('filter')['towards']);
		$this->datatables
			->select('t.bill_no, invoice_total, t.date as invoice_date, r.date as received_date, IFNULL(received_amt, 0) as received_amt, (invoice_total - IFNULL(received_amt, 0)) as pending_amount')
			->add_column(
				'bill',
				'<a href="' . base_url('home/bill_view/$1') . '" class="btn btn-success btn-sm"><i class="bi bi-save"></i> Bill</a>',
				'bill_no'
			)
			->from('tax_invoices t')
			->join('payment_receipts r', 'r.bill_no = t.bill_no', 'LEFT');
		$this->setSearchableColumns([0, 1, 2, 3, 4, 5]);
		echo $this->datatables->generate();
		// echo $this->db->last_query();
	}
}
