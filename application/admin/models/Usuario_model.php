<?php

class Usuario_model extends CI_Model
{
    private $tabla = 'usuario';
    private $msgBitacora = '';
    public $columnas = [];
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

                $this->db->insert($this->tabla, $dataToInsert);
                $this->msgBitacora = 'Usuario creado con éxito. ' . (json_encode($dataToInsert));
                $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
                return array(
                    'mensaje' => 'Usuario creado con éxito.',
                    'usuario' => $this->db->insert_id()
                );
            } else {
                $this->msgBitacora = 'Este usuario ya existe. ' . (json_encode($dataToInsert));
                $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
                return array(
                    'mensaje' => 'Este usuario ya existe.',
                    'usuario' => $idusr
                );
            }
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToInsert));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'usuario' => null
            );
        }
    }

    function actualizar($id = 0, $dataToUpdate = null)
    {
        if ($dataToUpdate) {
            if (array_key_exists('contrasenia', $dataToUpdate)) {
                $dataToUpdate['contrasenia'] = password_hash($dataToUpdate['contrasenia'], PASSWORD_BCRYPT, array('cost' => 12));
            }
            $this->db->where('usuario', $id);
            $this->db->update($this->tabla, $dataToUpdate);
            $this->msgBitacora = 'Usuario actualizado con éxito. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'Usuario actualizado con éxito.',
                'usuario' => $id
            );
        } else {
            $this->msgBitacora = 'La información enviada no es correcta o está incompleta. ' . (json_encode($dataToUpdate));
            $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);
            return array(
                'mensaje' => 'La información enviada no es correcta o está incompleta.',
                'usuario' => null
            );
        }
    }

    function findAll($debaja = 0)
    {
        $this->msgBitacora = 'El usuario consultó la lista de usuarios.';
        $this->Db_model->addBitacora($this->msgBitacora, $this->idusuario);

        if ($debaja !== 3) {
            $this->db->where('debaja', $debaja);
        }

        return $this->db
            ->select(join(',', $this->columnas))
            ->from($this->tabla)
            ->get()
            ->result();
    }
}
