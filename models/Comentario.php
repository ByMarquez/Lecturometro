<?php
namespace Model;

class comentario extends ActiveRecord
{
    protected static $tabla = "comentarios";
    protected static $columnasDB = ["id", "mostrar", "fecha", "comentario", "usuarios_id", "libros_id"];

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? "";
        $this->mostrar = $args["mostrar"] ?? "";
        $this->fecha = $args["fecha"] ?? "";
        $this->comentario = $args["comentario"] ?? "";
        $this->usuarios_id = $args["usuarios_id"] ?? "";
        $this->libros_id = $args["libros_id"] ?? "";
    }

    public function validarComentario()
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
        if (!$this->usuarios_id) {
            self::$alertas["error"][] = "Error al cargar identificar Usuario";
        }else{
            if (!is_numeric($this->usuarios_id)) {
                self::$alertas["error"][] = "Error en el Usuario";
            }
        }
        if (!$this->comentario) {
            self::$alertas["error"][] = "No hay comentario";
        }else{
            if (strlen($this->comentario)<10) {
                self::$alertas["error"][] = "El comentario es demasiado corto";
            }else{
                if (strlen($this->comentario)>280) {
                    self::$alertas["error"][] = "El comentario es demasiado largo";
                }
            }
        }
        if (!$this->fecha) {
            self::$alertas["error"][] = "No se identifica la fecha del comentario";
        }
        if (!$this->mostrar) {
            self::$alertas["error"][] = "No se especifica si el comentario se mostrara";
        }
        return self::$alertas;
    }
    

    
    public function setMostrar($mostrar){
        if(isset($mostrar)){
            $this->mostrar = $mostrar;
        }
    }
    public function setFecha($fecha){
        if(isset($fecha)){
            $this->fecha= $fecha;
        }
    }
    public function set_usuarios_id($usuarios_id){
        if(isset($usuarios_id)){
            $this->usuarios_id = $usuarios_id;
        }
    }
    public function set_libros_id($libros_id){
        if(isset($libros_id)){
            $this->libros_id = $libros_id;
        }
    }

}