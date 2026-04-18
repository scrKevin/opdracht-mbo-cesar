<?php
require 'auth.php';
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);

        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        error_log('Registration failed: ' . $e->getMessage());
        $error = 'Something went wrong while registering. Please try again.';
    }
}
?>
<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
<a href="login.php">Already have an account? Login</a>
