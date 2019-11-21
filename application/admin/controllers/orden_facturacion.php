<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'application/admin/controllers/Restserver.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Allow-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

class Orden_facturacion extends Restserver
{
    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__construct();
        $this->load->model('Orden_facturacion_model');
        $this->Orden_facturacion_model->idusuario = isset($this->status_verification_request->usuario) ? $this->status_verification_request->usuario : 0;
    }

    public function ordenes_facturacion_get()
    {
        if ($this->status_verification_request) {
            $this->response($this->Orden_facturacion_model->find());
        } else {
            $this->noAutorizado();
        }
    }

    public function orden_facturacion_get(){
        if($this->status_verification_request){
            $filtros = $this->getValidData($this->get(), $this->Orden_facturacion_model->columnas);
            $this->response($this->Orden_facturacion_model->find($filtros));
        } else {
            $this->noAutorizado();
        }
    }

    public function orden_facturacion_post()
    {
        if ($this->status_verification_request) {
            $datos = $this->getValidData($this->post(), $this->Orden_facturacion_model->columnas);
            $status = parent::HTTP_OK;
            $nuevo = $this->Orden_facturacion_model->crear($datos);
            $this->response($nuevo, $status);
        } else {
            $this->noAutorizado();
        }
    }

    public function orden_facturacion_put()
    {
        if ($this->status_verification_request) {
            $id = $this->getGetParam($this->Orden_facturacion_model->pKey, 0);
            $datos = $this->getValidData($this->put(), $this->Orden_facturacion_model->columnas);
            $status = parent::HTTP_OK;
            $nuevo = $this->Orden_facturacion_model->actualizar($id, $datos);
            $this->response($nuevo, $status);
        } else {
            $this->noAutorizado();
        }
    }

}
