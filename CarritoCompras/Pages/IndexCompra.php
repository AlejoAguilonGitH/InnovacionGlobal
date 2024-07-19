<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/EstiloIndexCompra.css">
    <script src="../Js/DoblaCantidad.js"></script>
    <title>Index - CARRITO</title>
</head>
<body>
    <h1>Carrito de Compras</h1>
    <div class="carrito-container">
        <?php
        $conexion = mysqli_connect("localhost", "root", "", "carrrito") or die("Problemas en la conexion");

        if (isset($_POST['confirmar_compra'])) {
            $productos = json_decode($_POST['productos'], true);
            $total = 0;

            foreach ($productos as $producto) {
                $id_producto = $producto['id'];
                $cantidad_comprar = $producto['cantidad'];
                $nombre_producto = $producto['nombre'];
                $precio = $producto['precio'];
                $total += $precio * $cantidad_comprar;

                $query = "UPDATE productos SET cantidad = cantidad - $cantidad_comprar WHERE id = $id_producto AND cantidad >= $cantidad_comprar";
                if (mysqli_query($conexion, $query)) {
                    $insert_query = "INSERT INTO compras (id_producto, nombre_producto, precio, cantidad) VALUES ($id_producto, '$nombre_producto', $precio, $cantidad_comprar)";
                    mysqli_query($conexion, $insert_query);

                    $query_delete = "DELETE FROM carrito WHERE id_producto = $id_producto";
                    mysqli_query($conexion, $query_delete);
                }
            }
            echo "<p class='mensaje-exito'>Compra realizada con éxito. Total: $" . number_format($total, 2) . "</p>";
        }

        if (isset($_POST['cancelar'])) {
            $id_producto_cancelar = $_POST['id_producto'];
            $query_delete = "DELETE FROM carrito WHERE id_producto = $id_producto_cancelar";
            if (mysqli_query($conexion, $query_delete)) {
                echo "<p class='mensaje-exito'>Producto cancelado correctamente.</p>";
            }
        }

        $registros = mysqli_query($conexion, "SELECT c.*, p.cantidad AS stock FROM carrito c JOIN productos p ON c.id_producto = p.id") or die("Problemas en el select: " . mysqli_error($conexion));

        while ($reg = mysqli_fetch_array($registros)) { ?>
                <div class="producto" data-id="<?php echo $reg['id_producto']; ?>" data-nombre="<?php echo $reg['nombre']; ?>" data-precio="<?php echo $reg['precio']; ?>">
                <img src="data:image/png;base64,<?php echo base64_encode($reg['img']); ?>" alt="<?php echo $reg['nombre']; ?>">
                <h2 class="NombreProducto"><?php echo $reg["nombre"]; ?></h2>
                <p class="IdProducto"><strong>ID:</strong> <?php echo $reg['id_producto']; ?></p>
                <p class="PrecioProducto"><strong>Precio unitario:</strong> $<span class="precio"><?php echo $reg['precio']; ?></span></p>
                <p class="TallaProducto"><strong>Talla:</strong> <?php echo $reg['talla']; ?></p>
                <div class="cantidad-container">
                    <p class="CantidadProducto"><strong>Cantidad disponible:</strong> <?php echo $reg['stock']; ?></p>
                    <label for="cantidad_<?php echo $reg['id_producto']; ?>">Cantidad:</label>
                    <input type="number" id="cantidad_<?php echo $reg['id_producto']; ?>" name="cantidad" min="1" max="<?php echo $reg['stock']; ?>" value="1" required oninput="actualizarPrecio(<?php echo $reg['id_producto']; ?>, <?php echo $reg['precio']; ?>, <?php echo $reg['stock']; ?>)">
                </div>
                <p><strong>Precio total:</strong> $<span id="precio_total_<?php echo $reg['id_producto']; ?>"><?php echo $reg['precio']; ?></span></p>
                <div class="botones">
                    <form action="IndexCompra.php" method="post">
                        <input type="hidden" name="id_producto" value="<?php echo $reg['id_producto']; ?>">
                        <button type="submit" name="cancelar">Cancelar</button>
                    </form>
                </div>
            </div>
        <?php
        }
        mysqli_close($conexion);
        ?>
    </div>
    <button class="modal-button btn" id="Comprar" onclick="mostrarConfirmacion()" >Comprar</button>
    <a href="SepararProductos.php">Volver a la lista de productos</a>

    <div id="confirmModal" class="modal">
    <div class="modal-content">
        <h2>Confirmar Compra</h2>
        <div id="resumenCompra"></div>
        <p><strong>Total: $<span id="totalCompra"></span></strong></p>
        <button class="modal-button btn" onclick="confirmarCompra()">Confirmar</button>
        <button class="modal-button btn" onclick="cerrarModal()">Cancelar</button>
    </div>
</div>

<a href="IndexMisCompras.php">Ir a mi historial de compras</a>

</body>
</html>