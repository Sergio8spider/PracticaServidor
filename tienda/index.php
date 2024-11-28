<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index de la tienda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );    

        require('util/conexion.php');

        session_start();
        if(isset($_SESSION["usuario"])){
            echo "<h1>Bienvenid@ $_SESSION[usuario]</h1>";
        }
    ?>
</head>
<body>
    <div class="container">
        <h1>Tabla de productos</h1>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $nombre = $_POST["nombre"];
                echo "<h1>$nombre</h1>";
                $sql = "DELETE FROM productos WHERE nombre = '$nombre'";
                $_conexion -> query($sql);
            }

            $sql = "SELECT * FROM productos";
            $resultado = $_conexion -> query($sql);
            /**
             * Aplicamos la función query a la conexión, donde se ejecuta la sentencia SQL hecha
             * 
             * El resultado se almacena $resultado, que es un objeto con una estructura parecida
             * a los arrays
             */
        ?>
        <?php if(isset($_SESSION["usuario"])){ ?>
        <a class="btn btn-warning" href="usuario/cerrar_sesion.php">Cerrar sesion</a><br><br>
        <a class="btn btn-secondary" href="productos/nuevo_producto.php">Crear nuevo producto</a>
        <a class="btn btn-secondary" href="categorias/nueva_categoria.php">Crear nueva categoria</a><br><br>
        <?php }else{ ?>
            <a class="btn btn-warning" href="usuario/iniciar_sesion.php">Iniciar sesion</a><br><br>
            <?php } ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoria</th>
                    <th>Stock</th>
                    <th>Descripcion</th>
                    <th>Imagen</th>
                    <?php if(isset($_SESSION["usuario"])){ ?>
                    <th></th>
                    <th></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($fila = $resultado -> fetch_assoc()) {    // trata el resultado como un array asociativo
                        echo "<tr>";
                        echo "<td>" . $fila["nombre"] . "</td>";
                        echo "<td>" . $fila["precio"] . "</td>";
                        echo "<td>" . $fila["categoria"] . "</td>";
                        echo "<td>" . $fila["stock"] . "</td>"; 
                        echo "<td>" . $fila["descripcion"] . "</td>"; 
                        ?>
                        <td>
                            <img width="250" height="200" src="imagenes/<?php echo $fila["imagen"] ?>">
                        </td>
                        <?php if(isset($_SESSION["usuario"])){ ?>
                        <td>
                            <a class="btn btn-primary" 
                               href="editar_producto.php?id_producto=<?php echo $fila["id"] ?>">Editar</a>
                        </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="nombre" value="<?php echo $fila["nombre"] ?>">
                                <input class="btn btn-danger" type="submit" value="Borrar">
                            </form>
                        </td>
                        <?php } ?>
                        <?php
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>