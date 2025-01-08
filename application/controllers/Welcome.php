<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('IS_LOGIN') == false) {
            redirect('auth');
        }
    }


    public function index()
    {
        $data = [
            'title_form' => 'Dahsboard',
        ];
        $this->load->view('includes/header');
        $this->load->view('dashboard', $data);
        $this->load->view('includes/footer');
    }
}
