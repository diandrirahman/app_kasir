<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user extends CI_Model
{
    var $table = 'db_appkasir.tb_user';
    var $column_order = array(null, 'a.username', 'a.nama', 'b.level_name', null);
    var $column_search = array('a.username', 'a.nama');
    var $order = array('a.ID' => 'asc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->select("a.*, b.level_name");
        $this->db->from('db_appkasir.tb_user a');
        $this->db->join('db_appkasir.tb_level b', 'b.id = a.id_level');

        if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
            $search_value = strtolower($_POST['search']['value']); // Konversi ke huruf kecil
            $i = 0;
            foreach ($this->column_search as $item) { // loop column
                if ($i === 0) {
                    $this->db->group_start(); // open bracket
                    $this->db->like("LOWER($item)", $search_value); // Gunakan LOWER pada kolom
                } else {
                    $this->db->or_like("LOWER($item)", $search_value); // Gunakan LOWER pada kolom
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end(); // close bracket
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) { // order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


    function get_datatables($params)
    {
        $this->_get_datatables_query();

        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered($params)
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($params)
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_by_id($id)
    {
        $this->db->from('db_appkasir.tb_user a');
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function check($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }
}
