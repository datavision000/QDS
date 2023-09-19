<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene permisos para acceder a esta página
if (!isset($_SESSION['nom_usu']) || $_SESSION['tipo_usu'] !== 'admin') {
    if ($_SESSION['tipo_usu'] !== 'almacenero') {
        header("Location: ../permisos.php"); // Redirige a la página de inicio de sesión
        exit();
    }
}
echo "<link rel='stylesheet' href='../css/estilos.css'>";
require '../plantillas/headerIngresado.php';
require '../plantillas/menu-cuenta.php';

?>

<form action="../../controladores/api/paquete/agregarDato.php" id="form-paquete" method="post">

    <div class="div-datos-paq">
        <legend>Ingreso de Paquete</legend>
        <p class="p-paquete">Sobre el destino</p>
        <input type="email" name="mail_destinatario" id="mail-destinatario-paq" class="destino-paq"
            placeholder="Correo destinatario" autocomplete="off" required>
        <input type="text" name="direccion" id="calle-destino-paq" class="destino-paq" placeholder="Direccion"
            autocomplete="off" required>
        <p class="p-paquete">Características del paquete</p>
        <input type="number" name="peso" id="peso-paq" class="destino-paq" placeholder="Peso (Kg)" autocomplete="off"
            required>
        <input type="number" name="volumen" id="volumen-paq" class="destino-paq" placeholder="Volumen (cm∧3)"
            autocomplete="off" required>
    </div>

    <div class="div-datos-paq">
        <p id="p-fragil">Contenido frágil</p>
        <div id="div-radios">
            <label for="radio-paq-si">Sí</label>
            <input type="radio" name="fragil" id="radio-paq-si" value="si">
            <label for="radio-paq-no">No</label>
            <input type="radio" name="fragil" id="radio-paq-no" value="no" checked>
            <select name="tipo" id="select-fragil-paq" disabled>
                <option value="" selected disabled>Contenido frágil</option>
                <option value="Líquido">Líquido</option>
                <option value="Vidrio">Vidrio</option>
            </select>
            <p class="p-paquete">Detalles</p>
            <textarea name="detalles" id="detalles-paq" cols="30" rows="8" maxlength="150"
                placeholder="Detalles adicionales (opcional)" form="form-paquete"></textarea>
            <a href=""><input type="submit" class="submit-paquete boton-siguiente" value="Ingresar paquete"></a>
            <a href="index.php"><input type="button" class="submit-paquete boton-volver" value="Volver"></a>
        </div>
    </div>
    <?php
    if (isset($_GET['datos'])) {
        $jsonDatos = urldecode($_GET['datos']);
        $datos = json_decode($jsonDatos, true);
        echo $datos['error'] . " ";
        echo $datos['respuesta'];
    }
    //  require("../../controladores/api/paquete/obtenerDato.php");
    // echo '<table>';
    // echo '<tr><th>ID</th><th>Direccion</th><th>Detalles</th></tr>';
    // foreach ($decode as $paquete) {
    //   echo '<tr>';
    // echo '<td>' . $paquete["id_paquete"] . '</td>';
    // echo '<td>' . $paquete["direccion"] . '</td>';
    // echo '<td>' . $paquete['detalles'] . '</td>';
    // echo '</tr>';
    // }
    ?>

</form>

<script src="../js/ingreso-paquete.js"></script>

</body>

</html>

<!--
<?php



?>
-->