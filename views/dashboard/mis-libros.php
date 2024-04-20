<?php include_once __DIR__ . "/header-dashboard.php"; ?>
<h2><?php echo $_SESSION["nombre"];?> ID:<?php echo $_SESSION["id"]; ?></h2>

<div class="contenedor">
    <div class="contenedor-md">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>
        <div>
            <?php if (empty($lecturas)) { ?>
                <p class="no-libros">No Tienes Libros por leer</p>
                <p class="simbolo">!</p>
                <p class="no-libros"><a href="/dashboard">Registra uno para leer Aquí</a></p>
            <?php } else { ?>
                <div class="listado-lecturas">


                    <?php foreach ($lecturas as $lectura) { ?>
                        <div class="libro">
                            <div class="imagen">
                                <img loading="lazy" src="build/imagenes/<?php echo $lectura->imagen; ?>" alt="Imagen de Libro">
                            </div>
                            <div class="contenido-libro">
                                <li><?php echo $lectura->titulo; ?></li>
                                <li>ISBN: <?php echo $lectura->libros_id; ?></li>
                                <li>Estatus:
                                    <?php
                                    if ($lectura->estatus === "0") {
                                        echo 'Leyendo';
                                    } else {
                                        echo 'Leído';
                                    }
                                    ?>
                                </li>
                                <li>Paginas: <?php echo $lectura->paginas; ?></li>
                                <li>Paginas Leidas: <?php echo $lectura->paginas_leidas; ?></li>


                            </div><!--.contenido-libro-->

                            <div class="contenido-boton">

                                <form action="/mis-libros" method="POST" class="formulario formulario-actualizar">
                                    <div class="campo">
                                        <input type="text" name="paginas_leidas" placeholder="Paginas Leidas">
                                    </div>
                                    <input type="hidden" name="libros_id_actualizar" value="<?php echo $lectura->libros_id;?>">
                                    <div class="campo">
                                        <input type="submit" value="Actualizar Paginas Leidas">
                                    </div>
                                </form>
                                <form action="/mis-libros" method="POST" class="formulario formulario-eliminar">
                                    <input type="hidden" name="libros_id_eliminar" value="<?php echo $lectura->libros_id; ?>">
                                    <div class="campo">
                                        <input type="submit" value="Eliminar Registro de libro">
                                    </div>
                                </form>
                            </div>

                        </div><!--libro-->
                    <?php } ?>
                </div> <!--.contenedor-anuncios-->
            <?php } ?>
        </div><!-- Contenedor para mostrar resultados de búsqueda-->
    </div><!-- Contenedor-sm-->
</div><!-- Contenedor-->