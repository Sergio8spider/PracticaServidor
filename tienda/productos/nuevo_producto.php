<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('../util/conexion.php');
    ?>
</head>
<body>
    <div class="container">
        <h1>Nuevo producto</h1>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_nombre = $_POST["nombre"];
            $tmp_precio = $_POST["precio"];
            $tmp_categoria = $_POST["categoria"];
            $tmp_stock = $_POST["stock"];
            $tmp_descripcion = $_POST["descripcion"];

            if($tmp_nombre == ""){
                $err_nombre = "El nombre es obligatorio";
            }else{
                $patron = "/^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]+$/";
                if(strlen($tmp_nombre) > 50 || strlen($tmp_nombre) < 2){
                    $err_nombre = "El nombre debe tener entre 2 y 50 caracteres";
                }else{
                    if(!preg_match($patron, $tmp_nombre)){
                        $err_nombre = "El nombre debe contener solo numeros o letras";
                    }else{
                        $nombre = $tmp_nombre;
                    }
                }
            } 

            if($tmp_precio == ""){
                $err_precio = "El precio es obligatorio";
            }else{
                $patron = "/^[0-9]{1,4}(\.[0-9]{1,2})?$/";
                if(!preg_match($patron, $tmp_precio)){
                    $err_precio = "El precio debe tener como maximo 4 numeros enteros y 2 decimales";
                }else{
                    $precio = $tmp_precio;
                }
            } 

            $sql = "SELECT * FROM categorias ORDER BY categoria";
            $resultado = $_conexion -> query($sql);
            $categorias = [];

            while($fila = $resultado -> fetch_assoc()) {
                array_push($categorias, $fila["categoria"]);
            }

            if($tmp_categoria = ""){
                $err_categoria = "La categoria es obligatoria";
            }else{
                if(!in_array($tmp_categoria,$categorias)){
                    $err_categoria="Esa categoria no existe";
                }else{
                    $categoria=$tmp_categoria;
                }
            }

            /**
             * $_FILES -> que es un array BIDIMENSIONAL
             */
            //var_dump($_FILES["imagen"]);
            $nombre_imagen = $_FILES["imagen"]["name"];
            $ubicacion_temporal = $_FILES["imagen"]["tmp_name"];
            $ubicacion_final = "./imagenes/$nombre_imagen";

            move_uploaded_file($ubicacion_temporal, $ubicacion_final);

            $sql = "INSERT INTO productos (nombre, precio, categoria, stock, imagen, descripcion) 
                VALUES ('$nombre', '$precio', $categoria, $stock, '$ubicacion_final','$descripcion')";

            $_conexion -> query($sql);
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
                <label class="form-label">Nombre</label>
                <input class="form-control" type="text" name="nombre">
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input class="form-control" type="text" name="precio">
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select class="form-select" name="categoria">
                    <option value="" selected disabled hidden>--- Elige la categoria ---</option>
                    <?php
                    foreach($categorias as $categoria) { ?>
                        <option value="<?php echo $categoria ?>">
                            <?php echo $categoria ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input class="form-control" type="text" name="stock">
            </div>
            <div class="mb-3">
                <label class="form-label">Imagen</label>
                <input class="form-control" type="file" name="imagen">
            </div>
            <div class="mb-3">
                <label class="form-label">Descripcion</label>
                <input class="form-control" type="file" name="descripcion">
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