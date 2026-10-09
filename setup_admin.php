
<?php
require "db.php";

$username = "admin";
$password = password_hash(
    "MovieAdmin123!",
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare(
    "INSERT INTO admins (username, password) VALUES (?, ?)"
);

$stmt->bind_param("ss", $username, $password);
$stmt->execute();

echo "Admin account created successfully!";
echo "<br>Username: admin";
echo "<br>Password: MovieAdmin123!";

$stmt->close();
$conn->close();
?>
