<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestión de Inventario</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 400px;
            padding: 15px;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 30px;
            color: #333333;
            font-weight: bold;
        }

        .form-control {
            background-color: #f8f9fa;
            border: none;
            border-radius: 5px;
            padding: 15px;
            font-size: 1rem;
        }

        .form-control:focus {
            background-color: #e9ecef;
            box-shadow: none;
        }

        .btn-login {
            background-color: #0056b3;
            color: #ffffff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #003f7f;
        }

        .navbar {
            position: absolute;
            top: 20px;
            width: 100%;
            text-align: center;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="http://localhost/SuperMarket/IMAGES/logo.png" alt="Logo" width="100" height="100"
                    class="d-inline-block align-middle">
                Gestión de Inventarios - SuperMarket.
            </a>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-card">
            <h3>Iniciar Sesión</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                </div>
                <button type="submit" class="btn-login">Ingresar</button>
            </form>

            <?php
            ob_start(); // Iniciar el buffer de salida
            session_start();
            include('../INCLUDES/conexion.php'); //aqui tomamos los datos de la base de datos que conectamos 

          //se verifica la conexion que sea correcta
            if (!$conn) {
                die("Conexión fallida: " . mysqli_connect_error());
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Validar que los campos no estén vacíos y que allan datos en los mismos
                if (empty($_POST['correo']) || empty($_POST['contrasena'])) {
                    echo '<div class="alert alert-warning mt-3">Por favor, ingresa tu correo electrónico y contraseña.</div>';
                    exit();
                }

                $correo = $_POST['correo'];
                $contrasena = $_POST['contrasena'];

                //hacemos la consulta a la base de datos 
                $sql = "SELECT * FROM usuarios WHERE correo = ?";
                $stmt = $conn->prepare($sql);
                
                if (!$stmt) {
                    echo '<div class="alert alert-danger mt-3">Error en la preparación de la consulta.</div>';
                    exit();
                }

                $stmt->bind_param("s", $correo);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();

                    // Verificamos si la contraseña ingresada coincide con la de la base de datos
                    if ($contrasena === $user['contrasena']) { // Comparar directamente sin hashear
                        // Iniciar sesión y guardar el correo del usuario
                        $_SESSION['correo'] = $user['correo'];
                        header(header: "Location: http://localhost/SuperMarket/PAGES/menu.php"); // Redirigir a la página del dashboard
                        exit(); // Detener la ejecución del script
                    } else {
                        echo '<div class="alert alert-danger mt-3">Contraseña incorrecta.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger mt-3">Correo electrónico no encontrado.</div>';
                }
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
