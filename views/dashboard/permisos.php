<?php include_once __DIR__ . "/header-dashboard.php"; ?>

<div class="contenedor">
    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <form method="post" class="formulario-tabla">
            <table>
                <tr>
                    <th>ID de Usuario</th>
                    <th>Permisos Libros</th>
                    <th>Permisos Usuarios</th>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="id_usuario_crear">
                    </td>
                    <td><input type="radio" name="registrar_libro" value="1"> Sí
                        <input type="radio" name="registrar_libro" value="0"> No
                    </td>
                    <td><input type="radio" name="registrar_usuarios" value="1"> Sí
                        <input type="radio" name="registrar_usuarios" value="0"> No
                    </td>
                </tr>
                <!-- Agrega más filas según sea necesario para más usuarios -->
            </table>
            <br>
            <div class="btn-crear">
                <input type="submit" value="Crear Usuario">
            </div>
        </form>



        <?php if (!empty($usuarios)) { ?>
            <div class="listado-usuarios">
                <form method="post" class="formulario-tabla">
                    <table>
                        <tr>
                            <th>ID Nombre</th>
                            <th>Permisos Libros</th>
                            <th>Permisos Usuarios</th>
                        </tr>

                        <?php foreach ($usuarios as $usuario) { ?>
                            <input type="hidden" name="usuario[<?php echo $usuario->id; ?>][id]"
                                value="<?php echo $usuario->id; ?>">
                            <input type="hidden" name="usuario[<?php echo $usuario->id; ?>][id_usuario]"
                                value="<?php echo $usuario->id_usuario; ?>">
                            <tr>
                                <td><?php echo $usuario->id_usuario . "  " . $usuario->nombre; ?></td>
                                <td><input type="radio" name="usuario[<?php echo $usuario->id; ?>][registrar_libro]" value="1"
                                        <?php if (isset($usuario->registrar_libro) && $usuario->registrar_libro == '1')
                                            echo 'checked'; ?>> Sí
                                    <input type="radio" name="usuario[<?php echo $usuario->id; ?>][registrar_libro]" value="0"
                                        <?php if (isset($usuario->registrar_libro) && $usuario->registrar_libro == '0')
                                            echo 'checked'; ?>> No
                                </td>
                                <td><input type="radio" name="usuario[<?php echo $usuario->id; ?>][registrar_usuarios]"
                                        value="1" <?php if (isset($usuario->registrar_usuarios) && $usuario->registrar_usuarios == '1')
                                            echo 'checked'; ?>> Sí
                                    <input type="radio" name="usuario[<?php echo $usuario->id; ?>][registrar_usuarios]"
                                        value="0" <?php if (isset($usuario->registrar_usuarios) && $usuario->registrar_usuarios == '0')
                                            echo 'checked'; ?>> No
                                </td>
                            </tr>
                        <?php } ?>

                        <!-- Agrega más filas según sea necesario para más usuarios -->
                    </table>
                    <br>
                    <div class="btn-actualizar">
                        <input type="submit" value="Actualizar Permisos">
                    </div>
                </form>

            </div> <!--.contenedor-anuncios-->
        <?php } ?>


        <form method="post" class="formulario-tabla">
            <table>
                <tr>
                    <th>ID a Eliminar</th>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="id_usuario_eliminar">
                    </td>
                </tr>
                <!-- Agrega más filas según sea necesario para más usuarios -->
            </table>
            <br>
            <div class="btn-eliminar">
                <input type="submit" value="Eliminar Usuario">
            </div>
        </form>

    </div>
</div>