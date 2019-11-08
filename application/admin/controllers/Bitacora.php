<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'application/admin/controllers/Restserver.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Allow-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

class Bitacora extends Restserver
{
    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__construct();
        $this->load->model('Bitacora_model');
    }

    public function bitacora_get()
    {
        if ($this->status_verification_request) {
            $this->response($this->Bitacora_model->findAll());
        } else {
            $this->noAutorizado();
        }
    }

    public function bitacora_post()
    {
        if ($this->status_verification_request) {
            $datos = $this->getValidData($this->post(), $this->Bitacora_model->columnas);
            $datos['usuario'] = $this->status_verification_request->usuario;
            $status = parent::HTTP_OK;
            $nuevo = $this->Bitacora_model->crear($datos);
            $this->response($nuevo, $status);
        } else {
            $this->noAutorizado();
        }
    }
}
