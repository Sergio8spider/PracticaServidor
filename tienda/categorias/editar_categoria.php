<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria</title>
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
        <h1>Editar categoria</h1>
        <?php

        $categoria = $_GET["categoria"];
        $sql = "SELECT * FROM categorias WHERE categoria = '$categoria'";
        $resultado = $_conexion -> query($sql);
        
        while($fila = $resultado -> fetch_assoc()) {
            $categoria = $fila["categoria"];
            $descripcion = $fila["descripcion"];
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_descripcion = depurar($_POST["descripcion"]);

            if($tmp_descripcion == ""){
                $err_descripcion = "La descripcion de la categoria es obligatoria";
            }else{
                $patron = "/^[0-9A-Za-zñÑ()áéíóúÁÉÍÓÚ ]+$/";
                if(strlen($tmp_descripcion) > 255){
                    $err_descripcion = "La descripcion de la categoria no puede tener mas de 255 caracteres";
                }else{
                    if(!preg_match($patron, $tmp_descripcion)){
                        $err_descripcion = "La descripcion de la categoria debe contener solo numeros o letras";
                    }else{
                        $descripcion = $tmp_descripcion;
                        $aviso_descripcion="La categoria se ha actualizado correctamente";
                    }
                }

            if(isset($categoria) && isset($descripcion)){
                $sql = "UPDATE categorias SET
                descripcion = '$descripcion'
                WHERE categoria = '$categoria'
            ";
            $_conexion -> query($sql);
            }
        }
    }
        ?>
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <input class="form-control" type="text" name="categoria" disabled value="<?php echo $categoria ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <input class="form-control" type="text" name="descripcion">
                <?php if(isset($err_descripcion)) echo "<span class='error'>$err_descripcion</span>" ?>
                <?php if(isset($aviso_descripcion)) echo "<span class='aviso'>$aviso_descripcion</span>" ?>
            </div>
            <div class="mb-3">
                <input type="hidden" name="categoria" value="<?php echo $categoria ?>">
                <input class="btn btn-primary" type="submit" value="Confirmar">
                <a class="btn btn-secondary" href="index.php">Volver</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>