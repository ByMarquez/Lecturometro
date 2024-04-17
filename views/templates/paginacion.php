<?php
// Obtener el número de página actual
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Obtener la URL actual sin el parámetro 'page'
$urlActual = strtok($_SERVER["REQUEST_URI"], '?') . "?buscar=" . $_GET['buscar'];
// Mostrar enlaces a páginas anteriores y siguientes
if ($paginaActual > 1) {
    echo '<a class="boton-paginador" href="' . $urlActual . '&pagina=' . ($paginaActual - 1) . '">Anterior</a>';
}

// Mostrar el número de página actual
echo '<span>Página ' . $paginaActual . '</span>';
if ($paginaActual < $numPaginas) {
    // Mostrar enlace a la siguiente página
    echo '<a class="boton-paginador" href="' . $urlActual . '&pagina=' . ($paginaActual + 1) . '">Siguiente</a>';
}
?>