<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión, de lo contrario lo redirige a login.php
if (!isset($_SESSION['correo'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            background-image: url('http://localhost/SuperMarket/IMAGES/fondo.jpg'); /* Cambia la URL a tu imagen */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(47, 47, 47, 0.5);
            z-index: 1;
        }

        #sidebar {
            background-color: #002c5d;
            height: 100vh;
            color: white;
            padding: 20px;
            position: fixed;
            z-index: 2;
        }

        #sidebar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px; /* Espaciado agregado */
            display: flex; /* Usar flex para alinear icono y texto */
            align-items: center; /* Centrar verticalmente */
            border-radius: 8px; /* Bordes redondeados */
            transition: background-color 0.3s; /* Transición para el hover */
        }

        #sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Fondo en hover */
        }

        .sidebar-header h4 {
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 20px; /* Espacio entre el encabezado y el usuario */
        }

        .user-info img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 10px; /* Espacio debajo de la imagen */
        }

        .user-info h5 {
            font-size: 1.2rem;
            color: #f8f9fa;
            margin-top: 10px;
        }

        .user-info p {
            color: #f8f9fa;
            font-size: 1.2rem;
        }

        .list-group-item {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 1.4rem;
            margin-bottom: 10px; /* Espacio entre los elementos de la lista */
            transition: transform 0.3s; /* Animación de transformación */
        }

        .list-group-item:hover {
            transform: scale(1.02); /* Efecto de aumento en hover */
        }

        .icon {
            margin-right: 10px; /* Espacio entre icono y texto */
            font-size: 1.5rem; /* Tamaño del ícono */
        }

        .content {
            margin-left: 350px;
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .container-h1 {
            color: #f8f9fa;
            font-size: 2.5rem;
            text-align: center;
            margin-top: 150px;
        }

        .highlight {
            color: #002c5d;
        }

        .boxed {
            background-color: white;
            padding: 5px 10px;
            border-radius: 20px;
            display: inline-block;
            margin: 0 5px;
        }
    </style>
</head>

<body>
    <div class="overlay"></div>
    <div id="sidebar">
        <div class="sidebar-header">
            <h4>Hola Lindo</h4>
        </div>
        <div class="user-info text-center py-3">
            <img src="http://localhost/SuperMarket/IMAGES/user.png" alt="Avatar de usuario">
            <h5>Bienvenido</h5>
            <p><?php echo htmlspecialchars($_SESSION['correo']); ?></p> <!-- Mostrando el email del usuario -->
        </div>
        <ul class="list-group">
            <li class="list-group-item"><a href="#"><i class="bi bi-speedometer2 icon"></i>Dashboard</a></li>
            <li class="list-group-item"><a href="agregar_producto.php"><i class="bi bi-people icon"></i>Agregar Productos</a></li>
            <li class="list-group-item"><a href="actualizar_producto.php"><i class="bi bi-box-seam icon"></i>Actualizar Productos</a></li>
            <li class="list-group-item"><a href="eliminar_producto.php"><i class="bi bi-stack icon"></i>Eliminar Productos</a></li>
            <li class="list-group-item"><a href="ventas.php"><i class="bi bi-gear icon"></i>Realizar Ventas</a></li>
            <li class="list-group-item"><a href="logout.php"><i class="bi bi-door-open icon"></i>Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="container container-h1">
            <h1>Bienvenido a <span class="highlight boxed">nuestro sistema</span> de gestión de inventario para tu <span class="highlight boxed">super market</span></h1>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
