<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficina Mecânica</title>
    <link rel="stylesheet" href="/oficina/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/oficina/assets/css/header/style.css">
    <link rel="stylesheet" href="/oficina/assets/css/footer/style.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background: #000;
            color: #ffffff;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/oficina/assets/images/workshop-background.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            z-index: 0;
            width: 100%;
        }

        .content h1 {
            font-size: 3rem;
        }

        .content p {
            font-size: 1.5rem;
            margin: 20px 0;
        }

        .navbar {
            margin-bottom: 0;
        }

        #loginModal, #registerModal {
            color: #000;
        }

        #acesso {
            padding: 25px;
            background-color: #708090;
            border-radius: 20px;
            box-shadow: 5px 5px 5px black;
        }

    </style>
    <link rel="icon" href="/oficina/assets/images/favicon.png" type="image/png">
</head>
<body>
    <div class="background"></div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/oficina/index.php">
            <img src="/oficina/assets/images/favicon.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Oficina
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/oficina/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#registerModal">Registrar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="views/materials/index.php">Catálogo</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="content">
        <div id="acesso">
            <h1>Bem-vindo à nossa Oficina Mecânica</h1>
            <p>Oferecemos uma ampla gama de serviços de alta qualidade para manter seu veículo em perfeito estado.</p>
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#registerModal">Registre-se Agora</a>
            <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#loginModal">Faça Login</a>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/oficina/controllers/UserController.php?action=login" method="POST">
                        <div class="form-group">
                            <label for="loginUsername">Username</label>
                            <input type="text" name="username" class="form-control" id="loginUsername" required>
                        </div>
                        <div class="form-group">
                            <label for="loginPassword">Password</label>
                            <input type="password" name="password" class="form-control" id="loginPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Registrar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/oficina/controllers/UserController.php?action=register" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" class="form-control" required>
                                <option value="cliente">Cliente</option>
                                <option value="vendedor">Mecânico</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Registrar</button>
                    </form>
                    <p class="text-center mt-3">Possui uma conta? <a href="" data-dismiss="modal" data-toggle="modal" data-target="#loginModal">Login</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/oficina/assets/js/bootstrap.min.js"></script>
</body>
</html>
