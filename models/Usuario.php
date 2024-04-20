<?php 
namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id","nombre","correo","password","token"];

    public function __construct($args = []){
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->correo = $args["correo"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->password2 = $args["password2"] ?? "";
        $this->token = $args["token"] ?? "";
    }

    //Validar campos de login
    public function validarLogin(){
        if(!$this->correo){
            self::$alertas["error"][] = "El Correo Electronico es Obligatorio";
        }
        if(!filter_var($this->correo, FILTER_VALIDATE_EMAIL)){
            self::$alertas["error"][] = "El Correo Electronico no es Valido";
        }
        if(!$this->password){
            self::$alertas["error"][] = "La Contraseña es Obligatoria";
        }
        return self::$alertas;
    }
    //validar los campos del formulario para crear nueva cuenta
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas["error"][] = "El nombre es Obligatorio";
        }else{
            if(strlen($this->nombre)>50){
                self::$alertas["error"][] = "El nombre es muy largo";
            }
        }
        if(!$this->correo){
            self::$alertas["error"][] = "El Correo Electronico es Obligatorio";
        }else{
            if(strlen($this->correo)>50){
                self::$alertas["error"][] = "El correo es muy largo";
            }
        }
        if(!$this->password){
            self::$alertas["error"][] = "La Contraseña es Obligatoria";
        }else{
            if(strlen($this->password)<6){
                self::$alertas["error"][] = "La Contraseña debe contener al menos 6 Caracteres";
            }else{
                if(strlen($this->password)>50){
                    self::$alertas["error"][] = "La Contraseña es muy larga";
                }
            }
        }
        if($this->password !== $this->password2){
            self::$alertas["error"][] = "Las Contraseñas son diferentes";
        }
        return self::$alertas;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function validarCorreo(){
        if(!$this->correo){
            self::$alertas["error"][] = "El Correo Electronico es Obligatorio";
        }
        if(!filter_var($this->correo, FILTER_VALIDATE_EMAIL)){
            self::$alertas["error"][] = "El Correo Electronico no es Valido";
        }
        if(strlen($this->password)>50){
            self::$alertas["error"][] = "El correo electronico es muy largo";
        }

        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas["error"][] = "La Contraseña es Obligatoria";
        }
        if(strlen($this->password)<6){
            self::$alertas["error"][] = "La Contraseña debe contener al menos 6 Caracteres";
        }
        if(strlen($this->password)>50){
            self::$alertas["error"][] = "La Contraseña es muy larga";
        }
        if($this->password !== $this->password2){
            self::$alertas["error"][] = "Las Contraseñas son diferentes";
        }
        return self::$alertas;
    }
}