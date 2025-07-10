<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("inc/config.php");

$user_id = $_SESSION['user']['id'];
$book_id = $_GET['book_id'] ?? null;

if (!$book_id) {
    echo "Invalid book ID.";
    exit;
}

// Handle comment submission
if (isset($_POST['submit_comment'])) {
    $comment = trim($_POST['comment']);
    if ($comment !== '') {
        $stmt = $conn->prepare("INSERT INTO comments (user_id, book_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $book_id, $comment);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: comments.php?book_id=" . $book_id);
    exit;
}

// Handle delete comment
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $conn->query("DELETE FROM comments WHERE id=$delete_id AND user_id=$user_id");
    header("Location: comments.php?book_id=" . $book_id);
    exit;
}

$book = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM books WHERE id = $book_id"));
$comments = mysqli_query($conn, "SELECT c.*, u.name FROM comments c JOIN users u ON c.user_id = u.id WHERE book_id = $book_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Comments - <?= htmlspecialchars($book['title']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>💬 Comments on <em><?= htmlspecialchars($book['title']) ?></em></h3>

    <form method="post" class="mb-4">
        <textarea name="comment" class="form-control" placeholder="Write your comment..." required></textarea>
        <button type="submit" name="submit_comment" class="btn btn-primary mt-2">Post Comment</button>
    </form>

    <?php while ($c = mysqli_fetch_assoc($comments)): ?>
        <div class="border p-3 mb-2 bg-white rounded">
            <strong><?= htmlspecialchars($c['name']) ?></strong>:
            <span><?= htmlspecialchars($c['comment']) ?></span>
            <div class="small text-muted"><?= $c['created_at'] ?></div>
            <?php if ($c['user_id'] == $user_id): ?>
                <a href="edit_comment.php?id=<?= $c['id'] ?>&book_id=<?= $book_id ?>" class="btn btn-sm btn-warning btn-sm mt-1">Edit</a>
                <a href="comments.php?book_id=<?= $book_id ?>&delete=<?= $c['id'] ?>" class="btn btn-sm btn-danger btn-sm mt-1" onclick="return confirm('Delete comment?')">Delete</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

    <a href="view_books.php" class="btn btn-secondary mt-3">← Back to Books</a>
</div>
</body>
</html>
