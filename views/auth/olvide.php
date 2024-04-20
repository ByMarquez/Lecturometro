<div class="contenedor">
    <?php include_once __DIR__ ."/../templates/nombre-sitio.php"; ?>
    <div class="contenedor-sm">
        <p class="titulo-pagina">
            Recuperar Cuenta
        </p>
        <?php include_once __DIR__ ."/../templates/alertas.php"; ?>
        <form class="formulario" method="POST" action="/olvide">
            <div class="campo">
                <label for="correo">Correo Electrónico</label>
                <input 
                    type="email"
                    id="correo"
                    placeholder="Tu Correo Electrónico"
                    name="correo"
                    maxlength="50"
                    required>
            </div>
            <input 
            type="submit"
            class="boton"
            value="Enviar Instruciones">
        </form>
        <!---->
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
        </div>
    </div><!--contenedor-sm-->
</div>