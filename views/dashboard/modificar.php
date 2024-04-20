<?php include_once __DIR__ . "/header-dashboard.php"; ?>

<div class="contenedor">
    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>
        <form class="formulario" method="POST" action="/modificar" enctype="multipart/form-data">
            <input type="hidden" name="isbn_ori" value="<?php echo $libro->id; ?>">
            <input type="checkbox" id="cambiar_lecturas" name="cambiar_isbn">
            <label for="cambiar_lecturas">Si se cambia este dato, se cambiarán las lecturas de todos los usuarios
                referenciadas a este ISBN</label>
            <script>
                document.getElementById('cambiar_lecturas').addEventListener('change', function () {
                    var inputISBN = document.getElementById('id');
                    if (this.checked) {
                        inputISBN.removeAttribute('readonly');
                    } else {
                        inputISBN.setAttribute('readonly', 'readonly');
                    }
                });
            </script>
            <div class="campo">
                <label for="isbn">ISBN</label>
                <input type="number" name="id" id="id" placeholder="Código ISBN del Libro" maxlength="13" required
                    value="<?php echo $libro->id; ?>" readonly>
            </div>
            <div class="campo">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" placeholder="Título Identificador del Libro" maxlength="80" required
                    value="<?php echo $libro->titulo; ?>">
            </div>
            <div class="campo">
                <label for="genero">Genero Literario </label>
                <input type="text" name="genero" id="genero" placeholder="Genero del Libro" maxlength="30" required
                    value="<?php echo $libro->genero; ?>">
            </div>
            <div class="campo">
                <label for="autor">Autor</label>
                <input type="text" name="autor" id="autor" placeholder="Autor del Libro" maxlength="50" required
                    value="<?php echo $libro->autor; ?>">
            </div>
            <div class="campo">
                <label for="paginas">Paginas</label>
                <input type="number" name="paginas" id="paginas" placeholder="Paginas del Libro" maxlength="6" required
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
        <form class="formulario btn-eliminar-modificar" method="POST" action="/modificar">
            <input type="hidden" name="isbn_eliminar" value="<?php echo $libro->id; ?>">
            <input type="submit" value="Eliminar libro">
        </form>

    </div>
</div>