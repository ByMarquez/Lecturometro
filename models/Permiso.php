<?php
namespace Model;

class Permiso extends ActiveRecord
{
    protected static $tabla = "permisos";
    protected static $columnasDB = ["id", "registrar_libro", "registrar_usuarios", "id_usuario"];

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? null;
        $this->registrar_libro = $args["registrar_libro"] ?? "";
        $this->registrar_usuarios = $args["registrar_usuarios"] ?? "";
        $this->id_usuario = $args["id_usuario"] ?? "";
    }

    //Validar campos de login
    public function validarPermiso()
    {
        if (!$this->id_usuario) {
            self::$alertas["error"][] = "Error en el ID del Usuario";
        } else {
            if (!is_numeric($this->id_usuario)) {
                self::$alertas["error"][] = "Error en el ID del Usuario NOT NUM";
            }
        }

        if (!isset($this->registrar_libro)) {
            self::$alertas["error"][] = "Se debe seleccionar un valor en los permisos";
        } else {
            if (!is_numeric($this->registrar_libro)) {
                self::$alertas["error"][] = "Campos vacios";
            } else {
                if (intval($this->registrar_libro) !==0 & intval($this->registrar_libro) !==1) {
                    self::$alertas["error"][] = "Valor elegido no permitido";
                }
            }
        }
        if (!isset($this->registrar_usuarios)) {
            self::$alertas["error"][] = "Se debe seleccionar un valor en los permisos";
        } else {
            if (!is_numeric($this->registrar_usuarios)) {
                self::$alertas["error"][] = "Campos vacios";
            } else {
                if (intval($this->registrar_usuarios) !== 0 & intval($this->registrar_usuarios) !== 1) {
                    self::$alertas["error"][] = "Valor elegido no permitido";
                }
            }
        }
        return self::$alertas;
    }

    public function validarEliminar()
    {
        if (!$this->id_usuario) {
            self::$alertas["error"][] = "Error en el ID del Usuario";
        } else {
            if (!is_numeric($this->id_usuario)) {
                self::$alertas["error"][] = "Error en el ID del Usuario NOT NUM";
            }
        }

        return self::$alertas;
    }

    public function validarActualizar()
    {
        if (!$this->id) {
            self::$alertas["error"][] = "Error en el ID del Usuario";
        } else {
            if (!is_numeric($this->id)) {
                self::$alertas["error"][] = "Error en el ID del Usuario NOT NUM";
            }
        }
        if (!$this->id_usuario) {
            self::$alertas["error"][] = "Error en el ID del Usuario";
        } else {
            if (!is_numeric($this->id_usuario)) {
                self::$alertas["error"][] = "Error en el ID del Usuario NOT NUM";
            }
        }

        if (!isset($this->registrar_libro)) {
            self::$alertas["error"][] = "Se debe seleccionar un valor en los permisos";
        } else {
            if (!is_numeric($this->registrar_libro)) {
                self::$alertas["error"][] = "Campos vacios";
            } else {
                if (intval($this->registrar_libro) !==0 & intval($this->registrar_libro) !==1) {
                    self::$alertas["error"][] = "Valor elegido no permitido";
                }
            }
        }
        if (!isset($this->registrar_usuarios)) {
            self::$alertas["error"][] = "Se debe seleccionar un valor en los permisos";
        } else {
            if (!is_numeric($this->registrar_usuarios)) {
                self::$alertas["error"][] = "Campos vacios";
            } else {
                if (intval($this->registrar_usuarios) !== 0 & intval($this->registrar_usuarios) !== 1) {
                    self::$alertas["error"][] = "Valor elegido no permitido";
                }
            }
        }
        return self::$alertas;
    }
    public function set_id_usuario($id_usuario){
        if(isset($id_usuario)){
            $this->id_usuario = $id_usuario;
        }
    }
}