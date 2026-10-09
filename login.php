
<?php
session_start();
require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT id, username, password
         FROM admins WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $admin = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (
        $admin &&
        password_verify($password, $admin["password"])
    ) {
        session_regenerate_id(true);

        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_username"] = $admin["username"];

        header("Location: admin.php");
        exit;
    }

    $error = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - MovieZone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <div class="logo">MovieZone 🎬</div>
    <a href="index.php">Back to Home</a>
</nav>

<div class="container">
    <div class="form-box" style="max-width:450px;margin:auto;">
        <h1>Admin Login</h1>

        <?php if ($error !== ""): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</div>

</body>
</html>
