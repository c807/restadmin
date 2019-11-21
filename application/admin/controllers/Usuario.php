<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'application/admin/controllers/Restserver.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Allow-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');

class Usuario extends Restserver
{
    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__construct();
        $this->load->model('Usuario_model');
        $this->Usuario_model->idusuario = isset($this->status_verification_request->usuario) ? $this->status_verification_request->usuario : 0;
    }

    public function login_post()
    {
        $credenciales = array(
            'usr' => $this->post('usr'),
            'pwd' => $this->post('pwd')
        );

        $status = parent::HTTP_OK;
        $logged = $this->Usuario_model->logIn($credenciales);
        $this->response($logged, $status);
    }

    public function usuarios_get()
    {
        if ($this->status_verification_request) {
            $this->response($this->Usuario_model->find());
        } else {
            $this->noAutorizado();
        }
    }

    public function usuario_post()
    {
        if ($this->status_verification_request) {
            $datos = $this->getValidData($this->post(), $this->Usuario_model->columnas);
            $status = parent::HTTP_OK;
            $nuevo = $this->Usuario_model->crear($datos);
            $this->response($nuevo, $status);
        } else {
            $this->noAutorizado();
        }
    }

    public function usuario_put()
    {
        if ($this->status_verification_request) {
            $id = $this->getGetParam($this->Usuario_model->pKey, 0);
            $datos = $this->getValidData($this->put(), $this->Usuario_model->columnas);
            $status = parent::HTTP_OK;
            $nuevo = $this->Usuario_model->actualizar($id, $datos);
            $this->response($nuevo, $status);
        } else {
            $this->noAutorizado();
        }
    }

    public function checktoken_get()
    {
        $this->response(['valido' => $this->status_verification_request ? true : false]);
    }
}
