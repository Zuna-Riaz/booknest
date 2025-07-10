<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include("inc/config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM books WHERE id=$id";
    mysqli_query($conn, $query);
}

header("Location: view_books.php");
exit;
?>
