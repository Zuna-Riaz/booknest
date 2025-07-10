<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include("inc/config.php");

// Fetch all books
$query = "SELECT * FROM books ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Prepare comment count array
$comment_counts = [];
$comment_query = mysqli_query($conn, "SELECT book_id, COUNT(*) as count FROM comments GROUP BY book_id");
while ($row = mysqli_fetch_assoc($comment_query)) {
    $comment_counts[$row['book_id']] = $row['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Books - BookNest</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">📚 BookNest</a>
    <div>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Navigation Buttons -->
<div class="container mt-3">
    <div class="d-flex justify-content-between">
        <div>
            <?php if (basename($_SERVER['PHP_SELF']) !== 'home.php') : ?>
                <a href="<?= $_SERVER['HTTP_REFERER'] ?? 'home.php' ?>" class="btn btn-secondary btn-sm">← Back</a>
            <?php endif; ?>
        </div>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="add_book.php" class="btn btn-success btn-sm">➕ Add Book</a>
        <?php endif; ?>
    </div>
</div>

<!-- Book Table -->
<div class="container mt-4">
    <h2 class="text-center mb-4">📖 All Books</h2>
    <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Price (PKR)</th>
                <th>Added On</th>
                <th>💬 Comments</th>
                <?php if ($_SESSION['user']['role'] === 'admin') echo "<th>Actions</th>"; ?>
            </tr>
        </thead>
        <tbody>
        <?php while ($book = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= number_format($book['price'], 2) ?></td>
                <td><?= $book['created_at'] ?></td>

                <!-- Comment Column -->
                <td>
                    <a href="comments.php?book_id=<?= $book['id'] ?>" class="btn btn-sm btn-info">
                        💬 Comment (<?= $comment_counts[$book['id']] ?? 0 ?>)
                    </a>
                </td>

                <!-- Admin Actions -->
                <?php if ($_SESSION['user']['role'] === 'admin') { ?>
                    <td>
                        <a href="edit_book.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_book.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?')">Delete</a>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>
</div>

</body>
</html>
