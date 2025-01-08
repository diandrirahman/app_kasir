<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	/** 
	 * check login
	 * @param string $user
	 *        int    $pass
	 * @return array 
	 */
	public function check_login($u, $p)
	{
		$this->load->library('user_agent');

		// $username = substr($u, 0, -2);
		$password = sha1($p);
		$this->db->select('a.*, b.level_name');
		$this->db->from('db_appkasir.tb_user a');
		$this->db->join('db_appkasir.tb_level b', 'a.id_level = b.id', 'left');
		$this->db->where('a.username', $u);
		$this->db->where('a.password', $password);
		$result = $this->db->get();

		if ($result->num_rows() > 0) {
			return $result->row();
		}
		return false;
	}
	public function get_by_id($id)
	{
		$this->db->select("*");
		$this->db->from('db_logins.tb_logins');
		$this->db->where('USER_NAME', $id);
		$query = $this->db->get();

		return $query->row();
	}
	public function update($where, $save_data)
	{

		$this->db->update('db_repacking.tb_logins', $save_data, $where);
		return $this->db->affected_rows();
	}
	public function get_by_username($username)
	{
		$query = $this->db->get_where('db_logins.tb_logins', array('USER_NAME' => $username));
		return $query->row_array();
	}

	public function save($data)
	{
		$this->db->insert('db_repacking.tb_logins', $data);
		return $this->db->insert_id();
	}

	public function save_log($data)
	{
		$this->db->insert('db_repacking.tb_repacking_logs', $data);
		return $this->db->insert_id();
	}
	// Check NIK exist	
	public function check_nik_exists($nik)
	{
		$this->db->where('NIK', $nik);
		$query = $this->db->get('db_repacking.tb_logins'); // Nama tabel database
		return $query->num_rows() > 0;
	}
	// Insert batch data
	public function insert_batch($data)
	{
		$this->db->insert_batch('db_repacking.tb_logins', $data); // Nama tabel database
	}

	// Update batch data
	public function update_batch($data_login, $field_key)
	{
		$this->db->update_batch('db_repacking.tb_logins', $data_login, $field_key); // Nama tabel database
	}
}
