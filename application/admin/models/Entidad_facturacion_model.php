<?php

class Entidad_facturacion_model extends Db_model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = 'entidad_facturacion';
        $this->pKey = 'entidad_facturacion';
        $this->master = 'cliente_corporacion';
        $this->pKeyMaster = 'cliente_corporacion';
        $this->setColumnas();
    }

    public function validNit($nit)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $nit);
    }

    private function chkNitField($data)
    {
        if (array_key_exists('nit', $data)) {
            $data['nit'] = strtolower($this->validNit($data['nit']));
        }
        return $data;
    }

    private function chkEmail($data)
    {
        if (array_key_exists('correoe', $data)) {
            if (!filter_var($data['correoe'], FILTER_VALIDATE_EMAIL)) {
                $data['correoe'] = null;
            }
        }
    }

    function crear($dataToInsert = null)
    {
        $dataToInsert = $this->chkNitField($dataToInsert);
        $dataToInsert = $this->chkEmail($dataToInsert);
        return $this->addElement(
            $dataToInsert,
            'Entidad de facturación de la corporación de cliente creada con éxito.',
            'Entidad de facturación de la corporación de cliente creada con éxito.'
        );
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        $dataToUpdate = $this->chkNitField($dataToUpdate);
        $dataToUpdate = $this->chkEmail($dataToUpdate);
        return $this->updElement(
            $dataToUpdate,
            $id,
            'Entidad de facturación de la corporación de cliente actualizada con éxito. ',
            'Entidad de facturación de la corporación de cliente actualizada con éxito.'
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
