<?php

class Usuario_model extends Db_model
{
    public function __construct()
    {
        parent::__construct();
        $this->tabla = 'usuario';
        $this->pKey = 'usuario';
        $this->setColumnas();
    }

    function logIn($credenciales = null)
    {
        if ($credenciales) {
            $dbusr = $this->db
                ->select('usuario, contrasenia, usrname, nombres, apellidos')
                ->from($this->tabla)
                ->where('usrname', $credenciales['usr'])
                ->where('debaja', 0)
                ->get()
                ->row();

            if (isset($dbusr)) {
                if (password_verify($credenciales['pwd'], $dbusr->contrasenia)) {
                    $tokenData = array(
                        'usuario' => $dbusr->usuario,
                        'usrname' => $credenciales['usr'],
                        'inicia' => date('Y-m-d H:i:s'),
                        'hasta' => date('Y-m-d H:i:s', strtotime('+12 hours'))
                    );
                    $this->Db_model->addBitacora(('Usuario loggeado con éxito. ' . json_encode($tokenData)), $dbusr->usuario);
                    return array(
                        'mensaje' => 'El usuario tiene acceso.',
                        'token' => AUTHORIZATION::generateToken($tokenData),
                        'usrname' => $dbusr->usrname,
                        'nombres' => $dbusr->nombres,
                        'apellidos' => $dbusr->apellidos
                    );
                } else {
                    $this->msgBitacora = 'El usuario ' . $credenciales['usr'] . ' o la contraseña son inválidos. Intente de nuevo, por favor.';
                    $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
                    return array(
                        'mensaje' => $this->msgBitacora,
                        'token' => null
                    );
                }
            } else {
                $this->msgBitacora = 'El usuario ' . $credenciales['usr'] . ' es inválido. Intente de nuevo, por favor.';
                $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
                return array(
                    'mensaje' => $this->msgBitacora,
                    'token' => null
                );
            }
        } else {
            $this->msgBitacora = 'Por favor envíe credenciales válidas. ' . (json_encode($credenciales));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => $this->msgBitacora,
                'token' => null
            );
        }
    }

    private function checkUserExists($usr)
    {
        $existe = -1;
        $dbusr = $this->db
            ->select('usuario')
            ->from($this->tabla)
            ->where('usrname', $usr)
            ->get()
            ->row();
        if (isset($dbusr)) {
            $existe = (int) $dbusr->usuario;
        }
        return $existe;
    }

    function crear($dataToInsert = null)
    {
        if ($dataToInsert) {
            $idusr = $this->checkUserExists($dataToInsert['usuario']);
            if ($idusr < 0) {
                if (array_key_exists('contrasenia', $dataToInsert)) {
                    $dataToInsert['contrasenia'] = password_hash($dataToInsert['contrasenia'], PASSWORD_BCRYPT, array('cost' => 12));
                }

                return $this->addElement(
                    $dataToInsert,
                    'Usuario creado con éxito. ',
                    'Usuario creado con éxito. '
                );
            } else {
                return array(
                    'mensaje' => 'Este usuario ya existe.',
                    'usuario' => $idusr
                );
            }
        } else {
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'usuario' => null
            );
        }
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        return $this->updElement(
            $dataToUpdate,
            $id,
            'Usuario actualizado con éxito. ',
            'Usuario actualizado con éxito. '
        );
    }

    function find($filtros = [], $fetchMaster = false)
    {
        return $this->getElements(
            $filtros,
            'Consulta de usuarios por filtro. ',
            $fetchMaster
        );
    }
}
