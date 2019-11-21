<?php

class Db_model extends CI_Model
{
    public $tabla = null;
    public $pKey = null;
    public $master = null;
    public $pKeyMaster = null;
    public $msgBitacora = '';
    public $msgResponse = '';
    public $columnas = [];
    public $columnasMaster = [];
    public $idusuario = 0;

    public function __construct()
    {
        parent::__construct();
    }

    private function getTableColumns($tblSchema = null, $tblName = null)
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

    public function setColumnas()
    {
        if ($this->tabla) {
            $this->columnas = $this->getTableColumns($this->db->database, $this->tabla);
        }

        if ($this->master) {
            $this->columnasMaster = $this->getTableColumns($this->db->database, $this->master);
        }
    }

    public function addBitacora($comentario = '', $usr = 0)
    {
        $this->load->model('Bitacora_model');
        $this->db->insert('bitacora', ['comentario' => $comentario, 'usuario' => $usr]);
    }

    public function addElement($dataToInsert, $mensajeBitacora = '', $mensajeRespuesta = '')
    {
        if ($dataToInsert) {
            $this->db->insert($this->tabla, $dataToInsert);
            $lastid = $this->db->insert_id();
            $this->msgBitacora = "$mensajeBitacora" . (json_encode($dataToInsert));
            $this->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => $mensajeRespuesta,
                $this->pKey => $lastid
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToInsert));
            $this->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                $this->pKey => null
            );
        }
    }

    public function updElement($dataToUpdate, $id, $mensajeBitacora = '', $mensajeRespuesta = '')
    {
        if ($dataToUpdate) {
            $this->db->where($this->pKey, $id);
            $this->db->update($this->tabla, $dataToUpdate);
            $this->msgBitacora = "$mensajeBitacora" . (json_encode($dataToUpdate));
            $this->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => $mensajeRespuesta,
                $this->pKey => $id
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToUpdate));
            $this->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                $this->pKey => null
            );
        }
    }

    private function fillMaster($detalle)
    {
        if (isset($detalle)) {
            $cntDetalle = count($detalle);
            for ($i = 0; $i < $cntDetalle; $i++) {
                $idmaster = $detalle[$i]->{$this->pKeyMaster};
                $detalle[$i]->{$this->pKeyMaster} = $this->db
                    ->select(join(',', $this->columnasMaster))
                    ->from($this->master)
                    ->where($this->master, $idmaster)
                    ->get()
                    ->row();
            }
        }
        return $detalle;
    }

    public function getElements($filtros = [], $mensajeBitacora = '', $fetchMaster = true)
    {
        $this->msgBitacora = "$mensajeBitacora" . (json_encode($filtros));
        $this->addBitacora($this->msgBitacora, $this->idusuario);

        if ($filtros && count($filtros) > 0) {
            foreach ($filtros as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        $detalle = $this->db
            ->select(join(',', $this->columnas))
            ->from($this->tabla)
            ->get()
            ->result();

        return ($fetchMaster && $this->master) ? $this->fillMaster($detalle) : $detalle;
    }
}
