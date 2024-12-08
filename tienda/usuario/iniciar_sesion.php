<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion</title>
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
                $usuario = $_POST["usuario"];
                $contraseña = $_POST["contraseña"]; 

                $sql="SELECT * FROM usuarios WHERE usuario ='$usuario'";
                $resultado=$_conexion -> query($sql);

                if($usuario == ""){
                    $err_usuario= "El usuario es obligatorio para iniciar sesion";
                }else{
                    if($resultado -> num_rows == 0){
                        $err_usuario= "El usuario $usuario no existe";
                    }else{
                        $datos_usuario = $resultado -> fetch_assoc();
        
                        if(password_verify($contraseña,$datos_usuario["contraseña"])){
                            session_start();
                            $_SESSION["usuario"] = $usuario;
                            header("location: ../index.php");
                        }else{
                            $err_contraseña="La contraseña no es correcta";
                        }      
                    }
                }  

                if($contraseña == ""){
                    $err_contraseña="La contraseña es obligatoria para iniciar sesion";
                }
            } 
        ?>
        <h1>Iniciar sesion</h1>
        
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
                <input class="btn btn-primary" type="submit" value="Iniciar sesion">
            </div>
            <div class="mb-3">
                <h4>Si no tienes cuenta,</h4>
                <a class="btn btn-secondary" href="registro.php">Registrarse</a>
            </div>
            <div class="mb-3">
                <a class="btn btn-secondary" href="../">Ir a la pagina principal</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>