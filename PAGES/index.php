<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles (opcional) -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: rgba(0, 44, 93, 0.8); /* Color azul oscuro con opacidad */
        }   

        .hero-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 50px 0;
        }

        .hero-text {
            color: #f8f9fa;
            max-width: 50%;
        }

        .hero-text h1 {
            color: #f8f9fa;
            font-size: 3rem;
            font-weight: bold;
        }

        .hero-text p {
            font-size: 1.4rem;
            color: #000000;
        }

        .highlight-box {
            display: inline-block;
            background-color: #ffffff;
            border-radius: 15px;
            padding: 5px 15px;
            font-weight: bold;
            color: #002c5d;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .category-title {
            color: #f8f9fa;
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .category-description {
            text-align: center;
            font-size: 1.4rem;
            margin-bottom: 50px;
            color: #000000;
        }

        .category-card {
            border: none;
            background-color: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-10px);
        }

        .category-card img {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
        }

        .category-card h5 {
            font-size: 1.25rem;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .category-card p {
            color: #333333;
        }

        .highlight {
            color: #002c5d;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="http://localhost/SuperMarket/IMAGES/logo.png" alt="" width="100" height="100" class="d-inline-block align-middle">
                Gestión de Inventarios - SuperMarket.
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Ingresar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Nosotros -->
    <div class="container hero-section">
        <div class="hero-text">
            <h1>Gestión <span class="highlight-box">eficiente</span> de inventario</h1>
            <p>Organiza y gestiona tu inventario de víveres de manera simple y eficaz. Soluciones modernas para tu negocio.</p>
        </div>
        <div class="hero-image">
            <img src="http://localhost/SuperMarket/IMAGES/imagen1.png" alt="Logo">
        </div>
    </div>

    <!-- Sección de Categorías -->
    <div class="container category-section">
        <h1 class="category-title">Puedes implementar <span class="highlight-box">nuestro sistema</span> en diferentes tipos de negocios.</h1>
        <p class="category-description">Organiza, gestiona y controla tus productos de manera eficiente.</p>

        <div class="row">
            <!-- Tarjeta 1 -->
            <div class="col-md-4">
                <div class="category-card text-center">
                    <img src="http://localhost/SuperMarket/IMAGES/tienda.png" alt="Tienda de ropa">
                    <h5>Tienda de ropa</h5>
                    <p>Organiza tus prendas </p>
                </div>
            </div>
            <!-- Tarjeta 2 -->
            <div class="col-md-4">
                <div class="category-card text-center">
                    <img src="http://localhost/SuperMarket/IMAGES/libreria.png" alt="Microempresas">
                    <h5>Libreria</h5>
                    <p>Gestiona fácilmente tu stock </p>
                </div>
            </div>
            <!-- Tarjeta 3 -->
            <div class="col-md-4">
                <div class="category-card text-center">
                    <img src="http://localhost/SuperMarket/IMAGES/licoreria.png" alt="Licorería">
                    <h5>Licorería</h5>
                    <p>Vende y Administra tus bebidas</p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Tarjeta 4 -->
            <div class="col-md-4">
                <div class="category-card text-center">
                    <img src="http://localhost/SuperMarket/IMAGES/ferreteria.png" alt="Ferretería">
                    <h5>Ferretería</h5>
                    <p>Maneja fácil tu inventario</p>
                </div>
            </div>
            <!-- Tarjeta 5 -->
            <div class="col-md-4">
                <div class="category-card text-center">
                    <img src="http://localhost/SuperMarket/IMAGES/casa.png" alt="Panaderías">
                    <h5>Emprendimiento</h5>
                    <p>Clasifica productos y factura fácil</p>
                </div>
            </div>
            <!-- Tarjeta 6 -->
            <div class="col-md-4">
                <div class="category-card text-center">
                    <img src="http://localhost/SuperMarket/IMAGES/farmacia.png" alt="Farmacia">
                    <h5>Farmacia</h5>
                    <p>Administra y organiza tus medicamentos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Footer (opcional) -->
    <footer class="bg-light text-center text-lg-start mt-5 py-4">
        <div class="container">
            <p>&copy; Creado Por Camilo Ardila y Yesli Malaver</p>
        </div>
    </footer>
</body>

</html>
