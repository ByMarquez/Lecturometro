<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;
use Model\Libro;
use Model\Lectura;
use Model\Permiso;
use Model\Comentario;
use Model\ActiveRecord;
use Intervention\Image\ImageManagerStatic as Image;


class dashboardController extends ActiveRecord
{
    public static function index(Router $router)
    {
        session_start();
        isAuth();
        //$libros = Libro::belongsTo("1","1");
        $librosRegistros = Libro::startBy("1", "1");
        //$totalLibros=count($libros);
        $totalLibros = count($librosRegistros);

        //$paginaActual = s($_GET['pagina']) ? $_GET['pagina'] : 1;
        $paginaActual = isset($_GET['pagina']) ? s($_GET['pagina']) : 1;

        if (!is_numeric($paginaActual)) {
            header("location: /dashboard");
        }
        $librosPorPagina = 6;
        $comienzo = ($paginaActual - 1) * $librosPorPagina;

        $libros = Libro::startBy_page("1", "1", $comienzo, $librosPorPagina);





        if (isset($_GET['buscar'])) {
            $buscar = s($_GET['buscar']);
            //verificar que el ISBN existe
            if (is_numeric($buscar)) {
                $libros = Libro::startBy_page('id', $buscar, $comienzo, $librosPorPagina);
                $registros = Libro::startBy("id", $buscar);
                $totalLibros = count($registros);
            } else {
                //verificar que el titulo existe
                $libros = Libro::startBy_page('titulo', $buscar, $comienzo, $librosPorPagina);
                $registros = Libro::startBy("titulo", $buscar);
                $totalLibros = count($registros);
                if (intval($totalLibros) === 0) {
                    //verificar que el titulo existe
                    $libros = Libro::startBy_page('autor', $buscar, $comienzo, $librosPorPagina);
                    $registros = Libro::startBy("autor", $buscar);
                    $totalLibros = count($registros);
                }
            }
        }

        $numPaginas = ceil($totalLibros / $librosPorPagina);

        if ($paginaActual > $numPaginas & $numPaginas != 0) {
            header("location: /dashboard");
        }
        $router->render("dashboard/index", [
            "titulo" => "Libros",
            "libros" => $libros,
            "numPaginas" => $numPaginas
        ]);
    }

