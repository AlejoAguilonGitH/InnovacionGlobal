<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/EstilosAdmin.css">
    <title>Index - ADMIN</title>
</head>
<body>
    <section class="Formularios">
        <form action="PanelAdmin.php" method="post" enctype="multipart/form-data" class="InsercionImagen">

            <h1>Insertar Producto</h1>
            <input type="text" class="form-control" required name="nombre" placeholder="Ingrese el nombre">
            <input type="number" class="form-control" required name="precio" placeholder="Ingrese el precio">

            <input type="text" class="form-control" required name="talla" placeholder="Ingrese la talla">
            <input type="number" class="form-control" required name="cantidad" placeholder="Ingrese la cantidad">

            <input type="file" class="form-control" required name="img">
            <input type="submit" value="ENVIAR" name="enviarImg">
            
        </form>

        <form action="PanelAdmin.php" method="post" class="Borrado">

            <h1>Borrar Producto por ID</h1>
            <input type="number" class="form-control" required name="id" placeholder="Ingrese el ID del producto a borrar">
            <input type="submit" value="BORRAR" name="borrarImg">

        </form>

        <form action="PanelAdmin.php" method="post" enctype="multipart/form-data" class="Update">

            <h1>Actualizar Producto</h1>
            <input type="number" class="form-control" required name="id" placeholder="ID del producto a actualizar">
            <input type="text" class="form-control" name="nombre" placeholder="Nuevo nombre">
            <input type="number" class="form-control" name="precio" placeholder="Nuevo precio">
            <input type="text" class="form-control" name="talla" placeholder="Nueva talla">
            <input type="number" class="form-control" name="cantidad" placeholder="Nueva cantidad">
            <input type="file" class="form-control" name="nuevaimg">
            <input type="submit" value="ACTUALIZAR" name="actualizar">

        </form>
    </section>

    <section class="BusquedaMuestra">

        <div class="arriba">
            <form action="PanelAdmin.php" method="get" class="BusquedaImagen">

                <h1>Buscar Producto por ID</h1>
                <input type="number" class="form-control" required name="id" placeholder="Ingrese el ID del producto">
                <input type="submit" value="BUSCAR" name="buscarImg">

            </form>

            <?php
            if (isset($_REQUEST['buscarImg'])) {
                $conexion = mysqli_connect("localhost", "root", "", "carrrito") or die("Problemas en la conexion");
                $id = intval($_REQUEST['id']);
                $buscarQuery = "SELECT id, nombre, precio, talla, img, cantidad FROM productos WHERE id = $id";
                $resultado = mysqli_query($conexion, $buscarQuery) or die("Problemas en el select: " . mysqli_error($conexion));

                if ($reg = mysqli_fetch_array($resultado)) { ?>
                    <table border="1" class="sleccionProductos">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Talla</th>
                            <th>Imagen</th>
                            <th>Cantidad</th>
                        </tr>
                        <tr>
                            <td><?php echo $reg['id']; ?></td>
                            <td><?php echo $reg['nombre']; ?></td>
                            <td><?php echo $reg['precio']; ?></td>
                            <td><?php echo $reg['talla']; ?></td>
                            <td><img height="200px" src="data:image/png;base64,<?php echo base64_encode($reg['img']); ?>" /></td>
                            <td><?php echo $reg['cantidad']; ?></td>
                        </tr>
                    </table>
                <?php
                } else {
                    echo "No se encontró un producto con el ID $id.";
                }
                mysqli_close($conexion);
            }
            ?>
        </div>

        <div class="abajo">
            <?php
            $conexion = mysqli_connect("localhost", "root", "", "carrrito") or die("Problemas en la conexion");
            $registros = mysqli_query($conexion, "SELECT id, nombre, precio, talla, img, cantidad FROM productos") or die("Problemas en el select: " . mysqli_error($conexion));
            ?>

            <h1>Lista de Productos</h1>
            <table border="1" class="MuestraProductos">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Talla</th>
                    <th>Imagen</th>
                    <th>Cantidad</th>
                </tr>
                <?php
                while ($reg = mysqli_fetch_array($registros)) { ?>
                    <tr>
                        <td><?php echo $reg['id']; ?></td>
                        <td><?php echo $reg['nombre']; ?></td>
                        <td><?php echo $reg['precio']; ?></td>
                        <td><?php echo $reg['talla']; ?></td>
                        <td><img height="100px" width="100px" src="data:image/png;base64,<?php echo base64_encode($reg['img']); ?>" /></td>
                        <td><?php echo $reg['cantidad']; ?></td>
                    </tr>
                <?php
                }
                mysqli_close($conexion);
                ?>
            </table>
        </div>
    </section>
    <?php
// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "carrrito") or die("Problemas en la conexion");

// Insertar producto
if (isset($_REQUEST['enviarImg'])) {
    $nombre = $_REQUEST['nombre'];
    $precio = $_REQUEST['precio'];
    $talla = $_REQUEST['talla'];
    $cantidad = $_REQUEST['cantidad'];
    $img = addslashes(file_get_contents($_FILES['img']['tmp_name']));

    $insertQuery = "INSERT INTO productos (nombre, precio, talla, img, cantidad) VALUES ('$nombre', $precio, '$talla', '$img', $cantidad)";
    mysqli_query($conexion, $insertQuery) or die("Problemas en el insert: " . mysqli_error($conexion));
}

// Borrar producto
if (isset($_REQUEST['borrarImg'])) {
    $id = intval($_REQUEST['id']);
    $deleteQuery = "DELETE FROM productos WHERE id = $id";
    mysqli_query($conexion, $deleteQuery) or die("Problemas en el delete: " . mysqli_error($conexion));
}

// Actualizar producto
if (isset($_REQUEST['actualizar'])) {
    $id = intval($_REQUEST['id']);
    $updateFields = [];

    if (!empty($_REQUEST['nombre'])) {
        $updateFields[] = "nombre = '" . mysqli_real_escape_string($conexion, $_REQUEST['nombre']) . "'";
    }

    if (!empty($_REQUEST['precio'])) {
        $updateFields[] = "precio = " . intval($_REQUEST['precio']);
    }

    if (!empty($_REQUEST['talla'])) {
        $updateFields[] = "talla = '" . mysqli_real_escape_string($conexion, $_REQUEST['talla']) . "'";
    }

    if (!empty($_REQUEST['cantidad'])) {
        $updateFields[] = "cantidad = " . intval($_REQUEST['cantidad']);
    }

    if (!empty($_FILES['nuevaimg']['tmp_name'])) {
        $nueva_imagen = addslashes(file_get_contents($_FILES['nuevaimg']['tmp_name']));
        $updateFields[] = "img = '$nueva_imagen'";
    }

    if (!empty($updateFields)) {
        $updateQuery = "UPDATE productos SET " . implode(', ', $updateFields) . " WHERE id = $id";
        mysqli_query($conexion, $updateQuery) or die("Problemas en el update: " . mysqli_error($conexion));
    }
}

mysqli_close($conexion);
?>
</body>
</html>
