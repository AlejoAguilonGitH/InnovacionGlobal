function actualizarPrecio(idProducto, precioUnitario) {
    const cantidad = parseFloat(document.getElementById('cantidad_' + idProducto).value);
    const precioTotal = cantidad * precioUnitario;
    document.getElementById('precio_total_' + idProducto).innerText = precioTotal.toFixed(2); 
}

function actualizarPrecio(id, precio, stock) {
    var cantidad = document.getElementById('cantidad_' + id).value;
    if (cantidad > stock) {
        cantidad = stock;
        document.getElementById('cantidad_' + id).value = stock;
    }
    var total = precio * cantidad;
    document.getElementById('precio_total_' + id).textContent = total.toFixed(2);
}

function mostrarConfirmacion() {
    var productos = document.querySelectorAll('.producto');
    var resumen = '';
    var total = 0;

    productos.forEach(function(producto) {
        var id = producto.dataset.id;
        var nombre = producto.dataset.nombre;
        var precio = parseFloat(producto.dataset.precio);
        var cantidad = parseInt(document.getElementById('cantidad_' + id).value);
        var subtotal = precio * cantidad;

        if (cantidad > 0) {
            resumen += `<p>${nombre} - Cantidad: ${cantidad} - Subtotal: $${subtotal.toFixed(2)}</p>`;
            total += subtotal;
        }
    });

    document.getElementById('resumenCompra').innerHTML = resumen;
    document.getElementById('totalCompra').textContent = total.toFixed(2);
    document.getElementById('confirmModal').style.display = 'block';
}

function confirmarCompra() {
    var productos = [];
    document.querySelectorAll('.producto').forEach(function(producto) {
        var id = producto.dataset.id;
        var cantidad = parseInt(document.getElementById('cantidad_' + id).value);
        if (cantidad > 0) {
            productos.push({
                id: id,
                nombre: producto.dataset.nombre,
                precio: parseFloat(producto.dataset.precio),
                cantidad: cantidad
            });
        }
    });

    var form = document.createElement('form');
    form.method = 'POST';
    form.action = 'IndexCompra.php';

    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'confirmar_compra';
    input.value = '1';
    form.appendChild(input);

    var productosInput = document.createElement('input');
    productosInput.type = 'hidden';
    productosInput.name = 'productos';
    productosInput.value = JSON.stringify(productos);
    form.appendChild(productosInput);

    document.body.appendChild(form);
    form.submit();
}

function cerrarModal() {
    document.getElementById('confirmModal').style.display = 'none';
}