    public static function mislibros(Router $router)
    {
        session_start();
        isAuth();
        //creamos un objeto vacio
        $lectura = new Lectura();
        $alertas = [];



        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //sincronizar post, se trae libros_id
            $lectura->sincronizar($_POST);
            //colocar datos basicos para crear usuario
            $lectura->set_paginas_leidas("0");
            $lectura->set_estatus("0");
            $lectura->set_usuarios_id($_SESSION["id"]);

            if (isset($_POST["libros_id_actualizar"])) {
                //pasar "libros_id_actualizar" -> libros_id
                $lectura->set_libros_id($_POST["libros_id_actualizar"]);
                //pasar las paginas leidas
                $lectura->set_paginas_leidas($_POST["paginas_leidas"]);
                //pasar el id
                $query = "select * from lecturas where libros_id = '" . $lectura->libros_id . "' and usuarios_id=" . $_SESSION["id"];
                $lecturas = Lectura::sqlAvanzado($query);
                $lectura->set_id("" . intval($lecturas[0]->id));
                //validacion
                $alertas = $lectura->validarActualizar();
                //todos los datos estan bien
                if (empty($alertas)) {
                    $paginas_libro = Libro::where('id', $lectura->libros_id);
                    $paginas_leidas = Lectura::where('id', $lectura->id);
                    $paginas_faltantes_por_leer = intval($paginas_libro->paginas) - intval($paginas_leidas->paginas_leidas);//=paginas del libro - paginas que ya habia leido
                    $alertas = $lectura->validarpaginas_leidas($paginas_faltantes_por_leer);
                    if (empty($alertas)) {
                        //modificar las paginas leidas
                        $paginas_leidas_Actuales = intval($paginas_leidas->paginas_leidas) + intval($lectura->paginas_leidas);
                        $lectura->set_paginas_leidas("" . $paginas_leidas_Actuales);
                        //modificar el estatus
                        if (intval($paginas_libro->paginas) === intval($paginas_leidas_Actuales)) {
                            $lectura->set_estatus("1");
                        }
                        //ejecutar actualizacion
                        $lectura->guardar();
                        header('location: /mis-libros');
                    }
                }
            } else {
                //ajecutar eliminacion
                if (isset($_POST["libros_id_eliminar"])) {
                    //pasar "libros_id_actualizar" -> libros_id
                    $lectura->set_libros_id($_POST["libros_id_eliminar"]);
                    //pasar el id
                    $query = "select * from lecturas where libros_id = '" . $lectura->libros_id . "' and usuarios_id=" . $_SESSION["id"];
                    $lecturas = Lectura::sqlAvanzado($query);
                    $lectura->set_id("" . intval($lecturas[0]->id));
                    $lectura->eliminar();
                } else {
                    //es una iserccion
                    //validar si hay algun error en el ISBN
                    $alertas = $lectura->validarRegistrar();
                    if (empty($alertas)) {
                        //validar si el libro ya esta registrado con el usuario, si es asi redirigirlo a libros sin hacer el insert
                        $query = "SELECT lecturas.id, lecturas.libros_id, lecturas.paginas_leidas, lecturas.estatus, libros.titulo, libros.paginas, libros.imagen  FROM lecturas INNER JOIN libros ON lecturas.libros_id = libros.id WHERE lecturas.usuarios_id = '" . $_SESSION["id"] . "' and lecturas.libros_id = '" . $_POST["libros_id"] . "'";
                        $validacion = Lectura::sqlAvanzado($query);
                        if (empty($validacion)) {
                            //insert: no esta el libro registrado
                            $lectura->crearLectura();
                        }

                    }
                }
            }

        }
        $query = "SELECT lecturas.id, lecturas.libros_id, lecturas.paginas_leidas, lecturas.estatus, libros.titulo, libros.paginas, libros.imagen  FROM lecturas INNER JOIN libros ON lecturas.libros_id = libros.id WHERE lecturas.usuarios_id = " . $_SESSION["id"];
        $lecturas = Lectura::sqlAvanzado($query);

