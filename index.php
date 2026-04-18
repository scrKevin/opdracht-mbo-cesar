<?php
require 'auth.php';
require 'db.php';
requireLogin();

// Add todo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO todos (user_id, title) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], trim($_POST['title'])]);
    header('Location: index.php');
    exit;
}

// Fetch todos
$stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$todos = $stmt->fetchAll();
?>
<a href="logout.php">Logout</a>

<form method="post">
    <input type="text" name="title" placeholder="New todo" required>
    <button type="submit">Add</button>
</form>

<ul>
<?php foreach ($todos as $todo): ?>
    <li><?= htmlspecialchars($todo['title']) ?></li>
<?php endforeach; ?>
</ul>