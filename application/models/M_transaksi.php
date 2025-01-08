<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_transaksi extends CI_Model
{

    public function save_transaction($data, $items)
    {
        // Begin transaction
        $this->db->trans_start();

        // Insert main transaction
        $this->db->insert('tb_transaksi', $data);

        // Insert transaction details
        foreach ($items as $item) {
            $this->db->insert('tb_transaksi_detail', $item);
        }

        // Complete transaction
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_transaction($id_transaksi)
    {
        // Get main transaction
        $transaction = $this->db->get_where('tb_transaksi', ['id_transaksi' => $id_transaksi])->row();

        // Get transaction details
        $details = $this->db->get_where('tb_transaksi_detail', ['id_transaksi' => $id_transaksi])->result();

        return [
            'transaction' => $transaction,
            'details' => $details
        ];
    }
}
