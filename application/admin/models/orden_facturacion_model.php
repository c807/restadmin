<?php

class Orden_facturacion_model extends Db_model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = 'orden_facturacion';
        $this->pKey = 'orden_facturacion';
        $this->master = 'entidad_facturacion';
        $this->pKeyMaster = 'entidad_facturacion';
        $this->setColumnas();
    }

    function crear($dataToInsert = null)
    {
        return $this->addElement(
            $dataToInsert,
            'Orden de facturación creada con éxito.',
            'Orden de facturación creada con éxito.'
        );
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        return $this->updElement(
            $dataToUpdate,
            $id,
            'Orden de facturación actualizada con éxito. ',
            'Orden de facturación actualizada con éxito.'
        );
    }

    function find($filtros = [], $fetchMaster = true)
    {
        return $this->getElements(
            $filtros,
            'Consulta de órdenes de facturación por filtro.',
            $fetchMaster
        );
    }
}
