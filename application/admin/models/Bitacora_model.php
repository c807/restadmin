<?php

class Bitacora_model extends CI_Model
{
    private $tabla = 'bitacora';
    public $columnas = [];

    public function __construct()
    {
        parent::__construct();
        $this->setColumnas();
    }

    private function setColumnas()
    {
        $this->load->model('Db_model');
        $this->columnas = $this->Db_model->getTableColumns($this->db->database, $this->tabla);
    }

    function crear($dataToInsert = null)
    {
        if ($dataToInsert) {
            $this->db->insert($this->tabla, $dataToInsert);
            return array(
                'mensaje' => 'Bitácora creada con éxito.',
                'bitacora' => $this->db->insert_id()
            );
        }
    }

    function findAll()
    {
        return $this->db
            ->select(join(',', $this->columnas))
            ->from($this->tabla)
            ->get()
            ->result();
    }
}
