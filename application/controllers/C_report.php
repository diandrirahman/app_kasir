<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_report');
    }

    public function index()
    {
        $data = [
            'title_form' => 'Laporan',
        ];
        $this->load->view('includes/header');
        $this->load->view('v_laporan/index', $data);
        $this->load->view('includes/footer');
    }

    public function ajax_list()
    {
        $start  = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $params = array();

        if ($this->input->post('from_date')) {
            $params['from_date'] = $this->input->post('from_date');
        }
        if ($this->input->post('to_date')) {
            $params['to_date'] = $this->input->post('to_date');
        }
        if ($length != -1) {
            $params['limit'] = $length; //tambahan limit
            $params['start'] = $start;
        }
        $list = $this->M_report->get_datatables($params);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $barang) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $barang->tanggal;
            $row[] = $barang->kode_barang;
            $row[] = $barang->nama_barang;
            $row[] = $barang->jumlah;
            $row[] = $barang->harga;
            $row[] = $barang->subtotal;
            $row[] = $barang->kasir;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_report->count_all($params),
            "recordsFiltered" => $this->M_report->count_filtered($params),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}
