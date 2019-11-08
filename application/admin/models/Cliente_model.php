<?php

class Cliente_model extends CI_Model
{
    private $tabla = 'cliente';
    private $msgBitacora = '';
    public $columnas = [];
    public $idusuario = 0;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Db_model');
        $this->setColumnas();
    }

    private function setColumnas()
    {
        $this->columnas = $this->Db_model->getTableColumns($this->db->database, $this->tabla);
    }

    function crear($dataToInsert = null)
    {
        if ($dataToInsert) {
            $this->db->insert($this->tabla, $dataToInsert);
            $lastid = $this->db->insert_id();
            $this->msgBitacora = 'Cliente creado con éxito. ' . (json_encode($dataToInsert));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'Cliente creado con éxito.',
                'cliente' => $lastid
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToInsert));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'cliente' => null
            );
        }
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        if ($dataToUpdate) {
            $this->db->where('cliente', $id);
            $this->db->update($this->tabla, $dataToUpdate);
            $this->msgBitacora = 'Cliente actualizado con éxito. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'Cliente actualizado con éxito.',
                'cliente' => $id
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'cliente' => null
            );
        }
    }

    function findAll($debaja = 0)
    {
        $this->msgBitacora = 'El usuario consultó la lista de clientes.';
        $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);

        if ($debaja !== 3) {
            $this->db->where('debaja', $debaja);
        }

        return $this->db
            ->select(join(',', $this->columnas))
            ->from($this->tabla)
            ->get()
            ->result();
    }

    function find($filtros = [])
    {
        if (!$filtros || count($filtros) == 0) {
            return $this->findAll();
        } else {
            $this->msgBitacora = 'Consulta de clientes por filtro. ' . (json_encode($filtros));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);

            foreach ($filtros as $key => $value) {
                $this->db->where($key, $value);
            }

            return $this->db
                ->select(join(',', $this->columnas))
                ->from($this->tabla)
                ->get()
                ->result();
        }
    }
}
