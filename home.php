<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>BookNest - Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f0f2f5;
        }
        .navbar {
            background: #007bff;
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-left {
            font-weight: bold;
            font-size: 18px;
        }
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .navbar-right a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .subnav {
            background: #e9ecef;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
        }
        .subnav a {
            text-decoration: none;
            padding: 6px 12px;
            background: #6c757d;
            color: white;
            border-radius: 5px;
            font-size: 14px;
        }
        .subnav a:hover {
            background: #5a6268;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            margin-bottom: 10px;
        }
        .actions a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .actions a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<!-- Main Navbar -->
<div class="navbar">
    <div class="navbar-left">📚 BookNest</div>
    <div class="navbar-right">
        <a href="view_books.php">📖 View Books</a>
        <span>Welcome, <?= htmlspecialchars($user['name']) ?> (<?= $user['role'] ?>)</span>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- Sub Navigation -->
<!-- Sub Navigation -->
<div class="subnav">
    <a href="<?= $_SERVER['HTTP_REFERER'] ?? 'home.php' ?>">← Back</a>
</div>


<!-- Content Container -->
<div class="container">
    <h2>Dashboard</h2>
    <p>You are logged in as <strong><?= $user['role'] ?></strong>.</p>

    <div class="actions">
        <a href="view_books.php">📖 View Books</a>
        <?php if ($user['role'] === 'admin'): ?>
            <a href="add_book.php">➕ Add New Book</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
