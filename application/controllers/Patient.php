<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends CI_Controller {

	/**
	 * Patient Page for this controller.
	 *
	 *
	 */
	public function index()
	{
		$this->load->view('patient');
	}
}
