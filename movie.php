
<?php
require "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    exit("Invalid movie ID.");
}

$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$movie = $stmt->get_result()->fetch_assoc();

if (!$movie) {
    http_response_code(404);
    exit("Movie not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($movie["title"]) ?> - MovieZone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <div class="logo">MovieZone 🎬</div>
    <a href="index.php">← Back to Movies</a>
</nav>

<div class="container">
    <div class="movie-card">
        <?php if (!empty($movie["poster"])): ?>
            <img
                src="<?= htmlspecialchars($movie["poster"]) ?>"
                alt="Movie poster"
                style="width:220px;height:320px;object-fit:cover;"
            >
        <?php endif; ?>

        <div class="movie-info">
            <h1><?= htmlspecialchars($movie["title"]) ?></h1>

            <p>
                <?= htmlspecialchars($movie["genre"]) ?>
                · <?= (int)$movie["release_year"] ?>
                · ⭐ <?= htmlspecialchars((string)$movie["rating"]) ?>/10
            </p>

            <p>
                <strong>Director:</strong>
                <?= htmlspecialchars($movie["director"] ?? "Not provided") ?>
            </p>

            <p>
                <strong>Cast:</strong>
                <?= htmlspecialchars($movie["cast_members"] ?? "Not provided") ?>
            </p>

            <p>
                <strong>Duration:</strong>
                <?= (int)$movie["duration"] ?> minutes
            </p>

            <h3>Synopsis</h3>
            <p>
                <?= nl2br(htmlspecialchars($movie["synopsis"] ?? "")) ?>
            </p>
        </div>
    </div>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
