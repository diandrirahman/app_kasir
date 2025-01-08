<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_user extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_user');
    }

    public function index()
    {
        $data = [
            'title_form' => 'Data user',
        ];
        $this->load->view('includes/header');
        $this->load->view('v_user/index', $data);
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
        $list = $this->M_user->get_datatables($params);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $user) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $user->username;
            $row[] = $user->nama;
            $row[] = $user->level_name;
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_user(' . "'" . $user->id . "'" . ')"><i class="fa fa-pencil"></i> Edit</a>
            <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_user(' . "'" . $user->id . "'" . ')"><i class="fa fa-trash"></i> Delete</a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_user->count_all($params),
            "recordsFiltered" => $this->M_user->count_filtered($params),
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
        $this->form_validation->set_rules('txt_input_username', 'username', 'required');
        $this->form_validation->set_rules('txt_input_nama', 'Nama', 'required');
        $this->form_validation->set_rules('akses', 'Akses', 'required');
        if ($this->form_validation->run() == false) {
            $out['is_error'] = true;
            $out['error_message'] = validation_errors();
        } else {
            $password = sha1('Qwerty123456');
            $save_data = array(
                'username' => $post_data['txt_input_username'],
                'password' => $password,
                'nama' => $post_data['txt_input_nama'],
                'id_level' => $post_data['akses'],
            );
            $check = $this->M_user->check($post_data['txt_input_username']);
            if ($check > 0) {
                $out['is_error'] = true;
                $out['error_message'] = 'Username Sudah Ada';
            } else {
                $this->db->trans_begin();
                $this->M_user->save($save_data);
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

    public function ajax_edit($id)
    {
        $data = $this->M_user->get_by_id($id); // Contoh fungsi model
        echo json_encode($data);
    }

    public function update_proses()
    {
        $out['is_error'] = true;
        $out['error_message'] = '';
        $post_data = $this->input->post();
        $this->form_validation->set_rules('txt_edit_id', 'ID', 'required');
        $this->form_validation->set_rules('txt_edit_username', 'Username', 'required');
        $this->form_validation->set_rules('txt_edit_nama', 'Nama', 'required');
        $this->form_validation->set_rules('edit_akses', 'Akses', 'required');
        if ($this->form_validation->run() == false) {
            $out['is_error'] = true;
            $out['error_message'] = validation_errors();
        } else {
            $save_data = array(
                'username' => $post_data['txt_edit_username'],
                'nama' => $post_data['txt_edit_nama'],
                'id_level' => $post_data['edit_akses'],
            );
            $check = $this->M_user->check($post_data['txt_edit_username']);
            if ($check > 0 && $post_data['txt_edit_username'] != $post_data['txt_edit_username_old']) {
                $out['is_error'] = true;
                $out['error_message'] = 'Username Sudah Ada';
            } else {
                $this->db->trans_begin();
                $this->M_user->update(array('id' => $post_data['txt_edit_id']), $save_data);
                if ($this->db->trans_status() == false) {
                    $this->db->trans_rollback();
                    $out['is_error'] = true;
                    $out['error_message'] = 'database error';
                } else {
                    $this->db->trans_commit();
                    $out['is_error'] = false;
                    $out['succes_message'] = 'Update Data User Berhasil.';
                }
            }
        }
        echo json_encode($out);
    }

    public function delete_permanen($id)
    {
        $this->M_user->delete_by_id($id);
        echo json_encode(['status' => true]);
    }
}
