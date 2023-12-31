<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene permisos para acceder a esta página
if (!isset($_SESSION['nom_usu']) || $_SESSION['tipo_usu'] !== 'admin') {
    if ($_SESSION['tipo_usu'] !== 'almacenero') {
        header("Location: ../permisos.php"); // Redirige a la página de inicio de sesión
        exit();
    }
}
if (!isset($_GET['id_lote']) || is_null($_GET['id_lote']) || empty(trim($_GET['id_lote']))) {
    header("Location: ../error.php");
}
echo "<link rel='stylesheet' href='../css/estilos.css'>";
require '../plantillas/headerIngresado.php';
require '../plantillas/menu-cuenta.php';
?>
<?php
$id_lote = $_GET['id_lote'];
require("../../controladores/api/lote/obtenerDatoPorId.php");
foreach ($decode as $lote) {
    $id_lote = $lote["id_lote"];
    $cant_paquetes = $lote["cant_paquetes"];
    $peso = $lote["peso"];
    $volumen = $lote["volumen"];
    $fragil = $lote["fragil"];
    $tipo = $lote["tipo"];
    $detalles = $lote["detalles"];
}
?>
<div class="form-crud">
    <legend class="legend-c-lote">Consultar Lote</legend>
    <p class="subtitulo-crud">Datos del lote</p>
    <p><b class='p-id'>ID: </b>
        <?= $id_lote ?>
    </p>
    <p><b class='p-cant-paquetes'>Cantidad de paquetes: </b>
        <?= $cant_paquetes ?>
    </p>
    <p><b class='p-peso'>Peso: </b>
        <?= $peso ?> Kg
    </p>
    <p><b class='p-volumen'>Volumen: </b>
        <?= $volumen ?> Cm3
    </p>
    <p><b class='p-fragil'>Fragil: </b>
        <?= $fragil ?>
    </p>
    <?php
    if ($fragil == "Si") {
        echo "<p><b class='p-tipo'>Tipo: </b>$tipo</p>";
    }
    ?>
    <?php
    if (!isset($detalles) || is_null($detalles) || empty(trim($detalles))) {
    } else {
        echo "<p><b class='p-detalles'>Detalles: </b>$detalles</p>";
    }
    ?>
    <a href="op-lotes.php"><input type="submit" value="Volver" class="estilo-boton boton-volver"></a>

</div>