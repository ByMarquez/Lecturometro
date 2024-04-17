<div class="contenedor">
    <?php include_once __DIR__ ."/../templates/nombre-sitio.php"; ?>
    <div class="contenedor-sm">
        <p class="titulo-pagina">
            Iniciar Sesión
        </p>
        <?php include_once __DIR__ ."/../templates/alertas.php"; ?>
        <form class="formulario" method="POST" action="/">
            <div class="campo">
                <label for="correo">Correo Electrónico</label>
                <input 
                    type="email"
                    id="correo"
                    placeholder="Tu Correo Electrónico"
                    name="correo">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Cotraseña"
                    name="password">
            </div>
            <input 
            type="submit"
            class="boton"
            value="Iniciar Sesión">
        </form>
        <!---->
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>
    </div><!--contenedor-sm-->
</div>