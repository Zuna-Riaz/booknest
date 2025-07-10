<?php
include("inc/config.php");

$success = $error = "";

if (isset($_POST['register'])) {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role  = $_POST['role'] ?? 'user'; // default to 'user' if not set
 // user-selected role

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "⚠️ Email already registered.";
    } else {
        $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$pass', '$role')";
        if (mysqli_query($conn, $query)) {
            $success = "🎉 Registration successful! <a href='login.php'>Login now</a>";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - BookNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="text-center mb-4">📘 Register at BookNest</h3>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Register As</label>
                    <select name="role" class="form-select" required>
                        <option value="user" selected>User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" name="register" class="btn btn-primary w-100">Sign Up</button>
            </form>

            <p class="text-center mt-3">Already registered? <a href="login.php">Login here</a></p>
        </div>
    </div>
</div>
</body>
</html>
