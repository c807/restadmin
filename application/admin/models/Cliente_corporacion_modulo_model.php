<?php

class Cliente_corporacion_modulo_model extends Db_model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = 'cliente_corporacion_modulo';
        $this->pKey = ['cliente_corporacion', 'modulo'];
        $this->master = 'modulo';
        $this->pKeyMaster = 'modulo';
        $this->setColumnas();
    }

    function crear($dataToInsert = null)
    {
        return $this->addElement(
            $dataToInsert,
            'El módulo de la corporación de cliente creada con éxito.',
            'El módulo de la corporación de cliente creada con éxito.'
        );
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        return $this->updElement(
            $dataToUpdate,
            $id,
            'El módulo de la corporación de cliente actualizado con éxito. ',
            'El módulo de la corporación de cliente actualizado con éxito.'
        );
    }

    function find($filtros = [], $fetchMaster = true)
    {
        return $this->getElements(
            $filtros,
            'Consulta de módulos de corporaciones de clientes por filtro.',
            $fetchMaster
        );
    }
}
