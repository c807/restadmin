<?php

class Bitacora_model extends Db_model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = 'bitacora';
        $this->pKey = 'bitacora';
        $this->setColumnas();
    }

    function crear($dataToInsert = null)
    {
        return $this->addElement(
            $dataToInsert,
            'Bitácora creada con éxito.',
            'Bitácora creada con éxito.'
        );
    }

    function find($filtros = [], $fetchMaster = false)
    {
        return $this->getElements(
            $filtros,
            'Consulta de bitácora por filtro.',
            $fetchMaster
        );
    }
}
