<?php

class Cliente_corporacion_model extends CI_Model
{
    private $tabla = 'cliente_corporacion';
    private $master = 'cliente';
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
            $this->load->library('Uuid');
            $dataToInsert['llave'] = $this->uuid->v4();
            $this->db->insert($this->tabla, $dataToInsert);
            $lastid = $this->db->insert_id();
            $this->msgBitacora = 'Corporación de cliente creada con éxito. ' . (json_encode($dataToInsert));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'Corporación de cliente creada con éxito.',
                'cliente_corporacion' => $lastid
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToInsert));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'cliente_corporacion' => null
            );
        }
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        if ($dataToUpdate) {
            $this->db->where('cliente_corporacion', $id);
            $this->db->update($this->tabla, $dataToUpdate);
            $this->msgBitacora = 'Corporación de cliente actualizada con éxito. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'Corporación de cliente actualizada con éxito.',
                'cliente_corporacion' => $id
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'cliente_corporacion' => null
            );
        }
    }

    private function fillMaster($corporaciones)
    {
        if (isset($corporaciones)) {
            $cntCorporaciones = count($corporaciones);
            for ($i = 0; $i < $cntCorporaciones; $i++) {
                $idcliente = $corporaciones[$i]->cliente;
                $corporaciones[$i]->cliente = $this->db
                    ->select(join(',', $this->columnasMaster))
                    ->from($this->master)
                    ->where($this->master, $idcliente)
                    ->get()
                    ->row();
            }
        }
        return $corporaciones;
    }

    function findAll($debaja = 0)
    {
        $this->msgBitacora = 'El usuario consultó la lista de corporaciones de clientes.';
        $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);

        if ($debaja !== 3) {
            $this->db->where('debaja', $debaja);
        }

        $corporaciones = $this->db
            ->select(join(',', $this->columnas))
            ->from($this->tabla)
            ->get()
            ->result();

        return $this->fillMaster($corporaciones);
    }

    function find($filtros = [])
    {
        if (!$filtros || count($filtros) == 0) {
            return $this->findAll();
        } else {
            $this->msgBitacora = 'Consulta de corporaciones de clientes por filtro. ' . (json_encode($filtros));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);

            foreach ($filtros as $key => $value) {
                $this->db->where($key, $value);
            }

            $corporaciones = $this->db
                ->select(join(',', $this->columnas))
                ->from($this->tabla)
                ->get()
                ->result();

            return $this->fillMaster($corporaciones);
        }
    }
}
