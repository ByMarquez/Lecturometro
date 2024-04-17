<?php include_once __DIR__ . "/header-dashboard.php"; ?>
<?php include_once __DIR__ . '/../templates/alertas.php' ?>
<div class="contenedor">
    <div class="contenedor-sm">
        <div class="libro-registrar">
            <img loading="lazy" src="build/imagenes/<?php echo $libro->imagen; ?>" alt="Imagen de Libro">
            <div class="contenido-libro">
                <li><?php echo $libro->titulo; ?></li>
                <li><?php echo $libro->autor; ?></li>
                <li>Genero: <?php echo $libro->genero; ?></li>
                <li>Paginas: <?php echo $libro->paginas; ?></li>


            </div><!--.contenido-libro-->

            <form action="/mis-libros" method="POST" class="formulario">
                <input type="hidden" name="libros_id" value="<?php echo $libro->id; ?>">
                <div class="campo">
                    <input type="submit" value="Leer Libro">
                </div>
            </form>

            <form action="/modificar" method="GET" class="formulario formulario_btnNaranja">
                <input type="hidden" name="id_actualizacion" value="<?php echo $libro->id; ?>">
                <div class="campo">
                    <input type="submit" value="Modificar datos de Libro">
                </div>
            </form>
        </div><!--libro-->

        <div><!-- Contenedor para mostrar resultados de búsqueda-->
            <div class="listado-comentarios">
                <?php foreach ($comentarios as $comentario) { ?>
                    <div class="comentario">
                        <div class="usuario"><?php echo $comentario->nombre; ?></div>
                        <div class="fecha"><?php echo $comentario->fecha; ?></div>
                        <div class="contenido"><?php echo $comentario->comentario; ?></div>
                    </div><!--comentario-->
                <?php } ?>


            </div> <!--.contenedor-comentarioss-->
        </div><!-- Contenedor para mostrar resultados de búsqueda-->

        <div class="contenido-boton">
            <form method="POST" class="formulario formulario-crear">
                <input type="hidden" name="id" value="<?php echo $libro->id; ?>">
                <div class="campo">
                    <textarea rows="5" cols="20" placeholder="tu comentario" name="comentario"></textarea>
                </div>
                <div class="campo">
                    <input type="submit" value="Enviar Comentario">
                </div>
            </form>
        </div>

    </div><!--contenedor-sm-->
</div><!--contenedor-->