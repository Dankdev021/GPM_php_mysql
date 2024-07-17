<!-- views/register.php -->

<?php
$pageTitle = 'Register';
$customCSS = ['register/style.css'];
include 'header.php';
?>

<div class="container">
    <div class="register-container">
        <h2 class="text-center">Register</h2>
        <form action="/oficina/controllers/UserController.php" method="POST"> <!-- Caminho absoluto -->
            <input type="hidden" name="action" value="register">
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
                    <option value="vendedor">Vendedor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <p class="text-center mt-3">Possui uma conta? <a href="login.php">Login</a></p>
    </div>
</div>

<?php
$customJS = ['register/script.js'];
include 'footer.php';
?>
