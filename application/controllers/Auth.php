<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Login_model');
		$this->load->library('user_agent');
	}
	public function index()
	{
		$session = $this->session->userdata('IS_LOGIN');

		if ($session == FALSE) {
			$this->load->view('login');
		} else {
			redirect('Welcome');
		}
	}
	public function proses_login()
	{
		//inisiasi
		$message['is_error'] = true;
		$message['error_msg'] = 'Hey Boss, its already showing an error before anything even starts. The programmer is really bad at this';
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			$message['is_error']  = true;
			$message['error_msg'] = validation_errors();
		} else {

			$username = $this->input->post('username');
			$password = ($this->input->post('password'));

			$check_data = $this->Login_model->check_login($username, $password);
			if ($check_data) {
				$row = $check_data;

				$sess_data = array(
					'IS_LOGIN' => true,
					'id' => $row->id,
					'username' => $row->username,
					'nama' => $row->nama,
					'level_name' => $row->level_name,
				);
				$this->session->set_userdata($sess_data);
				$message['is_error']  = false;
				$message['succes_msg'] = "success";
				$message['error_msg'] = "";
				redirect('Welcome');
				// $message['redirect']   = site_url('Welcome/index');
			} else {
				$message['is_error']  = true;
				$message['error_msg'] = "Sorry, the password or username is incorrect, please try again";
				redirect('auth');
			}
		}
		echo json_encode($message);
	}
	public function Logout()
	{
		$this->session->sess_destroy();
		redirect('auth', 'refresh');
	}
}
