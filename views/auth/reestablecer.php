<div class="contenedor">
    <?php include_once __DIR__ ."/../templates/nombre-sitio.php"; ?>
    <div class="contenedor-sm">
        <p class="titulo-pagina">
            Restablece tu Contraseña
        </p>
        <?php include_once __DIR__ ."/../templates/alertas.php"; ?>
        <form class="formulario" method="POST">
            <div class="campo">
                <label for="password">Contraseña</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Cotraseña"
                    name="password"
                    maxlength="50"
                    required>
            </div>
            <div class="campo">
                <label for="password2">Confirma tu Contraseña</label>
                <input 
                    type="password"
                    id="password2"
                    placeholder="Confirma tu Cotraseña"
                    name="password2"
                    maxlength="50"
                    required>
            </div>
            <input 
            type="submit"
            class="boton"
            value="Reestablecer Contraseña">
        </form>
        <!---->
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
        </div>
    </div><!--contenedor-sm-->
</div>