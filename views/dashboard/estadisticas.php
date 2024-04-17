<?php include_once __DIR__ . "/header-dashboard.php"; ?>

<div class="contenedor">
    <div class="contenedor-sm">

        <form class="formulario-lista" id="miFormulario" action="/estadisticas" method="post">
            <select id="opciones" name="opciones">
                <option value="1" <?php if (isset($_POST['opciones']) && $_POST['opciones'] == '1')
                    echo 'selected'; ?>>
                    libro que mas registran para leer</option>
                <option value="2" <?php if (isset($_POST['opciones']) && $_POST['opciones'] == '2')
                    echo 'selected'; ?>>
                    libro con mas paginas leidas</option>
                <option value="3" <?php if (isset($_POST['opciones']) && $_POST['opciones'] == '3')
                    echo 'selected'; ?>>
                    libros que mas terminan</option>
                <option value="4" <?php if (isset($_POST['opciones']) && $_POST['opciones'] == '4')
                    echo 'selected'; ?>>
                    usuarios con mas libros registrados para leer</option>
                <option value="5" <?php if (isset($_POST['opciones']) && $_POST['opciones'] == '5')
                    echo 'selected'; ?>>
                    usuarios con mas paginas leidas</option>
                <option value="6" <?php if (isset($_POST['opciones']) && $_POST['opciones'] == '6')
                    echo 'selected'; ?>>
                    usuarios con mas libros terminados</option>
            </select>
        </form>
        <script>
            document.getElementById("opciones").addEventListener("change", function () {
                document.getElementById("miFormulario").submit();
            });
        </script>
        <div>
        <?php if (!empty($resultado2)) { ?>
            <div class="contenedor-usuarios">
                <?php foreach ($resultado2 as $usuario) { ?>
                        
                            <div class="usuario">
                                <div>Nombre: <?php echo $usuario->nombre; ?></div>
                                <div>Total: <?php echo $usuario->total; ?></div>
                            </div>
                    
                <?php } ?>
                </div><!--contenedor-usuarios-->
            <?php }else{ ?>
            <?php if (!empty($resultado)) { ?>

                <?php foreach ($resultado as $libro) { ?>
                    <div class="libro-estadisticas">
                        <div class="imagen">
                            <img loading="lazy" src="build/imagenes/<?php echo $libro->imagen; ?>" alt="Imagen de Libro">
                        </div>
                        <div class="libro">
                            <div class="contenido-libro">
                                <div class="titulo"><?php echo $libro->titulo; ?></div>
                                <div><?php echo $libro->autor; ?></div>
                            </div>
                            <div class="contenido-libro">
                                <div>ISBN: <?php echo $libro->libros_id; ?></div>
                                <div>Paginas: <?php echo $libro->paginas; ?></div>
                            </div>
                            <div class="contenido-boton">
                                <a href="/libro?id=<?php echo $libro->libros_id; ?>">
                                    Ver Libro
                                </a>
                            </div>
                        </div>

                    </div><!--libro-estadisticas-->
                <?php } ?>
            <?php }} ?>


            

        </div><!-- Contenedor para mostrar resultados de búsqueda-->
    </div><!-- Contenedor-sm-->
</div><!-- Contenedor-->