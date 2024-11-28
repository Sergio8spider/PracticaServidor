<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva categoria</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Nueva categoria</h1>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_categoria = $_POST["categoria"];
            $tmp_descripcion = $_POST["descripcion"];

            $sql = "SELECT * FROM categorias";
            $resultado = $_conexion -> query($sql);
            $categorias = [];

            while($fila = $resultado -> fetch_assoc()) {
                array_push($categorias, $fila["categoria"]);
            }

            if($tmp_categoria == ""){
                $err_categoria = "El nombre de la categoria es obligatoria";
            }else if(in_array($tmp_categoria,$categorias)){
                $err_categoria = "Esa categoria ya existe";
            }else{
                $patron = "/^[0-9A-Za-zñÑ()áéíóúÁÉÍÓÚ ]+$/";
                if(strlen($tmp_categoria) > 30 && strlen($tmp_categoria) < 2){
                    $err_categoria = "El nombre de la categoria debe tener entre 2 y 30 caracteres";
                }else{
                    if(!preg_match($patron, $tmp_categoria)){
                        $err_categoria = "El nombre de la categoria debe contener solo numeros o letras";
                    }else{
                        $categoria = $tmp_categoria;
                    }
                }
            }   

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
                    }
                }
            }   

            if(isset($categoria) && isset($descripcion)){
                $sql = "INSERT INTO categorias (categoria,descripcion) 
                VALUES ('$categoria', '$descripcion')";

                $_conexion -> query($sql);
            }   
        }
 
        ?>
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <input class="form-control" type="text" name="categoria">
                <?php if(isset($err_categoria)) echo "<span class='error'>$err_categoria</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <input class="form-control" type="text" name="descripcion">
                <?php if(isset($err_descripcion)) echo "<span class='error'>$err_descripcion</span>" ?>
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Crear">
                <a class="btn btn-secondary" href="../">Volver</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>