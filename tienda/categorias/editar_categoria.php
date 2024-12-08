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
        <h1>Editar producto</h1>
        <?php

        $id_producto = $_GET["id_producto"];
        $sql = "SELECT * FROM productos WHERE id = $id_producto";
        $resultado = $_conexion -> query($sql);
        
        while($fila = $resultado -> fetch_assoc()) {
            $nombre = $fila["nombre"];
            $precio = $fila["precio"];
            $categoria = $fila["categoria"];
            $stock = $fila["stock"];
            $descripcion = $fila["descripcion"];
        }

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_nombre = $_POST["nombre"];
            $tmp_precio = $_POST["precio"];
            if(isset($_POST["categoria"])) $tmp_categoria = $_POST["categoria"];
            else $tmp_categoria="";
            $tmp_stock = $_POST["stock"];
            $tmp_descripcion = $_POST["descripcion"];

            $sql = "SELECT * FROM productos";
            $resultado = $_conexion -> query($sql);
            $nombres = [];

            while($fila = $resultado -> fetch_assoc()) {
                array_push($nombres, $fila["nombre"]);
            }

            if($tmp_nombre != ""){
                if(in_array($tmp_nombre,$nombres)){
                    $err_nombre = "Ese producto ya existe";
                }else{
                    $patron = "/^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ: ]+$/";
                    if(strlen($tmp_nombre) > 50 || strlen($tmp_nombre) < 2){
                        $err_nombre = "El nombre debe tener entre 2 y 50 caracteres";
                    }else{
                        if(!preg_match($patron, $tmp_nombre)){
                            $err_nombre = "El nombre debe contener solo numeros o letras";
                        }else{
                            $aviso_nombre=" Se ha actualizado";
                            $sql = "UPDATE productos SET nombre = '$tmp_nombre' WHERE id = $id_producto";
                            $_conexion -> query($sql);
                        }
                    }
                }
            } 

            if($tmp_precio != ""){
                if(!is_numeric($tmp_precio)){
                    $err_precio = "El precio debe ser un numero";
                }else{
                    $patron = "/^[0-9]{1,4}(\.[0-9]{1,2})?$/";
                    if(!preg_match($patron, $tmp_precio)){
                        $err_precio = "El precio debe tener como maximo 4 numeros enteros y 2 decimales";
                    }else{
                        $aviso_precio=" Se ha actualizado";
                        $sql = "UPDATE productos SET precio = $tmp_precio WHERE id = $id_producto";
                        $_conexion -> query($sql);
                    }
                }
            }

            $sql = "SELECT * FROM categorias ORDER BY categoria";
            $resultado = $_conexion -> query($sql);
            $categorias = [];

            while($fila = $resultado -> fetch_assoc()) {
                array_push($categorias, $fila["categoria"]);
            }

            if($tmp_categoria != ""){
                if(!in_array($tmp_categoria,$categorias)){
                    $err_categoria="Esa categoria no existe";
                }else{
                    $aviso_categoria=" Se ha actualizado";
                    $sql = "UPDATE productos SET categoria = '$tmp_categoria' WHERE id = $id_producto";
                    $_conexion -> query($sql);
                }
            }

            if($tmp_stock != ""){
                if(!filter_var($tmp_stock,FILTER_VALIDATE_INT)){
                    $err_stock = "El stock tiene que ser un numero entero";
                } else {
                    if($tmp_stock < 0 || $tmp_stock > 2147483647){
                        $err_stock = "El stock debe estar entre 0 y 2147483647";
                    }else{
                        $aviso_stock=" Se ha actualizado";
                        $sql = "UPDATE productos SET stock = $tmp_stock WHERE id = $id_producto";
                        $_conexion -> query($sql);
                    } 
                }
            }

            if($tmp_descripcion != ""){
                $patron = "/^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]+$/";
                if(strlen($tmp_descripcion) > 255){
                    $err_nombre = "La descripcion debe tener menos de 255 caracteres";
                }else{
                    if(!preg_match($patron, $tmp_descripcion)){
                        $err_nombre = "La descripcion debe contener solo numeros o letras";
                    }else{
                        $aviso_descripcion=" Se ha actualizado";
                        $sql = "UPDATE productos SET descripcion = '$tmp_descripcion' WHERE id = $id_producto";
                        $_conexion -> query($sql);
                    }
                }
            }
        }

        $sql = "SELECT * FROM categorias ORDER BY categoria";
        $resultado = $_conexion -> query($sql);
        $categorias = [];

        while($fila = $resultado -> fetch_assoc()) {
            array_push($categorias, $fila["categoria"]);
        }

        ?>
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <input class="form-control" type="text" name="nombre">
                <?php if(isset($err_categoria)) echo "<span class='error'>$err_categoria</span>" ?>
                <?php if(isset($aviso_categoria)) echo "<span class='aviso'>$aviso_categoria</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio (maximo 4 numeros enteros y 2 decimales)</label>
                <input class="form-control" type="text" name="precio">
                <?php if(isset($err_descripcion)) echo "<span class='error'>$err_descripcion</span>" ?>
                <?php if(isset($aviso_descripcion)) echo "<span class='aviso'>$aviso_descripcion</span>" ?>
            </div>
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" value="Insertar">
                <a class="btn btn-secondary" href="index.php">Volver</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>