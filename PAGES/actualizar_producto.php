<?php
// Incluir el archivo de conexión a la base de datos
include('../INCLUDES/conexion.php'); // Ajusta la ruta según tu estructura de archivos

// Función para cargar productos de la base de datos
function cargarProductos($conn, $search = '')
{
    $productos = [];
    $query = "SELECT codigo, nombre, precio, imagen FROM productos";

    if ($search) {
        // Escapar el término de búsqueda para evitar inyección SQL
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

// Procesar actualización de producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen']['name'];

    // Definir la ruta de la carpeta de imágenes
    $carpetaImagenes = $_SERVER['DOCUMENT_ROOT'] . '/SuperMarket/IMAGENESBD/';

    // Verificar si la carpeta existe, si no, crearla
    if (!is_dir($carpetaImagenes)) {
        mkdir($carpetaImagenes, 0777, true);
    }

    // Si se sube una nueva imagen
    if ($imagen) {
        $rutaImagen = $carpetaImagenes . basename($imagen);
        // Mueve el archivo temporal a la ubicación deseada
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            $query = "UPDATE productos SET nombre=?, precio=?, imagen=? WHERE codigo=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sdss", $nombre, $precio, $imagen, $codigo);
        } else {
            $error = error_get_last();
            $mensaje = "Error al mover la imagen: " . $error['message'];
        }
    } else {
        // Si no se sube una nueva imagen
        $query = "UPDATE productos SET nombre=?, precio=? WHERE codigo=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sds", $nombre, $precio, $codigo);
    }

    if (isset($stmt) && $stmt->execute()) {
        $mensaje = "Producto actualizado correctamente.";
        // Redirigir a la misma página para evitar el reenvío del formulario
        header("Location: actualizar_producto.php");
        exit; // Asegúrate de salir después de redirigir
    } else {
        $mensaje = "Error al actualizar el producto: " . $stmt->error;
    }

    if (isset($stmt)) {
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Actualizar Productos</title>
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
            justify-content: center;
            /* Centra las tarjetas en la lista */
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            flex: 1 1 calc(33.333% - 20px);
            /* Mantiene tres tarjetas por fila */
            max-width: 300px;
            /* Establece un ancho máximo para las tarjetas */
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            min-height: 300px;
            /* Tamaño uniforme */
            text-align: center;
            padding: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-image {
            width: 100%;
            height: 200px;
            /* Mantener una altura uniforme */
            object-fit: cover;
            /* Asegurarse de que la imagen cubra el área sin distorsión */
            border-bottom: 1px solid #ddd;
            border-radius: 8px;
            /* Bordes redondeados para las imágenes */
        }

        .product-name,
        .product-code,
        .product-price {
            font-weight: bold;
        }

        .product-preview img {
            max-width: 100px;
            border-radius: 8px;
            margin-top: 10px;
        }

        /* Animación de las tarjetas */
        .product-card:hover {
            transform: translateY(-10px);
            /* Elevar la tarjeta */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Sombra más intensa */
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
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

        .product-preview img {
            max-width: 100px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
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
                    <a class="nav-link active" aria-current="page" href="actualizar_producto.php"><i
                            class="bi bi-pencil-square"></i> <strong>Actualizar Productos</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="eliminar_producto.php"><i class="bi bi-trash"></i> <strong>Eliminar
                            Productos</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ventas.php"><i class="bi bi-cart"></i> <strong>Realizar
                            Ventas</strong></a>
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
            <!-- Lista de productos añadidos -->
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
                                onclick="mostrarFormulario('<?= $producto['codigo'] ?>', '<?= htmlspecialchars($producto['nombre']) ?>', '<?= htmlspecialchars($producto['precio']) ?>', '<?= htmlspecialchars($producto['imagen']) ?>')">
                                <img src="../IMAGENESBD/<?= htmlspecialchars($producto['imagen']) ?>"
                                    alt="<?= htmlspecialchars($producto['nombre']) ?>" class="product-image">
                                <h5 class="product-name"><?= htmlspecialchars($producto['nombre']) ?></h5>
                                <p class="product-code">Código: <?= htmlspecialchars($producto['codigo']) ?></p>
                                <p class="product-price">Precio: $<?= number_format($producto['precio'], 2) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No se encontraron productos.</p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Formulario de actualización de productos -->
            <div class="col-md-5">
                <div class="form-container" id="formularioActualizar">
                    <h3>Actualizar Producto</h3>
                    <?php if ($mensaje): ?>
                        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
                    <?php endif; ?>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="codigo" id="codigo" required>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="number" name="precio" id="precio" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="imagen">Imagen:</label>
                            <input type="file" name="imagen" id="imagen" class="form-control">
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Actualizar Producto</button>
                        <button type="button" class="btn btn-danger mt-3" onclick="cerrarFormulario()">Cancelar</button>

                    </form>
                    <div class="product-preview">
                        <h5>Vista previa:</h5>
                        <img id="imgPreview" src="" alt="Vista previa de la imagen" style="display:none;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrarFormulario(codigo, nombre, precio, imagen) {
            document.getElementById('codigo').value = codigo;
            document.getElementById('nombre').value = nombre;
            document.getElementById('precio').value = precio;
            const imgPreview = document.getElementById('imgPreview');
            imgPreview.src = '../IMAGENESBD/' + imagen;
            imgPreview.style.display = 'block';
            document.getElementById('formularioActualizar').style.display = 'block';
        }


        function cerrarFormulario() {
            document.getElementById('formularioActualizar').style.display = 'none';
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>