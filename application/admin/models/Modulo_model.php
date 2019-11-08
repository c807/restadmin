<?php

class Modulo_model extends CI_Model
{
    private $tabla = 'modulo';
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
            $this->msgBitacora = 'Modulo creado con éxito. ' . (json_encode($dataToInsert));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'Modulo creado con éxito.',
                'modulo' => $lastid
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToInsert));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'modulo' => null
            );
        }
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        if ($dataToUpdate) {
            $this->db->where('modulo', $id);
            $this->db->update($this->tabla, $dataToUpdate);
            $this->msgBitacora = 'Modulo actualizado con éxito. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'Modulo actualizado con éxito.',
                'modulo' => $id
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'modulo' => null
            );
        }
    }

    function findAll($debaja = 0)
    {
        $this->msgBitacora = 'El usuario consultó la lista de modulos.';
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
            $this->msgBitacora = 'Consulta de modulos por filtro. ' . (json_encode($filtros));
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
