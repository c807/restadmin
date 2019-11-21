<?php

class Cliente_corporacion_model extends Db_model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = 'cliente_corporacion';
        $this->pKey = 'cliente_corporacion';
        $this->master = 'cliente';
        $this->pKeyMaster = 'cliente';
        $this->setColumnas();
    }

    function crear($dataToInsert = null)
    {
        $this->load->library('Uuid');
        $dataToInsert['llave'] = $this->uuid->v4();
        return $this->addElement(
            $dataToInsert,
            'Corporación de cliente creado con éxito. ',
            'Corporación de cliente creado con éxito. '
        );
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        return $this->updElement(
            $dataToUpdate,
            $id,
            'Corporación de cliente actualizada con éxito. ',
            'Corporación de cliente actualizada con éxito. '
        );
    }

    function find($filtros = [], $fetchMaster = true)
    {
        return $this->getElements(
            $filtros,
            'Consulta de entidades de facturación de corporaciones de clientes por filtro.',
            $fetchMaster
        );
    }
}
