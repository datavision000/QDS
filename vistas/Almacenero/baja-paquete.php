<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene permisos para acceder a esta página
if (!isset($_SESSION['nom_usu']) || $_SESSION['tipo_usu'] !== 'admin') {
    if ($_SESSION['tipo_usu'] !== 'almacenero') {
        header("Location: ../permisos.php"); // Redirige a la página de inicio de sesión
        exit();
    }
}
if (!isset($_GET['id_paquete']) || is_null($_GET['id_paquete']) || empty(trim($_GET['id_paquete']))) {
    header("Location: ../error.php");
}
echo "<link rel='stylesheet' href='../css/estilos.css'>";
require '../plantillas/headerIngresado.php';
require '../plantillas/menu-cuenta.php';

?>
<?php
$id_paquete = $_GET['id_paquete'];
require("../../controladores/api/paquete/obtenerDatoPorId.php");
foreach ($decode as $paquete) {
    $id_paquete = $paquete["id_paquete"];
    $mail_destinatario = $paquete["mail_destinatario"];
    $direccion = $paquete["direccion"];
    $peso = $paquete["peso"];
    $volumen = $paquete["volumen"];
    $fragil = $paquete["fragil"];
    $tipo = $paquete["tipo"];
    $estado = $paquete["paquete_estado"];
    $detalles = $paquete["detalles"];

}
?>
<div class="form-crud">
    <legend class="legend-baja-paquete">Eliminar Paquete</legend>
    <p class="adv">¿Seguro que quiere eliminar el siguiente paquete? Los cambios serán irreversibles</p>
    <p><b class="p-id">ID: </b>
        <?= $id_paquete ?>
    </p>
    <p><b class="p-mail-d">Mail del destinatario: </b>
        <?= $mail_destinatario ?>
    </p>
    <p><b class="p-direccion">Dirección: </b>
        <?= $direccion ?>
    </p>
    <p><b class="p-peso">Peso: </b>
        <?= $peso ?> kg
    </p>
    <p><b class="p-volumen">Volumen: </b>
        <?= $volumen ?> cm3
    </p>
    <p><b class="p-fragil">Frágil: </b>
        <?= $fragil ?>
    </p>
    <?php
    if ($fragil == "Si") {
        echo "<p><b class='p-tipo'>Tipo: </b>$tipo</p>";
    }
    ?>
    <p><b class="p-estado">Estado: </b>
        <?= $estado ?>
    </p>
    <?php
    if (!isset($detalles) || is_null($detalles) || empty(trim($detalles))) {
    } else {
        echo "<p><b class='p-detalles'>Detalles: </b>$detalles</p>";
    }
    ?>
    <a href="../../controladores/api/paquete/eliminarDato.php?id_paquete=<?= $id_paquete ?>"><input type="submit" value="Eliminar" class="estilo-boton boton-siguiente boton-eliminar"></a>
    <a href="op-paquetes.php"><input type="submit" value="Volver" class="estilo-boton boton-volver"></a>

</div>