<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    public function index() {

        $this->form_validation->set_rules('test_code', 'PODAJ KOD TESTU', 'required');

        $this->data['message'] = '';
        $this->data['error_message'] = '';
                
        $this->data['test_attempt'] = json_decode(get_cookie('test_attempt'));
        
        if(!empty($this->data['test_attempt'])){
            $this->data['test'] = $this->Tests_model->test($this->data['test_attempt']->tests_id)->row();
            $this->session->set_userdata('attempt_id',$this->data['test_attempt']->id);
        }
               
        if ($this->form_validation->run() == TRUE) {
            
            $test = $this->Tests_model->get_test($this->input->post('test_code'))->row();
            
            if($test){
                redirect('test/start_test/'.$test->id,'refresh');
            }else{
                $this->data['error_message'] = "Nie ma takiego testu.";
            }
            
        }else{
            $this->data['error_message'] = validation_errors();
        }

        $this->lang->load('auth');

        $this->load->view('partials/header');
        $this->load->view('partials/menu');
        $this->load->view('welcome_message',$this->data);
        $this->load->view('partials/footer');
    }

}
