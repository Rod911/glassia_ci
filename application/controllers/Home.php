<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller {
	public function index() {
		$this->load->view('home');
	}

	public function form() {
		$bill_no = $this->input->get('edit');
		$data['invoice'] = $this->db->get_where('tax_invoices', ['bill_no' => $bill_no], 1)->row_array();
		$data['particulars'] = $this->db->get_where('invoice_particulars', ['bill_no' => $bill_no])->result_array();

		if (!$data['invoice']) {
			$last_bill = $this->db
				->select('bill_no')
				->limit(1)
				->order_by('bill_no', 'DESC')
				->get('tax_invoices')
				->row_array();
			if ($last_bill) {
				$data['invoice']['bill_no'] = $last_bill['bill_no'] + 1;
			}
		}
		$this->load->view('form', $data);
	}

	public function bill() {
		$post['bill_no'] = $this->input->post('bill_no');
		$post['invoice_type'] = $this->input->post('invoice_type');
		$data['date'] = $this->input->post('date');
		if ($data['date'] != '') {
			$post['date'] = date('Y-m-d', strtotime($data['date']));
		}
		$data['time'] = $this->input->post('time');
		if ($data['time'] != '') {
			$post['time'] = date('H:i:s', strtotime($data['time']));
		}
		$post['towards'] = $this->input->post('to');
		$post['worksite'] = $this->input->post('worksite');
		$post['address'] = $this->input->post('address');
		$post['vehicle'] = $this->input->post('vehicle');
		$post['buyer_tin'] = $this->input->post('buyer_tin');
		$post['tax_type'] = $this->input->post('tax_type');
		$post['sub_total'] = 0;
		$post['created_date'] = date('Y-m-d H:i:s');

		$data['hsn'] = $this->input->post('hsn');
		$data['particulars_items'] = $this->input->post('particulars_items');
		$data['particulars_qty'] = $this->input->post('particulars_qty');
		$data['particulars_price'] = $this->input->post('particulars_price');
		$data['particulars_rate'] = $this->input->post('particulars_rate');

		$post_p = [];
		foreach ($data['particulars_items'] as $di => $particular) {
			$name = $data['particulars_items'][$di];
			$hsn = $data['hsn'][$di];
			$qty = $data['particulars_qty'][$di];
			$price = $data['particulars_price'][$di];
			$rate = $data['particulars_rate'][$di];
			$amount = round($price * $rate);
			$post['sub_total'] += $amount;

			$particular_item = [
				'bill_no' => $post['bill_no'],
				'name' => $name,
				'hsn' => $hsn,
				'qty' => $qty,
				'sft' => $price,
				'rate' => $rate,
				'amount' => $amount,
			];

			$post_p[] = $particular_item;
		}
		$post['csgst'] = round($post['sub_total'] * 9 / 100);
		$post['igst'] = round($post['sub_total'] * 18 / 100);

		$post['invoice_total'] = $post['sub_total'] + $post['igst'];

		$invoice = $this->db->get_where('tax_invoices', ['bill_no' => $post['bill_no']], 1)->row_array();
		if ($invoice) {
			$this->db->update('tax_invoices', $post, ['bill_no' => $post['bill_no']], 1);
			$this->db->delete('invoice_particulars', ['bill_no' => $post['bill_no']]);
		} else {
			$this->db->insert('tax_invoices', $post);
		}
		$this->db->insert_batch('invoice_particulars', $post_p);

		redirect(base_url('home/bill_view/' . $post['bill_no']));
	}

	public function bill_view($bill_no) {
		$data['invoice'] = $this->db->get_where('tax_invoices', ['bill_no' => $bill_no], 1)->row_array();
		if (!$data['invoice']) {
			redirect(base_url('home/form?edit=' . $bill_no));
		}
		$data['particulars'] = $this->db->get_where('invoice_particulars', ['bill_no' => $bill_no])->result_array();

		$this->load->view('bill', $data);
	}

	public function receipts() {
		$toward_options = $this->db
			->select('TRIM(towards) as towards')
			->group_by('towards')
			->order_by('towards', 'ASC')
			->from('tax_invoices')
			->get()
			->result_array();
		$data['toward_options'] = array_column($toward_options, 'towards', 'towards');
		$this->load->view('receipts', $data);
	}

	public function submit_receipt() {
		$bill_no = $this->input->post('bill_no');
		$date = $this->input->post('date');
		$received_amt = $this->input->post('received_amt');

		$data = $this->db->get_where('tax_invoices', ['bill_no' => $bill_no], 1)->row_array();
		if ($received_amt > $data['invoice_total']) {
			redirect(base_url());
			return;
		}

		$post_receipt = [
			'bill_no' => $bill_no,
			'date' => date('Y-m-d', strtotime($date)),
			'received_amt' => $received_amt,
			'created_date' => date('Y-m-d'),
		];

		$this->db->insert('payment_receipts', $post_receipt);
		redirect(base_url('home/receipts?towards=' . $data['towards']));
	}
}
