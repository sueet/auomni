<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller {

	 function __construct()
    {
        parent::__construct();
        
    }
	
	
	
	
	public function error_404()
	{		
		//die();
		$page=$this->uri->uri_string();
		log_message('error', '404 Page Not Found '.$page);
		$heading="404 Page Not Found";	
		$message="404 Page Not Found ".$page;
		getErrorMessage(1,$heading,$message);
		redirect(base_url().'error/view_error?type=PNF');
	}
	
	public function view_error()
	{
		$this->load->view('error/errors');
		
	}
	
	
	
}

/* End of file error.php */
/* Location: ./application/controllers/error.php */