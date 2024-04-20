<?php
namespace Model;

class Lectura extends ActiveRecord
{
    protected static $tabla = "lecturas";
    protected static $columnasDB = ["id", "paginas_leidas", "estatus", "usuarios_id", "libros_id"];

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? "";
        $this->paginas_leidas = $args["paginas_leidas"] ?? "";
        $this->estatus = $args["estatus"] ?? "";
        $this->usuarios_id = $args["usuarios_id"] ?? "";
        $this->libros_id = $args["libros_id"] ?? "";
    }

    public function validarRegistrar()
    {
        //Id del libro existe
        if (!$this->libros_id) {
            self::$alertas["error"][] = "No hay libro";
        }else{
            if (!is_numeric($this->libros_id)) {
                self::$alertas["error"][] = "No es un numero";
            }else{
                if (strlen($this->libros_id)!==13) {
                    self::$alertas["error"][] = "Hay mas de 13 caracteres";
                }
            }
        } 
        return self::$alertas;
    }

    public function validarActualizar()
    {            
        //Id del libro existe
        if (!$this->libros_id) {
            self::$alertas["error"][] = "No hay libro";
        }else{
            if (!is_numeric($this->libros_id)) {
                self::$alertas["error"][] = "El ISBN debe ser numérico";
            }else{
                if (strlen($this->libros_id)!==13) {
                    self::$alertas["error"][] = "No Hay 13 caracteres";
                }
            }
        } 
        //paginas_leidas
        if (!$this->paginas_leidas) {
            self::$alertas["error"][] = "No hay paginas leídas";
        }else{
            if (!is_numeric($this->paginas_leidas)) {
                self::$alertas["error"][] = "El numero de paginas debe ser numérico";
            }else{
                if (strlen($this->paginas_leidas)>3) {
                    self::$alertas["error"][] = "Hay muchos dígitos en el número de paginas";
                }else{
                    if (intval($this->paginas_leidas)<0) {
                        self::$alertas["error"][] = "Hay paginas negativas";
                    }
                }
            }
        }
        if (!$this->id) {
            self::$alertas["error"][] = "No se identifica libro";
        }
        if (!$this->usuarios_id) {
            self::$alertas["error"][] = "No se identifica al usuario";
        }
        if (!$this->usuarios_id) {
            self::$alertas["error"][] = "Error en el estatus";
        }
        return self::$alertas;
    }
    public function validarpaginas_leidas($paginas_faltantes_por_leer)
    {
        //paginas_leidas del input < paginas faltantes
        if (intval($this->paginas_leidas) > intval($paginas_faltantes_por_leer)) {
            self::$alertas["error"][] = "El número de páginas ingresado es mayor a las que te falta por leer";
        }
        return self::$alertas;
    }

    public function set_libros_id($libros_id){
        if(isset($libros_id)){
            $this->libros_id = $libros_id;
        }
    }
    public function set_estatus($estatus){
        if(isset($estatus)){
            $this->estatus = $estatus;
        }
    }
    public function set_paginas_leidas($paginas_leidas){
        if(isset($paginas_leidas)){
            $this->paginas_leidas = $paginas_leidas;
        }
    }
    public function set_usuarios_id($usuarios_id){
        if(isset($usuarios_id)){
            $this->usuarios_id = $usuarios_id;
        }
    }
    public function set_id($id){
        if(isset($id)){
            $this->id = $id;
        }
    }

}