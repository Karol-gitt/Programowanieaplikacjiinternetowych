<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function index()
	{
                if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()){
                    $this->ion_auth->logout();
                    redirect('auth/logout','refresh');
                }
                
                redirect('tests','refresh');
	}
        
}
