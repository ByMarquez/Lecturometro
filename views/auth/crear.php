<div class="contenedor">
    <?php include_once __DIR__ ."/../templates/nombre-sitio.php"; ?>
    <div class="contenedor-sm">
        <p class="titulo-pagina">
            Crea tu Cuenta
        </p>
        <?php include_once __DIR__ ."/../templates/alertas.php"; ?>
        <form class="formulario" method="POST" action="/crear">
        <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    id="nombre"
                    placeholder="Tu Nombre"
                    name="nombre"
                    value="<?php echo $usuario->nombre; ?>"
                    maxlength="50"
                    required>
            </div>
            <div class="campo">
                <label for="correo">Correo Electrónico</label>
                <input 
                    type="email"
                    id="correo"
                    placeholder="Tu Correo Electrónico"
                    name="correo"
                    value="<?php echo $usuario->correo; ?>"
                    maxlength="50"
                    required>
            </div>
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
            value="Crear Cuenta">
        </form>
        <!---->
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <!--Olvidaste tu Contraseña
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>-->
        </div>
    </div><!--contenedor-sm-->
</div>