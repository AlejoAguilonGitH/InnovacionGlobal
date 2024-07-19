<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/EstiloIndexCompra.css">
    <title>Index - MIS COMPRAS</title>
</head>
<body> 
    <h1>Compras Recientes</h1>
    <div class="compras-recientes-container">
        <?php
        $conexion = mysqli_connect("localhost", "root", "", "carrrito") or die("Problemas en la conexion");

        if (isset($_POST['eliminar_compra'])) {
            $id_compra = $_POST['id_compra'];
            $query_delete = "DELETE FROM compras WHERE id = $id_compra";
            if (mysqli_query($conexion, $query_delete)) {
                echo "<p class='mensaje-exito'>Compra eliminada correctamente.</p>";
            } else {
                echo "<p class='mensaje-error'>Error al eliminar la compra.</p>";
            }
        }

        $registros = mysqli_query($conexion, "SELECT * FROM compras ORDER BY fecha_compra DESC LIMIT 10") or die("Problemas en el select: " . mysqli_error($conexion));

        while ($reg = mysqli_fetch_array($registros)) { ?>
            <div class="compra">
                <h2 class="NombreProducto"><?php echo $reg["nombre_producto"]; ?></h2>
                <p class="IdProducto"><strong>ID:</strong> <?php echo $reg['id_producto']; ?></p>
                <p class="PrecioProducto"><strong>Precio unitario:</strong> $<?php echo $reg['precio']; ?></p>
                <p class="CantidadProducto"><strong>Cantidad comprada:</strong> <?php echo $reg['cantidad']; ?></p>
                <p class="FechaCompra"><strong>Fecha de compra:</strong> <?php echo $reg['fecha_compra']; ?></p>
                
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" name="id_compra" value="<?php echo $reg['id']; ?>">
                    <button type="submit" name="eliminar_compra" class="btn-eliminar">Eliminar</button>
                </form>
            </div>
        <?php
        }
        mysqli_close($conexion);
        ?>
    </div>
    
    <a href="IndexCompra.php">Volver al carrito de compras</a>
</body>
</html>