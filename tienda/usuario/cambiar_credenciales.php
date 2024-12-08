<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('../util/conexion.php');

        session_start();
        if (!isset($_SESSION["usuario"])) { 
            header("location: ../usuario/iniciar_sesion.php");
            exit;
        }
    ?>
    <style>
        .error {
            color: red;
        }
        .aviso{
            color:green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cambiar credenciales</h1>
        <?php

        $usuario = $_GET["usuario"];
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        $resultado = $_conexion -> query($sql);
        
        while($fila = $resultado -> fetch_assoc()) {
            $contraseña = $fila["contraseña"];
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_contraseña = $_POST["contraseña"];

            if($tmp_contraseña == ""){
                $err_contraseña = "La contraseña es obligatoria";
            }else{
                $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                if(strlen($tmp_contraseña) > 15 || strlen($tmp_contraseña) < 8){
                    $err_contraseña = "La contraseña debe tener entre 8 y 15 caracteres";
                }else{
                    if(!preg_match($patron, $tmp_contraseña)){
                        $err_contraseña = "La contraseña debe tener al menos una mayuscula, una minuscula y un numero. Puede tener caracteres especiales";
                    }else{
                        $contraseña = password_hash($tmp_contraseña,PASSWORD_DEFAULT);
                        $sql = "UPDATE usuarios SET contraseña = '$contraseña' WHERE usuario = '$usuario'";
                        $_conexion -> query($sql);
                        $aviso_contraseña = "La contraseña se ha actualizado correctamente";
                    }
                }
            }  
        }

        ?>
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input class="form-control" type="text" name="usuario" value="<?php echo $usuario ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input class="form-control" type="password" name="contraseña">
                <?php if(isset($err_contraseña)) echo "<span class='error'>$err_contraseña</span>"; ?>
                <?php if(isset($aviso_contraseña)) echo "<span class='aviso'>$aviso_contraseña</span>"; ?>
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Cambiar contraseña">
            </div>
            <div class="mb-3">
                <a class="btn btn-secondary" href="../">Ir a la pagina principal</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>