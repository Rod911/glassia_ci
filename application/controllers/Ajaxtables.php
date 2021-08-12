<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajaxtables extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('datatables');
		$this->load->model('AdminModel');
		$this->AdminModel->verify_admin();
	}

	private function add_action_col($cols) {
		$action_col = '<div class="btn-group" role="group" aria-label="Button group">';
		foreach ($cols as $col_name => $col_value) {
			if ($col_name == 'edit') {
				$action_col .= '<a href="' . ad_base_url($col_value . '?edit=$1') . '" class="btn btn-warning btn-sm"><i class="fa fa-fw fa-pencil-alt"></i></a>';
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
}
