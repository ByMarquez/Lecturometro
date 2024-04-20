<?php include_once __DIR__ . "/header-dashboard.php"; ?>

<div class="contenedor">
    <div class="contenedor-sm contenedor-md">
        <form class="buscar" method="GET">
            <input type="text" id="buscar" placeholder="Buscar..." name="buscar">
            <input type="submit" value="Buscar">
        </form>


        <div>
            <?php if (intval($numPaginas) === 0) { ?>
                <p class="no-libros">No hemos encontrado el Libro</p>
                <p class="simbolo">!</p>
                <p class="no-libros">¿Qué hago?</p>
                <p class="no-libros">Compruebe los términos introducidos.</p>
                <p class="no-libros">Intenta utilizar una sola palabra</p>
                <p class="no-libros">Utilice términos genéricos en la búsqueda.</p>
                <p class="no-libros"><a href="/registrar">Registralo Aquí</a></p>
            <?php } else { ?>
                <div class="listado-libros">


                    <?php foreach ($libros as $libro) { ?>
                                <div class="libro">
                                    <img loading="lazy" src="build/imagenes/<?php echo $libro->imagen; ?>" alt="Imagen de Libro">
                                    <div class="contenido-libro">
                                        <li><?php echo $libro->titulo; ?></li>
                                        <li><?php echo $libro->autor; ?></li>
                                        <li>Genero: <?php echo $libro->genero; ?></li>
                                        <li>Paginas: <?php echo $libro->paginas; ?></li>
                                        
                                        
                                    </div><!--.contenido-libro-->
                                    <div class="contenido-boton">
                                        <a href="/libro?id=<?php echo $libro->id; ?>" >
                                            Leer Libro
                                        </a>
                                        </div>
                                </div><!--libro-->
                    <?php } ?>


                    </div> <!--.contenedor-anuncios-->
            <?php } ?>
        </div><!-- Contenedor para mostrar resultados de búsqueda-->
    </div><!--contenedor-sm-->
</div>
    
    <div class="paginacion">
        <?php include_once __DIR__ . "/../templates/paginacion.php";?>
    </div>

<?php include_once __DIR__ . "/footer-dashboard.php"; ?>