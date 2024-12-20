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

        require('../util/conexion.php');

        session_start();
        if (isset($_SESSION["usuario"])) { ?>
            <h2>Bienvenid@ <?php echo $_SESSION["usuario"] ?> </h2>
            <a class="btn btn-warning" href="../usuario/cerrar_sesion.php">Cerrar sesion</a>
            <a class="btn btn-success" href="../usuario/cambiar_credenciales.php?usuario=<?php echo $_SESSION["usuario"] ?>">Cambiar credenciales</a>
        <?php } else {
            header("location: ../usuario/iniciar_sesion.php");
            exit;
        } ?>
    <style>
        .aviso{
        color:green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tabla de los productos</h1>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $nombre = $_POST["nombre"];
                $sql = "DELETE FROM productos WHERE nombre = '$nombre'";
                $_conexion -> query($sql);
                $aviso_borrar = "Producto borrado correctamente";
            }

            $sql = "SELECT * FROM productos";
            $resultado = $_conexion -> query($sql);
        ?>
        <?php if(isset($aviso_borrar)) echo "<span class='aviso'>$aviso_borrar</span> <br><br>" ?>
        <a class="btn btn-outline-info" href="nuevo_producto.php">Crear producto</a>
        <a class="btn btn-outline-secondary" href="../">Volver a la pagina principal</a><br><br>
        <table class="table table-striped table-hover table-info">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoria</th>
                    <th>Stock</th>
                    <th>Descripcion</th>
                    <th>Imagen</th>
                    <th></th>
                    <th></th>
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
                            <img width="250" height="200" src="../imagenes/<?php echo $fila["imagen"] ?>">
                        </td>
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