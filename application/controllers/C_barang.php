<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_barang');
    }

    public function index()
    {
        $data = [
            'title_form' => 'Data Barang',
        ];
        $this->load->view('includes/header');
        $this->load->view('v_barang/index', $data);
        $this->load->view('includes/footer');
    }

    public function ajax_list()
    {
        $start  = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $params = array();
        if ($length != -1) {
            $params['limit'] = $length; //tambahan limit
            $params['start'] = $start;
        }
        $list = $this->M_barang->get_datatables($params);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $barang) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $barang->kode_barang;
            $row[] = $barang->nama_barang;
            $row[] = $barang->harga;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_barang(' . "'" . $barang->kode_barang . "'" . ')"><i class="fa fa-pencil"></i> Edit</a>
            <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_barang(' . "'" . $barang->kode_barang . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_barang->count_all($params),
            "recordsFiltered" => $this->M_barang->count_filtered($params),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function input_proses()
    {
        $out['is_error'] = true;
        $out['error_message'] = '';
        $post_data = $this->input->post();
        $this->form_validation->set_rules('txt_input_kode_bar', 'Kode Barang', 'required');
        $this->form_validation->set_rules('txt_input_nama_bar', 'Nama Barang', 'required');
        $this->form_validation->set_rules('txt_input_harga', 'Harga', 'required');
        if ($this->form_validation->run() == false) {
            $out['is_error'] = true;
            $out['error_message'] = validation_errors();
        } else {

            $save_data = array(
                'kode_barang' => $post_data['txt_input_kode_bar'],
                'nama_barang' => $post_data['txt_input_nama_bar'],
                'harga' => $post_data['txt_input_harga'],
            );
            $check = $this->M_barang->check($post_data['txt_input_kode_bar']);
            if ($check > 0) {
                $out['is_error'] = true;
                $out['error_message'] = 'Kode Barang Sudah Ada';
            } else {
                $this->db->trans_begin();
                $this->M_barang->save($save_data);
                if ($this->db->trans_status() == false) {
                    $this->db->trans_rollback();
                    $out['is_error'] = true;
                    $out['error_message'] = 'database error';
                } else {
                    $this->db->trans_commit();
                    $out['is_error'] = false;
                    $out['succes_message'] = 'Input Data Barang Berhasil.';
                }
            }
        }
        echo json_encode($out);
    }

    public function ajax_edit($kode_barang)
    {
        $data = $this->M_barang->get_by_id($kode_barang); // Contoh fungsi model
        echo json_encode($data);
    }

    public function update_proses()
    {
        $out['is_error'] = true;
        $out['error_message'] = '';
        $post_data = $this->input->post();
        $this->form_validation->set_rules('txt_edit_id_bar', 'Kode Barang', 'required');
        $this->form_validation->set_rules('txt_edit_kode_bar', 'Kode Barang', 'required');
        $this->form_validation->set_rules('txt_edit_nama_bar', 'Nama Barang', 'required');
        $this->form_validation->set_rules('txt_edit_harga', 'Harga', 'required');
        if ($this->form_validation->run() == false) {
            $out['is_error'] = true;
            $out['error_message'] = validation_errors();
        } else {

            $save_data = array(
                'kode_barang' => $post_data['txt_edit_kode_bar'],
                'nama_barang' => $post_data['txt_edit_nama_bar'],
                'harga' => $post_data['txt_edit_harga'],
            );
            $check = $this->M_barang->check($post_data['txt_edit_kode_bar']);
            if ($check > 0 && $post_data['txt_edit_kode_bar'] != $post_data['txt_edit_kode_bar_old']) {
                $out['is_error'] = true;
                $out['error_message'] = 'Kode Barang Sudah Ada';
            } else {
                $this->db->trans_begin();
                $this->M_barang->update(array('id' => $post_data['txt_edit_id_bar']), $save_data);
                if ($this->db->trans_status() == false) {
                    $this->db->trans_rollback();
                    $out['is_error'] = true;
                    $out['error_message'] = 'database error';
                } else {
                    $this->db->trans_commit();
                    $out['is_error'] = false;
                    $out['succes_message'] = 'Update Data Barang Berhasil.';
                }
            }
        }
        echo json_encode($out);
    }

    public function delete_permanen($kode_barang)
    {
        $this->M_barang->delete_by_id($kode_barang);
        echo json_encode(['status' => true]);
    }
}
