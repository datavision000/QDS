<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene permisos para acceder a esta página
if (!isset($_SESSION['nom_usu']) || $_SESSION['tipo_usu'] !== 'admin') {
    header("Location: ../permisos.php"); // Redirige a la página de inicio de sesión
    exit();
}
echo "<link rel='stylesheet' href='../css/estilos.css'>";
require '../../controladores/funciones.php';
require '../plantillas/headerIngresado.php';
require '../plantillas/menu-cuenta.php';
?>


<div class="form-crud">
    <form action="alta-almacen-central.php" method="post">
        <legend class="legend-form">Agregar Almacén Central</legend>
        <input type="text" placeholder="Teléfono" class="txt-crud txt-1" name="telefono[]" required maxlength="20">
        <input type="tel" placeholder="Número de almacén" class="txt-crud txt-2" name="numero_almacen[]" required
            maxlength="11">
        <a href=""><input type="submit" value="Agregar" class="estilo-boton boton-siguiente boton-agregar"></a>
    </form>
    <a href="op-almacen-central.php"><input type="submit" value="Volver" class="estilo-boton boton-volver"></a>
</div>

<?php


if ($_POST) {
    $telefono = $_POST["telefono"];
    $numero_almacen = $_POST["numero_almacen"];

    $numArrays = count($telefono);
    for ($i = 0; $i < $numArrays; $i++) {
        include("../../modelos/db.php");
        $respuesta = existencia('almacen_central', 'telefono', $telefono[$i]);
        if ($respuesta['error'] == "Error") {
            $respuesta = [
                'error' => "Error",
                'respuesta' => "Ya existe el telefono $telefono[$i]"
            ];
            break;
        }

        $respuesta = existencia('almacen_central', 'numero_almacen', $numero_almacen[$i]);
        if ($respuesta['error'] == "Error") {
            $respuesta = [
                'error' => "Error",
                'respuesta' => "Ya existe el número de puerta $numero_almacen[$i]"
            ];
            break;
        }

        $respuesta = atributosVacio($telefono);
        $respuesta1 = atributosVacio($numero_almacen);

        if ($respuesta['error'] !== "Error" && $respuesta1['error'] !== "Error") {

            $respuesta = longitud($telefono[$i], 20);
            $respuesta1 = longitud($numero_almacen[$i], 11);

            if ($respuesta['error'] !== "Error" && $respuesta1['error'] !== "Error") {

                $respuesta = numeros($telefono[$i]);
                $respuesta1 = numeros($numero_almacen[$i]);
                
                if ($respuesta['error'] !== "Error" && $respuesta1['error'] !== "Error") {
                    $respuesta = [
                        'error' => "Éxito",
                        'respuesta' => "Almacén guardado"
                    ];
                    $instruccion = "insert into almacen_central(numero_almacen, telefono) value ('$numero_almacen[$i]', '$telefono[$i]')";
                    $conexion->query($instruccion);
                } else {
                    $respuesta = [
                        'error' => "Éxito",
                        'respuesta' => "Debes completar los campos solo con números"
                    ];
                }
            } else {
                $respuesta = [
                    'error' => "Éxito",
                    'respuesta' => "Palabras inválidas"
                ];
            }


        } else {
            $respuesta = [
                'error' => "Error",
                'respuesta' => "Hay atributos que no deben estar vacíos"
            ];
        }

    }
    $respuesta = json_encode($respuesta);
    header('Location: alta-almacen-central.php?datos=' . urlencode($respuesta));
}
?>
<div class="div-error">
    <?php
    if (isset($_GET['datos'])) {
        $jsonDatos = urldecode($_GET['datos']);
        $datos = json_decode($jsonDatos, true);
        echo $datos['respuesta'];
    }
    ?>
</div>

<script src="../js/ocultar-get-alta.js"></script>
<script src="../js/mostrar-respuesta.js"></script>