        $router->render("dashboard/mis-libros", [
            "titulo" => "Mis Libros",
            "lecturas" => $lecturas,
            'alertas' => $alertas
        ]);

    }

    public static function registrar(Router $router)
    {
        session_start();
        isAuth();
        isAuth_registrar_libro();
        $permiso3 = Permiso::where("id_usuario", $_SESSION["id"]);
        if (!boolval($permiso3->registrar_libro)) {
            $_SESSION = [];
            header('location: /');
        }
        $alertas = [];
        //creamos el objeto
        $libro = new Libro();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Verificar que no existan alertas
            $libro->sincronizar($_POST);
            $alertas = $libro->validarRegistrar();



            //si no hay alertas se inicia el proceso de registro
            if (empty($alertas)) {
                //verificar que la imagen existe
                $existeISBN = Libro::where('id', $libro->id);

                if ($existeISBN) {
                    Libro::setAlerta('error', 'Ya hay un libro registrado con ese ISBN');
                    $alertas = Libro::getAlertas();
                } else {
                    //variable de imagen
                    $imagen = $_FILES['imagen'];
                    if (!$imagen['name']) {
                        Libro::setAlerta('error', 'La imagen es obligatoria');
                        $alertas = Libro::getAlertas();
                    } else {
                        //verificar que el archivo si sea una imagen
                        if (!str_contains($imagen['type'], 'image')) {
                            Libro::setAlerta('error', 'El archivo no es una imagen');
                            $alertas = Libro::getAlertas();
                        } else {
                            //veriicar tamaño de la imagen
                            $medida = 2 * 1000 * 1000;
                            if ($imagen['size'] > $medida) {
                                Libro::setAlerta('error', 'La imagen no debe superar los 2MB');
                                $alertas = Libro::getAlertas();
                            } else {
                                //crear carpeta
                                $carpetaImagenes = '../public/build/imagenes/';
                                if (!is_dir($carpetaImagenes)) {
                                    mkdir($carpetaImagenes);
                                }
                                //generar un nombre unico
                                $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';
                                //crear variables
                                $rutaImagenOriginal = $imagen['tmp_name'];
                                $rutaImagenDestino = $carpetaImagenes . $nombreImagen;

                                //abrir la imagen
                                $img = Image::make($rutaImagenOriginal);
                                // Reescalar la imagen a un máximo de 400 píxeles de ancho
                                $img->resize(400, null, function ($constraint) {
                                    $constraint->aspectRatio(); // Mantener la proporción de aspecto
                                });
                                //pasar el nombre al objeto para despues hacer la inserccion a la bd
                                $libro->setImagen($nombreImagen);
                                // Reducir la calidad de la imagen y guardarla en el servidor
                                $img->save($rutaImagenDestino, 50); // 50 es el nivel de calidad (0 a 100)
                                //guardar el registro en la base de datos
                                $resultado = $libro->crearLibro();
                                if ($resultado) {
                                    //limpiar los campos
                                    $libro = [];
                                    //dar una alerta
                                    Libro::setAlerta('exito', 'Libro Registrado');
                                    $alertas = Libro::getAlertas();
                                }
                            }
                        }
                    }
                }
            }
        }//fin POST


        $router->render("dashboard/registrar", [
            "titulo" => "Registrar Libro",
            'libro' => $libro,
            'alertas' => $alertas,
        ]);
    }

    public static function modificar(Router $router)
    {
        session_start();
        isAuth();
        $permiso3 = Permiso::where("id_usuario", $_SESSION["id"]);
        if (!boolval($permiso3->registrar_libro)) {
            $_SESSION = [];
            header('location: /');
        }
        $alertas = [];
        //creamos el objeto
        $libro = new Libro();

        if (isset($_GET["id_actualizacion"])) {
            $consulta = Libro::where("id", $_GET["id_actualizacion"]);
            $libro = $consulta;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST["isbn_eliminar"])) {
                //sincronizar post
                $libro->setId(s($_POST["isbn_eliminar"]));

                //si no hay alertas se inicia el proceso de actualizacion sin cambio de ISBN
                $alertas = $libro->validarISBNeliminar();

                if (empty($alertas)) {
                    $resultado = $libro->eliminar();

                    if ($resultado) {
                        $libro = [];
                        //dar una alerta
                        Libro::setAlerta('exito', 'Libro Eliminado');
                        $alertas = Libro::getAlertas();
                    }
                }
            } else {
                //Verificar que no existan alertas
                $consulta = Libro::where("id", $_POST["id"]);
                $_POST["imagen"] = $consulta->imagen;
                $libro->sincronizar($_POST);
                //ACTUALIZACION CON CAMBIO DE ISBN
                //si no hay alertas se inicia el proceso de actualizacion sin cambio de ISBN
                $alertas = $libro->validarRegistrar();

                if (empty($alertas) & !isset($_POST["cambiar_isbn"])) {
                    //verificar que el isbn existe
                    $existeISBN = Libro::where('id', $libro->id);
                    if ($existeISBN) {
                        $imagen = $_FILES['imagen'];
                        //verificar que la imagen existe
                        if (!$imagen['name']) {
                            //hacer la actualizacion dejando la imagen
                            if ($consulta != $libro) {
                                if (isset($libro->id)) {
                                    debuguear($libro);
                                    $resultado = $libro->guardar();
                                    if ($resultado) {
                                        //dar una alerta
                                        Libro::setAlerta('exito', 'Libro Actualizado');
                                        $alertas = Libro::getAlertas();
                                    }
                                }


                            } else {
                                //objetos iguales
                                Libro::setAlerta('error', 'Error al actualizar, Mismos datos');
                                $alertas = Libro::getAlertas();
                            }

                        } else {
                            //verificar que el archivo si sea una imagen
                            if (!str_contains($imagen['type'], 'image')) {
                                Libro::setAlerta('error', 'El archivo no es una imagen');
                                $alertas = Libro::getAlertas();
                            } else {
                                //veriicar tamaño de la imagen
                                $medida = 2 * 1000 * 1000;
                                if ($imagen['size'] > $medida) {
                                    Libro::setAlerta('error', 'La imagen no debe superar los 2MB');
                                    $alertas = Libro::getAlertas();
                                } else {
                                    //eliminar laimagen anterior

                                    $consulta = Libro::where("id", $_POST["id"]);
                                    // Obtener la ruta completa de la imagen a eliminar
                                    $rutaImagenEliminar = '../public/build/imagenes/' . $consulta->imagen;
                                    // Verificar si el archivo existe antes de intentar eliminarlo
                                    if (file_exists($rutaImagenEliminar)) {
                                        // Eliminar la imagen del servidor
                                        unlink($rutaImagenEliminar);
                                    }


                                    //crear carpeta
                                    $carpetaImagenes = '../public/build/imagenes/';
                                    if (!is_dir($carpetaImagenes)) {
                                        mkdir($carpetaImagenes);
                                    }
                                    //generar un nombre unico
                                    $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';
                                    //crear variables
                                    $rutaImagenOriginal = $imagen['tmp_name'];
                                    $rutaImagenDestino = $carpetaImagenes . $nombreImagen;

                                    //abrir la imagen
                                    $img = Image::make($rutaImagenOriginal);
                                    // Reescalar la imagen a un máximo de 400 píxeles de ancho
                                    $img->resize(400, null, function ($constraint) {
                                        $constraint->aspectRatio(); // Mantener la proporción de aspecto
                                    });
                                    //pasar el nombre al objeto para despues hacer la inserccion a la bd
                                    $libro->setImagen($nombreImagen);
                                    // Reducir la calidad de la imagen y guardarla en el servidor
                                    $img->save($rutaImagenDestino, 50); // 50 es el nivel de calidad (0 a 100)
                                    //guardar el registro en la base de datos
                                    $resultado = $libro->guardar();
                                    if ($resultado) {
                                        //dar una alerta
                                        Libro::setAlerta('exito', 'Libro Actualizado');
                                        $alertas = Libro::getAlertas();
                                    }
                                }
                            }
                        }
                    } else {
                        Libro::setAlerta('error', 'Error al actualizar, No existe Libro con ese ISBN');
                        $alertas = Libro::getAlertas();
                    }
                } else {














                    //se cambia el ISBN
                    //tenemos el get con el id original
                    //tenemos el objeto libro con lo que enviamos via post, con el isbn nuevo
                    //cualquier error lo mandamos a la url de index
                    //verificar que el isbn original del post existe
                    $isbnOriginal = $_POST["isbn_ori"];
                    $existeISBN = Libro::where('id', $isbnOriginal);
                    if ($existeISBN) {
                        //si existe el isbn que se va a editar
                        $libro->sincronizar($_POST);
                        $aux = $existeISBN;
                        $alertas = $libro->validarRegistrar();
                        if (empty($alertas)) {
                            //validar si si existe el isbn original
                            if ($existeISBN) {
                                $imagen = $_FILES['imagen'];
                                //verificar que la imagen existe
                                if (!$imagen['name']) {
                                    $libro->setImagen($existeISBN->imagen);
                                    //hacer la actualizacion dejando la imagen
                                    if ($existeISBN != $libro) {
                                        //que el isbn del post no sea nulo
                                        if (isset($libro->id)) {
                                            $resultado = $libro->actualizarLibro($isbnOriginal, $libro->id);

                                            if ($resultado) {
                                                //dar una alerta
                                                Libro::setAlerta('exito', 'Libro Actualizado');
                                                $alertas = Libro::getAlertas();
                                            }
                                        }
                                    } else {
                                        //objetos iguales
                                        Libro::setAlerta('error', 'Error al actualizar, desmarque la casilla');
                                        $alertas = Libro::getAlertas();
                                    }

                                } else {
                                    //verificar que el archivo si sea una imagen
                                    if (!str_contains($imagen['type'], 'image')) {
                                        Libro::setAlerta('error', 'El archivo no es una imagen');
                                        $alertas = Libro::getAlertas();
                                    } else {
                                        //veriicar tamaño de la imagen
                                        $medida = 2 * 1000 * 1000;
                                        if ($imagen['size'] > $medida) {
                                            Libro::setAlerta('error', 'La imagen no debe superar los 2MB');
                                            $alertas = Libro::getAlertas();
                                        } else {
                                            //eliminar laimagen anterior

                                            //obtener de la bd el libro original
                                            $consulta = Libro::where("id", $_POST["id"]);
                                            // Obtener la ruta completa de la imagen a eliminar
                                            $rutaImagenEliminar = '../public/build/imagenes/' . $consulta->imagen;
                                            // Verificar si el archivo existe antes de intentar eliminarlo
                                            if (file_exists($rutaImagenEliminar)) {
                                                // Eliminar la imagen del servidor
                                                unlink($rutaImagenEliminar);
                                            }


                                            //crear carpeta
                                            $carpetaImagenes = '../public/build/imagenes/';
                                            if (!is_dir($carpetaImagenes)) {
                                                mkdir($carpetaImagenes);
                                            }
                                            //generar un nombre unico
                                            $nombreImagen = md5(uniqid(rand(), true)) . '.jpg';
                                            //crear variables
                                            $rutaImagenOriginal = $imagen['tmp_name'];
                                            $rutaImagenDestino = $carpetaImagenes . $nombreImagen;

                                            //abrir la imagen
                                            $img = Image::make($rutaImagenOriginal);
                                            // Reescalar la imagen a un máximo de 400 píxeles de ancho
                                            $img->resize(400, null, function ($constraint) {
                                                $constraint->aspectRatio(); // Mantener la proporción de aspecto
                                            });
                                            //pasar el nombre al objeto para despues hacer la inserccion a la bd
                                            $libro->setImagen($nombreImagen);
                                            // Reducir la calidad de la imagen y guardarla en el servidor
                                            $img->save($rutaImagenDestino, 50); // 50 es el nivel de calidad (0 a 100)
                                            //guardar el registro en la base de datos
                                            $isbnOriginal = $_POST["isbn_ori"];
                                            $aux = new Libro();
                                            $aux->sincronizar($_POST);

                                            $resultado = $libro->actualizarLibro($isbnOriginal, $aux->id);
                                            if ($resultado) {
                                                //dar una alerta
                                                Libro::setAlerta('exito', 'Libro Actualizado');
                                                $alertas = Libro::getAlertas();
                                            }
                                        }
                                    }
                                }
                            } else {
                                Libro::setAlerta('error', 'Error al actualizar, No existe Libro con ese ISBN');
                                $alertas = Libro::getAlertas();
                            }
                        }
                    }//si existe isbn
                    else {
                        //objetos iguales
                        Libro::setAlerta('error', 'Error al actualizar, El isbn no existe');
                        $alertas = Libro::getAlertas();
                    }
                }
            }
        }//fin POST
        $router->render("dashboard/modificar", [
            "titulo" => "Registrar Libro",
            'libro' => $libro,
            'alertas' => $alertas,
        ]);
    }
    public static function estadisticas(Router $router)
    {
        session_start();
        isAuth();

        //libro que mas registran para leer
        $query = "SELECT lecturas.libros_id, COUNT(*) AS total_registros, libros.titulo, libros.autor,libros.paginas, libros.imagen FROM lecturas JOIN libros ON lecturas.libros_id = libros.id GROUP BY lecturas.libros_id ORDER BY total_registros DESC LIMIT 10;";
        $resultado = Comentario::sqlAvanzado($query);
        $resultado2 = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($_POST["opciones"]) {
                case '1':
                    //libro que mas registran para leer
                    $query = "SELECT lecturas.libros_id, COUNT(*) AS total_registros, libros.titulo, libros.autor,libros.paginas, libros.imagen FROM lecturas JOIN libros ON lecturas.libros_id = libros.id GROUP BY lecturas.libros_id ORDER BY total_registros DESC LIMIT 10;";
                    $resultado = Comentario::sqlAvanzado($query);
                    break;
                case '2':
                    //libro con mas paginas leidas
                    $query = "SELECT lecturas.libros_id, SUM(lecturas.paginas_leidas) AS total_paginas_leidas, libros.titulo, libros.autor,libros.paginas, libros.imagen FROM lecturas JOIN libros ON lecturas.libros_id = libros.id GROUP BY lecturas.libros_id ORDER BY total_paginas_leidas DESC LIMIT 10;";
                    $resultado = Comentario::sqlAvanzado($query);
                    break;
                case '3':
                    //libros terminados
                    $query = "SELECT libros_id, SUM(estatus) AS terminados, libros.titulo, libros.autor,libros.paginas, libros.imagen FROM lecturas JOIN libros ON lecturas.libros_id = libros.id GROUP BY lecturas.libros_id ORDER BY terminados DESC LIMIT 10;";
                    $resultado = Comentario::sqlAvanzado($query);
                    break;
                case '4':
                    //usuarios con mas libros registrados para leer
                    $query = "SELECT lecturas.usuarios_id, COUNT(*) AS total, usuarios.nombre FROM lecturas JOIN usuarios ON lecturas.usuarios_id = usuarios.id GROUP BY lecturas.usuarios_id ORDER BY total DESC LIMIT 10";
                    $resultado2 = Comentario::sqlAvanzado($query);
                    break;
                case '5':
                    //usuarios con mas paginas leidas
                    $query = "SELECT lecturas.usuarios_id, SUM(lecturas.paginas_leidas) AS total, usuarios.nombre FROM lecturas JOIN usuarios ON lecturas.usuarios_id = usuarios.id GROUP BY lecturas.usuarios_id ORDER BY total DESC LIMIT 10";
                    $resultado2 = Comentario::sqlAvanzado($query);
                    break;
                case '6':
                    //usuarios con mas libros terminados
                    $query = "SELECT lecturas.usuarios_id, COUNT(*) AS total, usuarios.nombre FROM lecturas JOIN usuarios ON lecturas.usuarios_id = usuarios.id WHERE lecturas.estatus = '1' GROUP BY lecturas.usuarios_id ORDER BY total DESC LIMIT 10";
                    $resultado2 = Comentario::sqlAvanzado($query);
                    break;
                default:
                    header("location: /estadisticas");
            }
        }

        $router->render("dashboard/estadisticas", [
            "titulo" => "Estadisticas",
            "resultado" => $resultado,
            "resultado2" => $resultado2,
        ]);
    }

    public static function libro(Router $router)
    {
        session_start();
        isAuth();
        $comentarios = [];
        $id = s($_GET['id']);
        $comentario = new Comentario();
        $alertas = [];


        if (!is_numeric($id)) {
            header("location: /dashboard");
        } else {
            $libro = Libro::where("id", $id);
            if (!$libro) {
                header("location: /dashboard");
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            array_shift($_POST);
            $comentario->sincronizar($_POST);
            $comentario->setMostrar("1");
            $comentario->setFecha("" . date("Y-m-d"));
            $comentario->set_usuarios_id($_SESSION["id"]);
            $comentario->set_libros_id("" . $id);
            $alertas = $comentario->validarComentario();
            if (empty($alertas)) {
                $comentario->crearComentario();
            }
        }

        $query = "SELECT comentarios.id, comentarios.mostrar, comentarios.fecha, comentarios.comentario, comentarios.libros_id, usuarios.nombre FROM comentarios JOIN usuarios ON comentarios.usuarios_id = usuarios.id WHERE mostrar = 1 and comentarios.libros_id =" . $id;
        $comentarios = Comentario::sqlAvanzado($query);

        $router->render("dashboard/libro", [
            "titulo" => "Libro",
            'libro' => $libro,
            'comentarios' => $comentarios,
            'alertas' => $alertas,
        ]);
    }
    public static function permisos(Router $router)
    {
        session_start();//iniciar la sesion
        isAuth();//verificar que este autenticado
        isAuth_registrar_usuarios();//verificar permisos en la sesion
        //verificar en la base de datos
        $permiso3 = Permiso::where("id_usuario", $_SESSION["id"]);
        if (!boolval($permiso3->registrar_usuarios)) {
            $_SESSION = [];
            header('location: /');
        }

        $alertas = [];
        $permiso = new Permiso();
        $permiso2 = new Permiso();


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['id_usuario_crear'])) {
                $permiso->sincronizar($_POST);
                $permiso->set_id_usuario($_POST['id_usuario_crear']);
                //verificar errores
                $alertas = $permiso->validarPermiso();
                if (empty($alertas)) {
                    //el usuario existe
                    $query = Usuario::where("id", $permiso->id_usuario);
                    if ($query) {
                        //el usuario ya esta en la tabla de permisos
                        $query = Permiso::where("id_usuario", $permiso->id_usuario);
                        //crear el permiso
                        if (!$query) {
                            $permiso->guardar();
                        } else {
                            //el usuario ya esta agregado en la tabla de permisos
                            Usuario::setAlerta('error', 'Ya hay un usuario registrado');
                            $alertas = Usuario::getAlertas();
                        }
                    } else {
                        //alerta el usuario no existe
                        Usuario::setAlerta('error', 'El usuario no existe');
                        $alertas = Usuario::getAlertas();
                    }

                }
            } else {
                if (isset($_POST['id_usuario_eliminar'])) {
                    $permiso->set_id_usuario($_POST['id_usuario_eliminar']);
                    //verificar errores en el id
                    $alertas = $permiso->validarEliminar();
                    if (empty($alertas)) {
                        //el usuario existe
                        $query = Usuario::where("id", $permiso->id_usuario);
                        if ($query) {
                            //el usuario ya esta en la tabla de permisos
                            $query = Permiso::where("id_usuario", $permiso->id_usuario);
                            //crear el permiso
                            if ($query) {
                                $permiso = $query;
                                $permiso->eliminar();
                            } else {
                                //el usuario ya esta agregado en la tabla de permisos
                                Usuario::setAlerta('error', 'El usuario no tiene privilegios');
                                $alertas = Usuario::getAlertas();
                            }
                        } else {
                            //alerta el usuario no existe
                            Usuario::setAlerta('error', 'El usuario no existe');
                            $alertas = Usuario::getAlertas();
                        }

                    }
                } else {
                    //actualizar
                    //crear un array con los datos del post
                    $nuevoArreglo = [];
                    foreach ($_POST['usuario'] as $usuarioID => $usuario) {
                        $nuevoArreglo[] = [
                            'id' => $usuario['id'],
                            'registrar_libro' => $usuario['registrar_libro'],
                            'registrar_usuarios' => $usuario['registrar_usuarios'],
                            'id_usuario' => $usuario['id_usuario']
                        ];
                    }







                    //traer los datos directamente de la bd
                    $permiso = Permiso::startBy("1", "1");
                    //verificar si tienen el mismo tamaño
                    if (count($permiso) == count($nuevoArreglo)) {
                        //verificar que el dato del post no sea igual al que esta en la bd, sino no tiene caso actualizar
                        foreach ($nuevoArreglo as $indice => $elemento) {
                            $objeto = $permiso[$indice];
                            if (
                                $elemento["id"] == $objeto->id &&
                                $elemento["registrar_libro"] == $objeto->registrar_libro &&
                                $elemento["registrar_usuarios"] == $objeto->registrar_usuarios &&
                                $elemento["id_usuario"] == $objeto->id_usuario
                            ) {
                            } else {
                                //verificar alertas
                                $permiso2->sincronizar($elemento);
                                $alertas = $permiso2->validarActualizar();
                                if (empty($alertas)) {
                                    //actualizar
                                    $permiso2->guardar();
                                }
                            }
                        }
                    } else {
                        //error al actualizar
                    }
                }
            }

        }
        $query = "SELECT permisos.*, usuarios.nombre, usuarios.correo FROM permisos LEFT JOIN usuarios ON usuarios.id = permisos.id_usuario;";
        $usuarios = Permiso::sqlAvanzado($query);

        $router->render("dashboard/permisos", [
            "titulo" => "Admin",
            "usuarios" => $usuarios,
            'alertas' => $alertas,
        ]);
    }
}