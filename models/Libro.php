<?php
namespace Model;

class Libro extends ActiveRecord
{
    protected static $tabla = "libros";
    protected static $columnasDB = ["id", "titulo", "genero", "autor", "paginas", "imagen"];

    public function __construct($args = [])
    {
        $this->id = $args["isbn"] ?? "";
        $this->titulo = $args["titulo"] ?? "";
        $this->genero = $args["genero"] ?? "";
        $this->autor = $args["autor"] ?? "";
        $this->paginas = $args["paginas"] ?? "";
        $this->imagen = $args["imagen"] ?? "";
    }
    public function setImagen($imagen)
    {
        //asignar al atributo imagen el nombre de la imagen para la db
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    public function validarRegistrar()
    {
        //ISBN existe
        if (!$this->id) {
            self::$alertas["error"][] = "El ISBN es Obligatorio";
        } else {
            //ISBN numerico
            if (!filter_var($this->id, FILTER_VALIDATE_INT)) {
                self::$alertas["error"][] = "El ISBN del libro debe ser numerico sin decimales";
            } else {
                //ISBN no negativo
                if (intval($this->id) < 0) {
                    self::$alertas["error"][] = "Error en el ISBN";
                } else {
                    //ISBN con 13 caracteres
                    if (strlen($this->id) !== 13) {
                        self::$alertas["error"][] = "El ISBN debe contener exactamente 13 dígitos";
                    }
                }
            }
        }

        if (!$this->titulo) {
            self::$alertas["error"][] = "El Título es Obligatorio";
        } else {
            //verificar que no haya mas de 80 caracteres
            if (strlen($this->titulo) > 80) {
                self::$alertas["error"][] = "El Título es Muy Extenso";
            }
        }

        if (!$this->genero) {
            self::$alertas["error"][] = "El genero es Obligatorio";
        } else {
            //verificado que el genero no tenga mas de 30 caracteres
            if (strlen($this->genero) > 30) {
                self::$alertas["error"][] = "El tipo de genero es Muy Extenso";
            }
        }

        if (!$this->autor) {
            self::$alertas["error"][] = "El autor es Obligatorio";
        } else {
            //verificado que el genero no tenga mas de 30 caracteres
            if (strlen($this->autor) > 50) {
                self::$alertas["error"][] = "El nombre del autor es Muy Extenso";
            }
        }

        //PAGINAS existen
        if (!$this->paginas) {
            self::$alertas["error"][] = "Las paginas del libro son Obligatorias";
        } else {
            //PAGINAS son numero sin decimales
            if (!filter_var($this->paginas, FILTER_VALIDATE_INT)) {
                self::$alertas["error"][] = "Las paginas del libro deben ser fromato numerico sin decimales";
            } else {
                //paginas no negativas ni mayores a 999,999
                if (intval($this->paginas) < 0 || strlen($this->paginas) > 6) {
                    self::$alertas["error"][] = "Error el numero de paginas";
                }
            }
        }
        return self::$alertas;
    }

}