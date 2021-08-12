<?php

class MY_Form_validation extends CI_Form_validation {

	public function __construct($rules = array()) {
		$this->CI = &get_instance();
		parent::__construct($rules);
	}
	public function edit_unique($str, $field) {
		sscanf($field, '%[^.].%[^.].%[^.].%[^.]', $table, $field, $columnIdName, $id);
		// echo $id;
		return isset($this->CI->db)
			? ($this->CI->db->limit(1)->get_where($table, array($field => $str, $columnIdName . '!=' => $id))->num_rows() === 0)
			: FALSE;
	}
}
