
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado del Alta</title>
</head>
<body>

<?php
if (isset($_POST["usu"]) && isset($_POST["clave"]) && isset($_POST["nombre"])) {
    $conexion = mysqli_connect("localhost", "root", "");

    if (!$conexion) {
        echo "ERROR: No se pudo conectar a la base de datos.<br>";
    } else {
        $usuario = $_POST["usu"];
        $clave = $_POST["clave"];
        $nombre = $_POST["nombre"];

        $db = mysqli_select_db($conexion, "ejemplo");

        if (!$db) {
            echo "ERROR: No se pudo seleccionar la base de datos.<br>";
        } else {
            // Verificamos si el usuario ya existe
            $verificar_query = "SELECT login FROM acceso WHERE login = ?";
            $stmt_verificar = mysqli_prepare($conexion, $verificar_query);
            mysqli_stmt_bind_param($stmt_verificar, "s", $usuario);
            mysqli_stmt_execute($stmt_verificar);
            mysqli_stmt_store_result($stmt_verificar);

            if (mysqli_stmt_num_rows($stmt_verificar) > 0) {
                echo "<b>El usuario ya existe. Por favor, elige otro nombre de usuario.</b><br>";
            } else {
                // Insertar el nuevo usuario
                $query = "INSERT INTO acceso (login, clave, nya) VALUES (?, md5(?), ?)";
                $stmt = mysqli_prepare($conexion, $query);
                
                if ($stmt === false) {
                    echo "ERROR al preparar la consulta: " . mysqli_error($conexion) . "<br>";
                    exit;
                }
                
                mysqli_stmt_bind_param($stmt, "sss", $usuario, $clave, $nombre);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<b>¡Usuario registrado exitosamente!</b><br>";
                } else {
                    echo "ERROR: No se pudo insertar el registro.<br>";
                }

                mysqli_stmt_close($stmt);
            }

            mysqli_stmt_close($stmt_verificar);
            mysqli_close($conexion);
        }
    }
} else {
    echo "Por favor, rellena el formulario correctamente desde <a href='altas.html'>altas.html</a>.";
}
?>

<a href="index.html">Volver a la pagina principal</a>

</body>
</html>
