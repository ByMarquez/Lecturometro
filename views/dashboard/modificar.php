<?php include_once __DIR__ . "/header-dashboard.php"; ?>

<div class="contenedor">
    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>
        <form class="formulario" method="POST" action="/modificar" enctype="multipart/form-data">
            <div class="campo">
                <label for="isbn">ISBN</label>
                <input type="text" name="id" id="id" placeholder="Código ISBN del Libro"
                    value="<?php echo $libro->id; ?>" readonly>
            </div>
            <div class="campo">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" placeholder="Título Identificador del Libro"
                    value="<?php echo $libro->titulo; ?>">
            </div>
            <div class="campo">
                <label for="genero">Genero Literario </label>
                <input type="text" name="genero" id="genero" placeholder="Genero del Libro"
                    value="<?php echo $libro->genero; ?>">
            </div>
            <div class="campo">
                <label for="autor">Autor</label>
                <input type="text" name="autor" id="autor" placeholder="Autor del Libro"
                    value="<?php echo $libro->autor; ?>">
            </div>
            <div class="campo">
                <label for="paginas">Paginas</label>
                <input type="text" name="paginas" id="paginas" placeholder="Paginas del Libro"
                    value="<?php echo $libro->paginas; ?>">
            </div>
            <div class="campo">
                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png, image/webp" name="imagen">
            </div>
            <?php if ($libro->imagen) { ?>
                <img src="build/imagenes/<?php echo $libro->imagen ?>" class="imagen-small">
            <?php } ?>
            <input type="submit" value="Actualizar libro">
        </form>

    </div>
</div>