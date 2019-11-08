<?php

class Entidad_facturacion_model extends CI_Model
{
    private $tabla = 'entidad_facturacion';
    private $master = 'cliente_corporacion';
    private $msgBitacora = '';
    public $columnas = [];
    public $columnasMaster = [];
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
        $this->columnasMaster = $this->Db_model->getTableColumns($this->db->database, $this->master);
    }

    function crear($dataToInsert = null)
    {
        if ($dataToInsert) {
            $this->db->insert($this->tabla, $dataToInsert);
            $lastid = $this->db->insert_id();
            $this->msgBitacora = 'Entidad de facturación de la corporación de cliente creada con éxito. ' . (json_encode($dataToInsert));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'Entidad de facturación de la corporación de cliente creada con éxito.',
                'entidad_facturacion' => $lastid
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToInsert));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'entidad_facturacion' => null
            );
        }
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        if ($dataToUpdate) {
            $this->db->where('entidad_facturacion', $id);
            $this->db->update($this->tabla, $dataToUpdate);
            $this->msgBitacora = 'Entidad de facturación de la corporación de cliente actualizada con éxito. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'Entidad de facturación de la corporación de cliente actualizada con éxito.',
                'entidad_facturacion' => $id
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'entidad_facturacion' => null
            );
        }
    }

    private function fillMaster($detalle)
    {
        if (isset($detalle)) {
            $cntDetalle = count($detalle);
            for ($i = 0; $i < $cntDetalle; $i++) {
                $idmaster = $detalle[$i]->cliente_corporacion;
                $detalle[$i]->cliente_corporacion = $this->db
                    ->select(join(',', $this->columnasMaster))
                    ->from($this->master)
                    ->where($this->master, $idmaster)
                    ->get()
                    ->row();
            }
        }
        return $detalle;
    }

    function findAll($debaja = 0)
    {
        $this->msgBitacora = 'El usuario consultó la lista de entidades de facturación de corporaciones de clientes.';
        $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);

        if ($debaja !== 3) {
            $this->db->where('debaja', $debaja);
        }

        $detalle = $this->db
            ->select(join(',', $this->columnas))
            ->from($this->tabla)
            ->get()
            ->result();

        return $this->fillMaster($detalle);
    }

    function find($filtros = [])
    {
        if (!$filtros || count($filtros) == 0) {
            return $this->findAll();
        } else {
            $this->msgBitacora = 'Consulta de entidades de facturación de corporaciones de clientes por filtro. ' . (json_encode($filtros));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);

            foreach ($filtros as $key => $value) {
                $this->db->where($key, $value);
            }

            $detalle = $this->db
                ->select(join(',', $this->columnas))
                ->from($this->tabla)
                ->get()
                ->result();

            return $this->fillMaster($detalle);
        }
    }
}
