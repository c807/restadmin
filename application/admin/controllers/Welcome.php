<?php
defined('BASEPATH') or exit('No direct script access allowed');
//use Restserver\Libraries\REST_Controller;
use REST_Controller;
//require APPPATH . '/libraries/REST_Controller.php';
//require APPPATH . '/libraries/Format.php';

class Welcome extends CI_Controller
{
	use REST_Controller {
		REST_Controller::__construct as private __resTraitConstruct;
	}

	public function index_get()
	{
		$this->response(['mensaje' => 'Esta es una prueba']);
	}
}
