<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class userlog extends CI_Controller {

	function __construct(){
		parent::__construct();
		ob_start();
		error_reporting(0);
	}

	public function create_log(){
		//print_r($_SERVER); die;
		extract($_POST);
		//$userid=$this->input->post('userid');
		// Get IP address
		if( ($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
		$remote_addr = "REMOTE_ADDR_UNKNOWN";
		}

		// Get requested script
		if( ($request_uri = $_SERVER['HTTP_REFERER']) == '') {
		$request_uri = "REQUEST_URI_UNKNOWN";
		}
		$data=array('userid'=>$userID,'remote_addr'=>$remote_addr,'request_uri'=>$request_uri,'log_date'=>date('Y-m-d H:i:s'));
		$this->db->insert('udt_AU_UserUrlLogs',$data);	
	}
}