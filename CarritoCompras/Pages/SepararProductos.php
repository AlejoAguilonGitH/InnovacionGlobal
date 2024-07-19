<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/EstiloIndexCliente.css">
    <title>Index - CLIENTES</title>
</head>
<body>
    <h1>Lista de Productos</h1>
    <div class="productos-container">
        <?php
      
        $conexion = mysqli_connect("localhost", "root", "", "carrrito") or die("Problemas en la conexión");

      
        $mensaje_exito = array();
        
       
        if (isset($_POST['separar'])) {
            $id_producto = $_POST['separar'];
            
          
            $check_query = "SELECT id FROM carrito WHERE id_producto = $id_producto";
            $check_result = mysqli_query($conexion, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                // Si el producto ya está en el carrito, mostrar mensaje de error
                $mensaje_exito[$id_producto] = "¡Este producto ya está en el carrito!";
            } else {
               
                $insert_query = "INSERT INTO carrito (id_producto, nombre, precio, talla, img, cantidad) SELECT id, nombre, precio, talla, img, 1 FROM productos WHERE id = $id_producto AND cantidad > 0";
                if (mysqli_query($conexion, $insert_query)) {
                    $mensaje_exito[$id_producto] = "Producto añadido al carrito.";
                }
            }
        }

      
        $registros = mysqli_query($conexion, "SELECT id, nombre, precio, talla, img, cantidad FROM productos") or die("Problemas en el select: " . mysqli_error($conexion));

       
        while ($reg = mysqli_fetch_array($registros)) { ?>
            <div class="producto">
                <h2 class="NombreProducto"><?php echo $reg["nombre"]; ?></h2>
                <p class="IdProducto"><strong>ID:</strong> <?php echo $reg['id']; ?></p>
                <p class="PrecioPorducto"><strong>Precio:</strong> $<?php echo $reg['precio']; ?></p>
                <p class="TallaProducto"><strong>Talla:</strong> <?php echo $reg['talla']; ?></p>
                <p class="CantProducto"><strong>Cantidad:</strong> <?php echo ($reg['cantidad'] > 0) ? $reg['cantidad'] : 'Agotado'; ?></p>
                <img src="data:image/png;base64,<?php echo base64_encode($reg['img']); ?>" alt="<?php echo $reg['nombre']; ?>">
                <?php if (isset($mensaje_exito[$reg['id']])) { ?>
                    <p class="mensaje-exito"><?php echo $mensaje_exito[$reg['id']]; ?></p>
                <?php } ?>
                <form action="" method="post">
                    <button type="submit" name="separar" value="<?php echo $reg['id']; ?>" <?php echo ($reg['cantidad'] <= 0) ? 'disabled' : ''; ?>>
                        <?php echo ($reg['cantidad'] > 0) ? 'Separar' : 'Agotado'; ?>
                    </button>
                </form>
            </div>
        <?php
        }
        mysqli_close($conexion);
        ?>
    </div>
    <a href="IndexCompra.php">Ver carrito</a>
</body>
</html>
