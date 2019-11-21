<?php

class Cliente_model extends Db_model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = 'cliente';
        $this->pKey = 'cliente';
        $this->setColumnas();
    }

    function crear($dataToInsert = null)
    {
        return $this->addElement(
            $dataToInsert,
            'Cliente creado con éxito. ',
            'Cliente creado con éxito. '
        );
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        return $this->updElement(
            $dataToUpdate,
            $id,
            'Cliente actualizado con éxito. ',
            'Cliente actualizado con éxito. '
        );
    }

    function find($filtros = [], $fetchMaster = false)
    {
        return $this->getElements(
            $filtros,
            'Consulta de clientes por filtro. ',
            $fetchMaster
        );
    }
}
