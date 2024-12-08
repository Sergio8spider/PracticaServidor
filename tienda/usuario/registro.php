<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro   </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('../util/conexion.php');
    ?>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <?php
    function depurar(string $entrada) : string {
        $salida = htmlspecialchars($entrada);
        $salida = trim($salida);
        $salida = stripslashes($salida);
        $salida = preg_replace('!\s+!', ' ', $salida);
        return $salida;
    }
    ?>
    <div class="container">
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $tmp_usuario = depurar($_POST["usuario"]);
            $tmp_contraseña = depurar($_POST["contraseña"]);

            $sql = "SELECT * FROM usuarios";
            $resultado = $_conexion -> query($sql);
            $usuarios = [];

            while($fila = $resultado -> fetch_assoc()) {
                array_push($usuarios, $fila["usuario"]);
            }

            if($tmp_usuario == ""){
                $err_usuario = "El usuario es obligatorio";
            }else if(in_array($tmp_usuario,$usuarios)){
                $err_usuario = "Ese usuario ya existe";
            }else{
                $patron = "/^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]+$/";
                if(strlen($tmp_usuario) > 15 || strlen($tmp_usuario) < 3){
                    $err_usuario = "El nombre de la categoria debe tener entre 3 y 15 caracteres";
                }else{
                    if(!preg_match($patron, $tmp_usuario)){
                        $err_usuario = "El nombre de la categoria debe contener solo numeros o letras";
                    }else{
                        $usuario = $tmp_usuario;
                    }
                }
            }  
            
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
                        $contraseña = $tmp_contraseña;
                        $contraseña_cifrada = password_hash($contraseña,PASSWORD_DEFAULT);
                    }
                }
            }  
            
            if(isset($usuario) && isset($contraseña)){
                $sql = "INSERT INTO usuarios VALUES ('$usuario','$contraseña_cifrada')";
                $_conexion -> query($sql);
            }

            
        } 
        ?>
        <h1>Registro</h1>
        
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input class="form-control" type="text" name="usuario">
                <?php if(isset($err_usuario)) echo "<span class='error'>$err_usuario</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input class="form-control" type="password" name="contraseña">
                <?php if(isset($err_contraseña)) echo "<span class='error'>$err_contraseña</span>" ?>
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Registrarse">
            </div>
            <div class="mb-3">
                <h4>Si ya tienes cuenta,</h4>
                <a class="btn btn-secondary" href="iniciar_sesion.php">Iniciar sesion
                </a>
            </div>
            <div class="mb-3">
                <a class="btn btn-secondary" href="../">Ir a la pagina principal</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>