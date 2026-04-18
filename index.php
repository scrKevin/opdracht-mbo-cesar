<?php
require 'auth.php';
require 'db.php';
requireLogin();

// Add todo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $stmt = $pdo->prepare("INSERT INTO todos (user_id, title) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], trim($_POST['title'])]);
    header('Location: index.php');
    exit;
}

// Toggle complete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle'])) {
    $stmt = $pdo->prepare("UPDATE todos SET completed = NOT completed WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['toggle'], $_SESSION['user_id']]);
    header('Location: index.php');
    exit;
}

// Fetch todos
$stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$todos = $stmt->fetchAll();

// Delete todo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['delete'], $_SESSION['user_id']]);
    header('Location: index.php');
    exit;
}
?>
<a href="logout.php">Logout</a>

<form method="post">
    <input type="text" name="title" placeholder="New todo" required>
    <button type="submit">Add</button>
</form>

<ul>
<?php foreach ($todos as $todo): ?>
<li style="<?= $todo['completed'] ? 'text-decoration: line-through; color: grey;' : '' ?>">
    <?= htmlspecialchars($todo['title']) ?>
    <form method="post" style="display:inline;">
        <input type="hidden" name="toggle" value="<?= $todo['id'] ?>">
        <button type="submit"><?= $todo['completed'] ? 'Undo' : 'Done' ?></button>
    </form>
    <form method="post" style="display:inline;">
        <input type="hidden" name="delete" value="<?= $todo['id'] ?>">
        <button type="submit">Delete</button>
    </form>
</li>
<?php endforeach; ?>
</ul>