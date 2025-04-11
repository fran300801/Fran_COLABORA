<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Inicio de Sesión</title>
</head>
<body>

<?php
if (isset($_POST["usu"]) && isset($_POST["clave"])) {
    $conexion = mysqli_connect("localhost", "root", "");

    if (!$conexion) {
        echo "ERROR: Imposible establecer conexión con la base de datos.<br>\n";
    } else {
        // Sanitizamos los datos de entrada
        $usuario = $_POST["usu"];
        $clave = $_POST["clave"];

        $db = mysqli_select_db($conexion, "ejemplo");

        if (!$db) {
            echo "ERROR: Imposible seleccionar la base de datos.<br>\n";
        } else {
            // Usamos una consulta preparada para evitar SQL Injection
            $query = "SELECT * FROM acceso WHERE login = ? AND clave = md5(?)";

            if ($stmt = mysqli_prepare($conexion, $query)) {
                // Vinculamos los parámetros de entrada a la consulta
                mysqli_stmt_bind_param($stmt, "ss", $usuario, $clave);

                // Ejecutamos la consulta
                mysqli_stmt_execute($stmt);

                // Obtenemos el resultado de la consulta
                $resul = mysqli_stmt_get_result($stmt);

                if (!$resul) {
                    echo "ERROR: Imposible realizar consulta.<br>\n";
                } else {
                    echo "Consulta realizada satisfactoriamente!<br>\n";
                    echo "Se encontraron " . mysqli_num_rows($resul) . " registros.<br>";

                    if (mysqli_num_rows($resul) == 0) {
                        echo "<br><b>Usuario y/o clave incorrectos!.<br></b>\n";
                    } else {
                        echo "<br>REGISTROS ENCONTRADOS:<br>\n";
                        while ($fila = mysqli_fetch_row($resul)) {
                            echo "<b>USUARIO:</b> $fila[0] <b>CLAVE:</b> $fila[1] <b>Nombre:</b> $fila[2] <b>HAS CONSEGUIDO ENTRAR EN LA PÁGINA WEB!</b><br>";
                        }
                    }
                }

                // Cerramos la declaración
                mysqli_stmt_close($stmt);
            } else {
                echo "ERROR: No se pudo preparar la consulta.<br>\n";
            }

            mysqli_close($conexion);
        }
    }
}
?>

<form action="altas.html" method="get">
    <input type="submit" value="Registrar nuevo usuario">
</form>

</body>
</html>