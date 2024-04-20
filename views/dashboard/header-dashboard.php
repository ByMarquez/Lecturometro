<nav class="navegacion-principal">
    <div class="enlaces">
        <?php include_once __DIR__ . "/../templates/logo.php"; ?>
        <?php if (isset($_SESSION['registrar_usuarios'])) { ?>
            <?php if (boolval($_SESSION['registrar_usuarios'])) { ?>
                <a href="/permisos">Registrar Admin</a>
            <?php } ?>
        <?php } ?>
        <?php if (isset($_SESSION['registrar_libro'])) { ?>
            <?php if (boolval($_SESSION['registrar_libro'])) { ?>
                <a href="/registrar">Registrar un Libro</a>
            <?php } ?>
        <?php } ?>
        <a href="/mis-libros">Mis Libros</a>
        <a href="/estadisticas">Estadísticas</a>
        <a href="/logout">Cerrar Sesión</a>
    </div>
    
</nav>