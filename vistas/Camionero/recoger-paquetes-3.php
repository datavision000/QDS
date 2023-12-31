<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene permisos para acceder a esta página
if (!isset($_SESSION['nom_usu']) || $_SESSION['tipo_usu'] !== 'admin') {
    if ($_SESSION['tipo_usu'] !== 'camionero') {
        header("Location: ../permisos.php"); // Redirige a la página de inicio de sesión
        exit();
    }
}
echo "<link rel='stylesheet' href='../css/estilos.css'>";
require '../plantillas/headerIngresado.php';
require '../plantillas/menu-cuenta.php';

?>

<div id="div-elegir-lote">
    <h1 class="h1-tabla2">Plataformas a entregar</h1>
    <p class="adv">Los siguientes almacenes son por los que tendra que pasar la camioneta</p>
    <?php
    require('../../controladores/api/recoger_paquetesCamionero/obtenerDato.php');
    foreach ($decode as $almacen_cliente) {
    $id_almacen_cliente = $almacen_cliente["id_almacen_cliente"];
    $direccion = $almacen_cliente["direccion"];
    $fecha_recogida_ideal = $almacen_cliente["fecha_recogida_ideal1"];
    $fecha_recogida = $almacen_cliente["fecha_recogida"];
    $fecha_salida = $almacen_cliente["fecha_salida"];
    $almacen_central_salida = $almacen_cliente["almacen_central_salida"];
    $id_camioneta = $almacen_cliente["id_camioneta"];

    echo "<div class='div-almacen-recogida'><hr><p><b class='p1'>Almacen Cliente: </b>Almacen $id_almacen_cliente - $direccion</p>
    <p><b class='p2'>Recogida: </b>$fecha_recogida_ideal</p>
    <a href='recoger-paquetes-2.php?id_camioneta=$id_camioneta&id_almacen_cliente=$id_almacen_cliente&fri=$fecha_recogida_ideal'><button class='estilo-boton2 boton-siguiente btn-recoger-paquetes-3'>Ver paquetes del almacén</button></a></div> 
    ";
    }
    if (!isset($fecha_salida) || is_null($fecha_salida) || empty(trim($fecha_salida))){

    } else{
    echo "<a href='detalles-horarios-recogida.php?icth=$id_camioneta&fs=$fecha_salida&acs=$almacen_central_salida' class='finalizar-recorrido'>Ver detalles</a>"; 
    }
    if (!isset($fecha_recogida) || is_null($fecha_recogida) || empty(trim($fecha_recogida))) {
    } else{
        echo "<a href='../../controladores/api/recoger_paquetesCamionero/modificarDato.php?fs=$fecha_salida&ic=$id_camioneta' class='finalizar-recorrido'>Finalizar recorrido</a>";
    }
    ?>

    <div id="mov-lote-lote">
        <a href="recoger-paquetes-1.php">
            <button class="boton-volver estilo-boton">Volver</button>
        </a>
    </div>
</div>

</body>

</html>