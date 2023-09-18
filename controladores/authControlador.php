<?php
header('content-type: application/json; charset=utf-8');
require_once('../modelos/authModelo.php');

$authModelo = new authModelo();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        // Recibe las credenciales del usuario (correo y contraseña) desde la solicitud POST
        $nom_usu = $_POST['nom_usu'];
        $constrasenia = $_POST['contrasenia'];
        // Verifica las credenciales en el modelo
        $usuario = $authModelo->getUserByUsername($nom_usu);
        if ($constrasenia == $usuario['contrasenia']) {
            // Las credenciales son válidas, inicia una sesión
            session_start();
            // Almacena información del usuario en la sesión
            $_SESSION['nom_usu'] = $usuario['nom_usu'];
            $_SESSION['tipo_usu'] = $usuario['tipo_usu'];
            echo "Correcto";
            header("Location: ../index.php");
            // Devuelve una respuesta JSON con información adicional si es necesario
            // Asegúrate de salir del script para que la redirección se ejecute correctamente
        } else {
            // Las credenciales son inválidas, devuelve un mensaje de error
            $response = [
                'error' => "Error",
                'resultado' => "Credenciales Inválidas"
            ];
            $response = json_encode($response);
            header('Location: ../vistas/login.php?data=' . urlencode($response));
            echo json_encode($response);
        }
        
        break;
}

?>