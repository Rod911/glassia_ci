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

	public function statement() {
		$toward_options = $this->db
			->select('TRIM(towards) as towards')
			->group_by('towards')
			->order_by('towards', 'ASC')
			->from('tax_invoices')
			->get()
			->result_array();
		$data['toward_options'] = array_column($toward_options, 'towards', 'towards');
		$data['toward_options'] = ['' => 'All'] + $data['toward_options'];

		$data['date_options'] = [];
		$dateTime = new DateTime('first day of this month');
		for ($i = 1; $i <= 6; $i++) {
			$data['date_options'][] = [
				'label' => $dateTime->format('F, Y'),
				'date_from' => $dateTime->format('Y-m-d'),
				'date_to' => $dateTime->format('Y-m-t'),
			];
			$dateTime->modify('-1 month');
		}

		$this->load->view('statement', $data);
	}

	public function get_statement() {
		$customer = $this->input->get('customer');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		$data['has_opening_balance'] = false;
		$data['has_closing_balance'] = false;
		if ($from_date != '') {
			$this->db->where('t.date >=', date('Y-m-d', strtotime($from_date)));
			$data['has_opening_balance'] = true;
			$data['opening_balance_date'] = date('jS M, Y', strtotime('-1 day', strtotime($from_date)));
			$data['from_date'] = date('jS M, Y', strtotime($from_date));
		}
		if ($to_date != '') {
			$this->db->where('t.date <=', date('Y-m-d', strtotime($to_date)));
			$data['has_closing_balance'] = true;
			$data['to_date'] = date('jS M, Y', strtotime($to_date));
		}
		if ($customer != '') {
			$this->db->where('towards', $customer);
		}
		$data['statements'] = $this->db
			->select('t.*, "i" as type')
			// ->select_sum('r.received_amt', 'received_amt')
			->order_by('t.bill_no')
			->from('tax_invoices t')
			// ->join('payment_receipts r', 'r.bill_no = t.bill_no', 'LEFT')
			// ->group_by('t.bill_no')
			->get()
			->result_array();
		if ($from_date != '') {
			$this->db->where('payment_date >=', date('Y-m-d', strtotime($from_date)));
		}
		if ($to_date != '') {
			$this->db->where('payment_date <=', date('Y-m-d', strtotime($to_date)));
		}
		if ($customer != '') {
			$this->db->where('customer', $customer);
		}
		$data['customer'] = $customer;
		$data['customer_receipts'] = $this->db
			->select('*, payment_date as date, "r" as type, "" as bill_no, customer as towards, amount as invoice_total')
			->order_by('payment_date')
			->get('customer_receipts')
			->result_array();

		if ($from_date != '') {
			$this->db->where('date <', date('Y-m-d', strtotime($from_date)));
		}
		if ($customer != '') {
			$this->db->where('towards', $customer);
		}
		$data['opening_balance_invoices'] = $this->db
			->select_sum('invoice_total')
			->get('tax_invoices')
			->row_array()['invoice_total'];
		if ($from_date != '') {
			$this->db->where('payment_date <', date('Y-m-d', strtotime($from_date)));
		}
		if ($customer != '') {
			$this->db->where('customer', $customer);
		}
		$data['opening_balance_payments'] = $this->db
			->select_sum('amount')
			->get('customer_receipts')
			->row_array()['amount'];

		$this->load->view('statement_view', $data);
	}

	public function payments() {
		$toward_options = $this->db
			->select('TRIM(towards) as towards')
			->group_by('towards')
			->order_by('towards', 'ASC')
			->from('tax_invoices')
			->get()
			->result_array();
		$data['toward_options'] = ['' => "Select Customer"] + array_column($toward_options, 'towards', 'towards');
		$this->load->view('payments', $data);
	}

	public function submit_payment() {
		$date = $this->input->post('date');
		$customer = $this->input->post('customer');
		$received_amt = $this->input->post('received_amt');
		$id = $this->input->post('payment_id');

		$post_receipt = [
			'customer' => $customer,
			'payment_date' => date('Y-m-d', strtotime($date)),
			'amount' => $received_amt,
		];

		if ($id == "") {
			$post_receipt['receipt_date'] = date('Y-m-d H:i:s');
			$this->db->insert('customer_receipts', $post_receipt);
		} else {
			$this->db->update('customer_receipts', $post_receipt, ['id' => $id], 1);
		}

		redirect(base_url('home/payments'));
	}

	public function makeRecoverySQL($table) {
		$records = $this->db->get($table)->result_array();
		$insert_sql = "INSERT INTO `$table` VALUES ";
		$insert_values = [];
		foreach ($records as $i => $row) {
			$row_values = array_values($row);
			$insert_values[] = "\n(`" . implode('`,`', $row_values) . "`)";
		}
		$insert_sql .= implode(', ', $insert_values) . ";";
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=$table.sql");
		echo $insert_sql;
	}
}
