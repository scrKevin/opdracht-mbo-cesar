<?php
require 'auth.php';
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([trim($_POST['username'])]);
        $user = $stmt->fetch();

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit;
        }

        $error = 'Invalid credentials.';
    } catch (PDOException $e) {
        error_log('Login failed: ' . $e->getMessage());
        $error = 'Something went wrong while logging in. Please try again.';
    }
}
?>
<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<a href="register.php">Register</a>