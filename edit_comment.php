<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include("inc/config.php");

$user_id = $_SESSION['user']['id'];
$comment_id = $_GET['id'] ?? null;
$book_id = $_GET['book_id'] ?? null;

if (!$comment_id || !$book_id) {
    echo "Invalid request.";
    exit;
}

$comment = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM comments WHERE id = $comment_id AND user_id = $user_id"));

if (!$comment) {
    echo "Comment not found or unauthorized.";
    exit;
}

if (isset($_POST['update_comment'])) {
    $new_comment = trim($_POST['comment']);
    $stmt = $conn->prepare("UPDATE comments SET comment=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sii", $new_comment, $comment_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: comments.php?book_id=" . $book_id);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Comment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h4>Edit Your Comment</h4>
    <form method="post">
        <textarea name="comment" class="form-control" required><?= htmlspecialchars($comment['comment']) ?></textarea>
        <button type="submit" name="update_comment" class="btn btn-primary mt-2">Update</button>
        <a href="comments.php?book_id=<?= $book_id ?>" class="btn btn-secondary mt-2">Cancel</a>
    </form>
</div>
</body>
</html>
