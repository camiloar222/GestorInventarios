<?php
// Incluir el archivo de conexión a la base de datos
include('../INCLUDES/conexion.php'); // Ajusta la ruta según tu estructura de archivos

// Manejar la solicitud de agregar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Manejo de la imagen
    $imagen = $_FILES['imagen']['name'];
    $target_dir = __DIR__ . "/IMAGENESBD/"; // Ajustar la ruta del directorio de imágenes
    $target_file = $target_dir . basename($imagen);

    // Verificar si el directorio existe y es escribible
    if (!is_dir($target_dir)) {
        echo "El directorio no existe.";
    } elseif (!is_writable($target_dir)) {
        echo "El directorio no es escribible.";
    } else {
        // Mover la imagen al directorio deseado
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file)) {
            // Insertar los datos en la base de datos
            $stmt = $conn->prepare("INSERT INTO productos (codigo, nombre, precio, imagen) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $codigo, $nombre, $precio, $imagen);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success' role='alert'>Producto agregado exitosamente.</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error al agregar el producto: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger' role='alert'>Lo siento, hubo un error al subir la imagen.</div>";
        }
    }
}

// Función para cargar productos de la base de datos
function cargarProductos($conn)
{
    $productos = [];
    $result = $conn->query("SELECT codigo, nombre, precio, imagen FROM productos");

    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    return $productos;
}

// Cargar productos existentes
$productos = cargarProductos($conn);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Agregar Productos</title>
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
            font-weight: normal;
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

        /* Animación de las tarjetas */
        .product-card:hover {
            transform: translateY(-10px);
            /* Elevar la tarjeta */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            /* Sombra más intensa */
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
                    <a class="nav-link " href="menu.php"><i class="bi bi-house-door"></i> <strong>Menu</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="agregar_producto.php"><i
                            class="bi bi-plus-circle"></i> <strong>Agregar Productos</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="actualizar_producto.php"><i class="bi bi-pencil-square"></i>
                        <strong>Actualizar Productos</strong></a>
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
                <div class="product-list" id="productos-agregados">
                    <?php foreach ($productos as $producto): ?>
                        <div class="product-card">
                            <img src="<?= 'IMAGENESBD/' . $producto['imagen'] ?>" alt="<?= $producto['nombre'] ?>"
                                class="product-image">
                            <div class="product-info">
                                <h5 class="product-name"><strong><?= $producto['nombre'] ?></strong></h5>
                                <p class="product-code"><strong>Código:</strong> <?= $producto['codigo'] ?></p>
                                <p class="product-price"><strong>Precio:</strong>
                                    $<?= number_format($producto['precio'], 2) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Formulario para agregar productos -->
            <div class="col-md-5">
                <div class="form-container">
                    <h3>Agregar tus Productos Aqui</h3>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="codigo">Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="precio">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="imagen">Imagen del Producto</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                            <div class="product-preview">
                                <img id="preview-img" src="#" alt="Vista previa de la imagen" style="display:none;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('imagen').onchange = function (event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('preview-img').src = URL.createObjectURL(file);
                document.getElementById('preview-img').style.display = 'block';
            }
        };
    </script>
</body>

</html>