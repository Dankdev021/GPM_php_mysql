<!-- views/login.php -->

<?php
$pageTitle = 'Login';
$customCSS = ['login/style.css'];
include 'header.php';
?>

<div class="container" style="display: flex; justify-content: center; align-items: center; height: 100vh; ">
    <div class="login-container" style="background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 400px; width: 100%;">
        <h2 class="text-center">Login</h2>
        <form action="../controllers/UserController.php" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <p class="text-center mt-3">Não tem uma conta? <a href="register.php">Registrar</a></p>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="/oficina/assets/js/bootstrap.min.js"></script>


<?php
$customJS = ['login/script.js'];
?>
