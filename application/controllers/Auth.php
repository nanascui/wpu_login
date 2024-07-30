<?php
defined('BASEPATH') or exit('No direct script access allowed');

#[\AllowDynamicProperties]
class Auth extends CI_Controller
{
    public $form_validation;
    public $session;
    public $input;
    public $db;

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('form_validation');
        // $this->load->library('session');
        // $this->load->database();
    }

    public function index()
    //login//
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|
        required|vali_email');
        $this->form_validation->set_rules('password', 'Paswordd', 'required|trim|
        required|vali_email');


        if ($this->form_validation->run() == false) {
            $data['title'] = 'Login Page';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            //validation sukses//
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        var_dump($user);
        die;
    }

    public function registration()
    {
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'this email has already register'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[2]|matches[password2]', [
            'matches' => 'password dont match!',
            'min_length' => 'password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'WPU User Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'date_created' => time()
            ];

            $this->db->insert('user', $data);
            $this->session->set_flashdata('message', '<div class="alert
            alert-success" role="alert">Congratulation! your account has been created. Please Login</div>');
            redirect('auth');
        }
    }
}
