<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include("inc/config.php");

if (!isset($_GET['id'])) {
    header("Location: view_books.php");
    exit;
}

$id = $_GET['id'];

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    $query = "UPDATE books SET title='$title', author='$author', price='$price' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        header("Location: view_books.php");
        exit;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

$book = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM books WHERE id=$id"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book - BookNest</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f0f0;
            padding: 0;
            margin: 0;
        }
        .nav-bar {
            background: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            background: #0056b3;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
        }
        .box {
            background: #fff;
            max-width: 500px;
            margin: 50px auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        input, button {
            padding: 10px;
            width: 100%;
            margin-top: 10px;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
        }
        .error { color: red; }
    </style>
</head>
<body>

<!-- Nav Bar -->
<div class="nav-bar">
    <strong>✏️ Edit Book</strong>
    <div class="nav-links">
        <a href="home.php">🏠 Home</a>
        <a href="view_books.php">📖 View Books</a>
        <a href="add_book.php">➕ Add Book</a>
    </div>
</div>

<!-- Form -->
<div class="box">
    <h2>Edit Book</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
        <input type="number" name="price" step="0.01" value="<?= $book['price'] ?>" required>
        <button type="submit" name="update">Update Book</button>
    </form>
</div>

</body>
</html>
