<?php
// Incluir el archivo de conexión a la base de datos
include('../INCLUDES/conexion.php');

// Inicializar el carrito
$carrito = [];

// Función para cargar productos de la base de datos
function cargarProductos($conn, $search = '')
{
    $productos = [];
    $query = "SELECT id, codigo, nombre, precio, imagen FROM productos";

    if ($search) {
        $search = $conn->real_escape_string($search);
        $query .= " WHERE codigo LIKE '%$search%' OR nombre LIKE '%$search%' OR precio LIKE '%$search%'";
    }

    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    return $productos;
}

// Cargar productos existentes
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
$productos = cargarProductos($conn, $searchTerm);

// Mensaje de éxito o error
$mensaje = "";

// Procesar venta de producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vender'])) {
    // Obtener el carrito del formulario
    $carrito = json_decode($_POST['carrito'], true);

    // Validar que haya productos en el carrito
    if (!empty($carrito) && is_array($carrito)) {
        $totalVenta = 0; // Para almacenar el total de la venta

        foreach ($carrito as $item) {
            $idProducto = intval($item['id']); // Obtén el ID del producto
            $cantidad = intval($item['cantidad']);
            $precio = floatval($item['precio']);
            $totalVenta += $precio * $cantidad;

            // Inserta cada producto vendido en la tabla de ventas
            $query = "INSERT INTO ventas (id_producto, cantidad, precio) VALUES ('$idProducto', '$cantidad', '$precio')";

            if (!$conn->query($query)) {
                // Manejar error si la inserción falla
                $mensaje = "Error al registrar la venta: " . $conn->error;
                break;
            }
        }

        if ($mensaje === "") {
            // Solo se muestra si no hubo errores
            $mensaje = "Venta registrada correctamente. Total de la venta: $" . number_format($totalVenta, 2);
            // Limpiar el carrito después de la venta
            $carrito = [];
        }
    } else {
        $mensaje = "No hay productos en el carrito.";
    }
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Realizar Ventas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #002c5d;
            padding: 1rem;
        }

        .navbar .navbar-brand {
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .navbar .navbar-brand img {
            max-height: 40px;
            margin-right: 10px;
        }

        .navbar .nav-link {
            color: white;
        }

        .navbar .nav-link:hover {
            background-color: #0041a3;
        }

        .container-fluid {
            margin-top: 20px;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            flex: 1 1 calc(33.333% - 20px);
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            min-height: 300px;
            text-align: center;
            padding: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            /* Cambiado para indicar que es clickeable */
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .product-name,
        .product-code,
        .product-price {
            font-weight: bold;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h3 {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            color: #002c5d;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-danger {
            background-color: #002c5d;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-size: 1.1rem;
        }

        .btn-danger:hover {
            background-color: #0041a3;
        }

        .btn-primary {
            background-color: #002c5d;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            background-color: #0041a3;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .cart {
            margin-top: 20px;
        }

        .cart-total {
            font-weight: bold;
            margin-top: 10px;
        }

        .btn-eliminar {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 0.9rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-eliminar:hover {
            background-color: #c82333;
        }

        .cart-item-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .cart-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .cart-item-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cart-item-info .item-name {
            font-weight: bold;
            font-size: 1.2rem;
            color: #002c5d;
        }

        .cart-item-info .item-price {
            font-weight: bold;
            color: #0041a3;
            margin-top: 5px;
        }

        .cart-item-info .item-quantity {
            color: #555;
            margin-top: 5px;
        }

        .cart-item-actions {
            display: flex;
            align-items: center;
        }

        .cart-item-actions .btn-remove {
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 1rem;
        }

        .cart-item-actions .btn-remove:hover {
            background-color: #c9302c;
        }

        .cart-total {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .cart-change {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">
            <img src="http://localhost/SuperMarket/IMAGES/logo.png" alt="Logo">
            SuperMarket
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="menu.php"><i class="bi bi-house-door"></i> <strong>Menu</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="agregar_producto.php"><i class="bi bi-plus-circle"></i> <strong>Agregar
                            Productos</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="actualizar_producto.php"><i class="bi bi-pencil-square"></i>
                        <strong>Actualizar Productos</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="ventas.php"><i class="bi bi-cart"></i>
                        <strong>Realizar Ventas</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eliminar_producto.php"><i class="bi bi-trash"></i> <strong>Eliminar
                            Productos</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="bi bi-door-open"></i> <strong>Cerrar
                            Sesión</strong></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Lista de productos disponibles -->
            <div class="col-md-7">
                <form method="POST" class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search"
                            placeholder="Buscar por código, nombre o precio"
                            value="<?= htmlspecialchars($searchTerm) ?>">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </form>
                <div class="product-list" id="productos">
                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $producto): ?>
                            <div class="product-card"
                                onclick="agregarProducto(<?= $producto['id'] ?>, '<?= htmlspecialchars($producto['nombre']) ?>', <?= $producto['precio'] ?>)">
                                <img src="../IMAGENESBD/<?= htmlspecialchars($producto['imagen']) ?>"
                                    alt="<?= htmlspecialchars($producto['nombre']) ?>" class="product-image">
                                <div class="product-name"><?= htmlspecialchars($producto['nombre']) ?></div>
                                <div class="product-code">Código: <?= htmlspecialchars($producto['codigo']) ?></div>
                                <div class="product-price">Precio: $<?= number_format($producto['precio'], 2) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No se encontraron productos.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Carrito de compras -->
            <div class="col-md-5 cart">
                <div class="form-container">
                    <h3>Carrito de Compras</h3>
                    <form method="POST">
                        <div id="carrito-container"></div>
                        <input type="hidden" name="carrito" id="carrito" value="">

                        <!-- Campo para ingresar el monto pagado -->
                        <div class="form-group">
                            <label for="monto_pagado">Monto Pagado</label>
                            <input type="number" step="0.01" name="monto_pagado" id="monto_pagado" class="form-control"
                                required oninput="calcularCambio()">
                        </div>

                        <!-- Botón para vender -->
                        <form id="form-venta" onsubmit="event.preventDefault(); vender();">
                            <button type="submit" name="vender" class="btn btn-danger">Vender</button>
                        </form>

                    </form>

                    <!-- Mostrar total y cambio a devolver -->
                    <div class="cart-total" id="cart-total">Total: $0 COP</div>
                    <div class="cart-change" id="cart-change">Cambio a devolver: $0 COP</div>

                    <!-- Mensajes -->
                    <?php if ($mensaje): ?>
                        <div class="alert alert-info" role="alert">
                            <?= htmlspecialchars($mensaje) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>


            <script>
                const carrito = [];

                function agregarProducto(id, nombre, precio) {
                    const existingItem = carrito.find(item => item.id === id);
                    if (existingItem) {
                        existingItem.cantidad++;
                    } else {
                        carrito.push({ id, nombre, precio, cantidad: 1 });
                    }

                    actualizarCarrito();
                }

                function actualizarCarrito() {
                    const carritoContainer = document.getElementById('carrito-container');
                    carritoContainer.innerHTML = '';

                    let total = 0;

                    carrito.forEach(item => {
                        const itemDiv = document.createElement('div');
                        itemDiv.className = 'cart-item-card';
                        itemDiv.innerHTML = `
            <div class="cart-item-info">
                <span class="item-name">${item.nombre}</span>
                <span class="item-price">Precio: ${item.precio.toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}</span>
                <span class="item-quantity">Cantidad: ${item.cantidad}</span>
            </div>
            <div class="cart-item-actions">
                <button class="btn-remove" onclick="eliminarProducto(${item.id})">Eliminar</button>
            </div>
        `;
                        carritoContainer.appendChild(itemDiv);
                        total += item.precio * item.cantidad;
                    });

                    document.getElementById('cart-total').innerHTML = `Total: ${total.toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}`;
                    document.getElementById('carrito').value = JSON.stringify(carrito);
                    calcularCambio();
                }

                function calcularCambio() {
                    const total = carrito.reduce((acc, item) => acc + item.precio * item.cantidad, 0);
                    const montoPagado = parseFloat(document.getElementById('monto_pagado').value) || 0;
                    const cambio = montoPagado - total;

                    document.getElementById('cart-change').innerHTML = `Cambio a devolver: ${(cambio >= 0 ? cambio : 0).toLocaleString('es-CO', { style: 'currency', currency: 'COP' })}`;
                }

                function eliminarProducto(id) {
                    const index = carrito.findIndex(item => item.id === id);
                    if (index !== -1) {
                        carrito.splice(index, 1);
                    }
                    actualizarCarrito();
                }

                function vender() {
                    // Aquí va la lógica para procesar la venta (guardar en la base de datos, etc.)

                    // Mostrar el mensaje de éxito
                    const mensajeExito = document.getElementById('mensaje-exito');
                    mensajeExito.innerHTML = 'La venta fue realizada correctamente.';
                    mensajeExito.style.display = 'block'; // Mostrar el mensaje

                    // Limpiar el carrito y los campos
                    carrito.length = 0; // Vaciar el carrito
                    document.getElementById('monto_pagado').value = ''; // Limpiar monto pagado
                    document.getElementById('cart-total').innerHTML = 'Total: 0'; // Resetear total
                    document.getElementById('cart-change').innerHTML = 'Cambio a devolver: 0'; // Resetear cambio

                    // Ocultar el mensaje después de 1 segundo
                    setTimeout(() => {
                        mensajeExito.style.display = 'none'; // Ocultar el mensaje
                    }, 1000);
                }



            </script>

            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>