<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include("inc/config.php");

if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    $query = "INSERT INTO books (title, author, price) VALUES ('$title', '$author', '$price')";
    if (mysqli_query($conn, $query)) {
        $success = "✅ Book added successfully!";
    } else {
        $error = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book - BookNest</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">📚 BookNest</a>
    <div>
      <a href="view_books.php" class="btn btn-light btn-sm me-2">📖 View Books</a>
      <a href="<?= $_SERVER['HTTP_REFERER'] ?? 'home.php' ?>" class="btn btn-outline-light btn-sm">← Back</a>
    </div>
  </div>
</nav>

<!-- Main Container -->
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body">
            <h4 class="mb-4 text-center">➕ Add New Book</h4>

            <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Book Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Author Name</label>
                    <input type="text" name="author" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (PKR)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>

                <button type="submit" name="add" class="btn btn-success w-100">Add Book</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
