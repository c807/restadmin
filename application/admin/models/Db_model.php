<?php

class Db_model extends CI_Model
{
    public function getTableColumns($tblSchema = null, $tblName = null)
    {
        if (!$tblSchema || !$tblName) {
            return [];
        } else {
            $columnas = $this->db
                ->select('COLUMN_NAME')
                ->from('information_schema.columns')
                ->where("TABLE_SCHEMA = '$tblSchema'")
                ->where("TABLE_NAME = '$tblName'")
                ->order_by('ORDINAL_POSITION')
                ->get()
                ->result();
            if (isset($columnas)) {
                $cols = [];
                foreach ($columnas as $columna) {
                    $cols[] = $columna->COLUMN_NAME;
                }
                return $cols;
            } else {
                return [];
            }
        }
    }

    public function addBitacora($comentario = '', $usr = 0){
        $this->load->model('Bitacora_model');
        $this->db->insert('bitacora', ['comentario' => $comentario, 'usuario' => $usr]);
    }
}
