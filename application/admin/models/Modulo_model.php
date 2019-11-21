<?php

class Modulo_model extends Db_model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = 'modulo';
        $this->pKey = 'modulo';
        $this->setColumnas();
    }

    function crear($dataToInsert = null)
    {
        return $this->addElement(
            $dataToInsert,
            'Módulo creado con éxito. ',
            'Módulo creado con éxito. '
        );
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        return $this->updElement(
            $dataToUpdate,
            $id,
            'Módulo actualizado con éxito. ',
            'Módulo actualizado con éxito. '
        );
    }

    function find($filtros = [], $fetchMaster = false)
    {
        return $this->getElements(
            $filtros,
            'Consulta de modulos por filtro. ',
            $fetchMaster
        );
    }
}
