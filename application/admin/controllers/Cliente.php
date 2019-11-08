<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'application/admin/controllers/Restserver.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Allow-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

class Cliente extends Restserver
{
    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__construct();
        $this->load->model('Cliente_model');
        $this->Cliente_model->idusuario = isset($this->status_verification_request->usuario) ? $this->status_verification_request->usuario : 0;
    }

    public function clientes_get()
    {
        if ($this->status_verification_request) {
            $debaja = (int) $this->get('debaja');
            $this->response($this->Cliente_model->findAll($debaja));
        } else {
            $this->noAutorizado();
        }
    }

    public function cliente_get(){
        if($this->status_verification_request){
            $filtros = $this->getValidData($this->get(), $this->Cliente_model->columnas);
            $this->response($this->Cliente_model->find($filtros));
        } else {
            $this->noAutorizado();
        }
    }

    public function cliente_post()
    {
        if ($this->status_verification_request) {
            $datos = $this->getValidData($this->post(), $this->Cliente_model->columnas);
            $status = parent::HTTP_OK;
            $nuevo = $this->Cliente_model->crear($datos);
            $this->response($nuevo, $status);
        } else {
            $this->noAutorizado();
        }
    }

    public function cliente_put()
    {
        if ($this->status_verification_request) {
            $id = isset($_GET['cliente']) ? (int) $_GET['cliente'] : 0;
            $datos = $this->getValidData($this->put(), $this->Cliente_model->columnas);
            $status = parent::HTTP_OK;
            $nuevo = $this->Cliente_model->actualizar($id, $datos);
            $this->response($nuevo, $status);
        } else {
            $this->noAutorizado();
        }
    }

}
