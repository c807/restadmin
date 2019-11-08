<?php
defined('BASEPATH') or exit('No direct script access allowed');
//use Restserver\Libraries\REST_Controller; //Esto lo comente para probar que funcionara y si funciono...
require FCPATH . 'application/vendor/chriskacerguis/codeigniter-restserver/application/libraries/REST_Controller.php';
require FCPATH . 'application/vendor/chriskacerguis/codeigniter-restserver/application/libraries/Format.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Allow-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

class Restserver extends REST_Controller
{
	public $status_verification_request = false;

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/Guatemala');
		$this->load->helper(['jwt', 'authorization']);
		$this->status_verification_request = $this->verify_request();
	}

	public function test_get()
	{
		$tokenData = 'Hola mundo!';
		$token = AUTHORIZATION::generateToken($tokenData);
		$status = parent::HTTP_OK;
		$response = ['status' => $status, 'token' => $token];
		$this->response($response, $status);
	}

	public function test_post()
	{
		// Call the verification method and store the return value in the variable
		$data = $this->verify_request();
		// Send the return data as reponse
		$status = parent::HTTP_OK;
		$response = ['status' => $status, 'data' => $data];
		$this->response($response, $status);
	}

	private function verify_request()
	{
		$headers = $this->input->request_headers();

		if (array_key_exists('Authorization', $headers)) {
			$token = $headers['Authorization'];
		} else {
			return false;
		}

		try {
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				return false;
			} else {
				$now = date('Y-m-d H:i:s');
				if ($now <= $data->hasta) {
					return $data;
				} else {
					$this->noAutorizado('El token ya se venció. Debe loggearse de nuevo, por favor.');
				}
			}
		} catch (Exception $e) {
			$this->noAutorizado();
		}
	}

	public function noAutorizado($msg = '¡Acceso no autorizado!')
	{
		$status = parent::HTTP_OK;
		$response = ['status' => $status, 'mensaje' => $msg, 'valido' => false];
		$this->response($response, $status);
		exit();
	}

	public function getValidData($data, $columnas)
	{
		$datos = [];
		foreach ($data as $key => $value) {
			if (in_array($key, $columnas)) {
				$datos[$key] = $value;
			}
		}
		return $datos;
	}
}
