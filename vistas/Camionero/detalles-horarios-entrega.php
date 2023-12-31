<?php
session_start();

// Verifica si el usuario ha iniciado sesión y tiene permisos para acceder a esta página
if (!isset($_SESSION['nom_usu']) || $_SESSION['tipo_usu'] !== 'camionero') {
    header("Location: ../permisos.php"); // Redirige a la página de inicio de sesión
    exit();
}
echo "<link rel='stylesheet' href='../css/estilos.css'>";
require '../plantillas/headerIngresado.php';
require '../plantillas/menu-cuenta.php';
?>
<?php
include("../../modelos/db.php");

$id_camion = $_GET['id_camion'];
$fecha_salida = $_GET["fs"];
$almacen_central_salida = $_GET["acs"];

$instruccion = "select * from lleva inner join camion on lleva.id_camion = camion.id_camion inner join vehiculo on vehiculo.id_vehiculo = camion.id_camion where camion.id_camion=$id_camion AND fecha_salida='$fecha_salida'";
$filas = $conexion->query($instruccion);
$filas = $filas->fetch_all(MYSQLI_ASSOC);

if (count($filas) > 0) {
    foreach ($filas as $fila) {
        $matricula = $fila["matricula"];
        $fecha_salida = $fila["fecha_salida"];
    }
}
?>
<div class="div-btn-uno">
<a href='entregar-lotes-3.php?id_camion=<?=$id_camion?>'><input type='submit' value='Volver' class='estilo-boton boton-volver'></a>
</div>

<?php
echo "
    <div class='div-mapa'>";


$instruccion = "select * from lleva inner join camion on lleva.id_camion = camion.id_camion inner join vehiculo on vehiculo.id_vehiculo = camion.id_camion inner join plataforma on lleva.id_plataforma = plataforma.id_plataforma inner join destino on plataforma.ubicacion = destino.id_destino where camion.id_camion=$id_camion AND fecha_salida='$fecha_salida' ORDER BY fecha_entrega_ideal ASC;";
$filas = $conexion->query($instruccion);

$puntosIntermedios = [];

foreach ($filas->fetch_all(MYSQLI_ASSOC) as $fila) {
    $id_plataforma = $fila["id_plataforma"];
    $fecha_entrega_ideal = $fila["fecha_entrega_ideal"];
    $direccion_almacen = $fila["direccion"];
    $ubicacion = $fila["departamento_destino"];

    $direccion_completa = $direccion_almacen . ", Departamento de " . $ubicacion;
    array_push($puntosIntermedios, $direccion_completa);


}

?>

<div class='div-instrucciones'>

<?php

echo "<h2 class='h2-instrucciones' style='margin-bottom: 10px'>Instrucciones</h2>";
$origen = "Escuela+Superior+de+Informatica,Departamento+de+Montevideo";
$direccionDestino = "Escuela+Superior+de+Informatica,Departamento+de+Montevideo";

$api_key = 'AIzaSyD3apFCRO-Fq2fccUb-g6GvinOzsh-vDYM';

$puntosIntermedios = str_replace(' ', '+', $puntosIntermedios);
$intermediosJson = json_encode($puntosIntermedios);
$puntosIntermedios = implode('|', $puntosIntermedios);




$apiURL = "https://maps.googleapis.com/maps/api/directions/json?origin=$origen&destination=$direccionDestino&waypoints=optimize:true|$puntosIntermedios&key=$api_key&region=uy&language=es";

$response = file_get_contents($apiURL);
if ($response) {
    $datos = json_decode($response, true);

    if ($datos['status'] == 'OK') {
        $distanciaTotal = 0;
        $duracionTotal = 0;

        foreach ($datos['routes'][0]['legs'] as $leg) {
            foreach ($leg['steps'] as $step) {

                echo '<p>' . $step['html_instructions'] . '</p>';
                echo '<p>Distancia hasta el próximo giro: ' . $step['distance']['text'] . '</p>';
                echo '<p>Duracion hasta el próximo giro: ' . $step['duration']['text'] . '</p>';


                if (isset($step['traffic_speed_entry'])) {
                    foreach ($step['traffic_speed_entry'] as $trafficData) {
                        echo '<p>Segmento de tráfico: ' . $trafficData['segment'] . '</p>';
                        echo '<p>Velocidad de tráfico: ' . $trafficData['speed'] . '</p>';
                        echo '<hr>';
                    }
                }
                echo '<hr>';

                $distanciaTotal += $step['distance']['value'];
                $duracionTotal += $step['duration']['value'];
            }
        }
        $distanciaTotal = number_format($distanciaTotal / 1000, 2);
        $duracionTotal = round($duracionTotal / 60);
        echo "<p>Duracion total: $duracionTotal min";
        echo "<p>Distancia total: $distanciaTotal km";

        echo "<hr>";


    } else {
        echo 'No se pudo obtener una respuesta de la API de Google Maps Directions.';
    }
}
?>

</div>

<div id="map"></div>

<script>
    const start = "<?php echo $origen; ?>";
    const end = "<?php echo $direccionDestino; ?>";
    const waypointInputs = <?php echo $intermediosJson; ?>;

    console.log(start);
    console.log(end);
    console.log(waypointInputs);

    waypoints = [];

    waypointInputs.forEach(function (input) {
        if (input) {
            waypoints.push({
                location: input,
                stopover: true
            });
        }
    });

    console.log(waypoints);

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 0,
                lng: 0
            },
            zoom: 10
        });

        var directionsService = new google.maps.DirectionsService();

        var directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true
        });

        var request = {
            origin: start,
            destination: end,
            waypoints: waypoints,
            travelMode: 'DRIVING',
            optimizeWaypoints: true
        };

        var geocoder = new google.maps.Geocoder();

        geocoder.geocode({
            'address': start
        }, function (results, status) {
            if (status === 'OK') {
                var origenCoordenadas = results[0].geometry.location;

                var originMarker = new google.maps.Marker({
                    position: origenCoordenadas,
                    map: map,
                    title: 'Origen'
                });
            }
        });

        geocoder.geocode({
            'address': end
        }, function (results, status) {
            if (status === 'OK') {
                var destinoCoordenadas = results[0].geometry.location;

                var destinationMarker = new google.maps.Marker({
                    position: destinoCoordenadas,
                    map: map,
                    title: 'Destino'
                });
            }
        });

        waypointInputs.forEach(function (waypoint) {
            geocoder.geocode({
                'address': waypoint
            }, function (results, status) {
                if (status === 'OK') {
                    var waypointCoordenadas = results[0].geometry.location;

                    var waypointMarker = new google.maps.Marker({
                        position: waypointCoordenadas,
                        map: map,
                        title: 'Destino intermedio'
                    });
                }
            });
        });

        directionsService.route(request, function (response, status) {
            if (status == 'OK') {
                directionsRenderer.setDirections(response);
            }
        });
    }
</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD3apFCRO-Fq2fccUb-g6GvinOzsh-vDYM&callback=initMap&region=uy&language=es"
    async defer></script>
</div>