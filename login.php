<?php
session_start();
require 'db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            // Login success
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $msg = "Invalid password!";
        }
    } else {
        $msg = "No user found with that email!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Login</h2>
    <?php if ($msg) echo "<div class='alert alert-danger'>$msg</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Login</button>
        <p class="mt-2">Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>
