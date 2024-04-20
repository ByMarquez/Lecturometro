<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Permiso;
use Model\Usuario;

class loginController
{
    public static function login(Router $router)
    {
        $alertas=[];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if (empty($alertas)) {
                //hacer consulta por correo
                $usuario = Usuario::where('correo', $usuario->correo);
                //verificar si existe correo
                if($usuario){
                    //comprobar el password
                    if(password_verify($_POST['password'], $usuario->password)){
                        //Iniciar una session
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['correo'] = $usuario->correo;
                        $_SESSION['login'] = true;
                        $permiso = Permiso::where("id_usuario", $usuario->id);
                        if($permiso){
                            $_SESSION['registrar_usuarios'] = $permiso->registrar_usuarios;
                            $_SESSION['registrar_libro'] = $permiso->registrar_libro;
                        }
                        //redireccionar
                        header('location: /dashboard');
                    }else{
                        Usuario::setAlerta('error', 'Credenciales no validas');
                    }
                }else{
                    Usuario::setAlerta('error', 'Credenciales no validas');
                }
            }
            $alertas=Usuario::getAlertas();
        }

        //Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }


    public static function logout()
    {
        session_start();
        $_SESSION=[];
        header('location: /');
    }


    public static function crear(Router $router)
    {
        $alertas=[];
        $usuario = new Usuario;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            
            if(empty($alertas)){
                $existeCorreo = Usuario::where('correo', $usuario->correo);
                if($existeCorreo){
                    Usuario::setAlerta('error', 'Ya hay una cuenta asociada a ese correo electrónico');
                    $alertas=Usuario::getAlertas();
                }else{
                    //hash de password
                    $usuario->hashPassword();
                    //eliminar la variable password2 del objeto
                    unset($usuario->password2);
                    //Crear el usuario
                    $resultado = $usuario->guardar();
                    
                    if($resultado){
                        //limpiar los campos
                        $usuario=null;
                        //alerta de cuenta creada
                        header('location: /mensaje');
                    }
                }
            }
        }
        $router->render('auth/crear', [
            'titulo' => 'Crea tu Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        $alertas=[];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarCorreo();
            if(empty($alertas)){
                //buscar el correo
                $usuario = Usuario::where('correo', $usuario->correo);
                //usuario encontrado
                if($usuario){
                    //generar el token
                    $usuario->crearToken();
                    unset($usuario->password2);
                    //almacenar token
                    $usuario->guardar();

                    //enviar email
                    $phpemail = new Email($usuario->correo, $usuario->nombre, $usuario->token);
                    $phpemail->enviarReestablecer();
                    //generar alerta
                    Usuario::setAlerta('exito', 'Se enviará un correo electrónico de restablecimiento de contraseña.');
                }else{
                    //generar alerta
                    Usuario::setAlerta('error', 'No existe una cuanta con ese correo electrónico');
                }
            }
        }
        //imprimir alerta
        $alertas=Usuario::getAlertas();

        //render de la vista
        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Contraseña',
            'alertas' => $alertas
        ]);
    }
    public static function reestablecer(Router $router)
    {
        $alertas=[];
        //traer el token de la url
        $token = s($_GET['token']);
        if(!$token){
            header("location: /");
        }
        //traer informacion del usuarip
        $usuario = Usuario::where("token", $token);
        if(empty($usuario)){
            header("location: /");
        }
        //añadir nuevo password
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //leer la entrada
            $usuario->sincronizar($_POST);
            //validar password
            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                //hash de password
                $usuario->hashPassword();

                //eliminar el token
                $usuario->token = null;

                //actualizar usuario
                $resultado = $usuario->guardar();

                //redireccionar
                if($resultado){
                    header("location: /");
                }
            }
        }

        $router->render('auth/reestablecer', [
            'titulo'=> 'Restablecer Contraseña',
            'alertas' => $alertas
        ]);   
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo'=> 'Cuenta Creada'
        ]);   
    }
}