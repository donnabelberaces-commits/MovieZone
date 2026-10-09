
<?php
session_start();
require "db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

function uploadPoster(): ?string {
    if (!isset($_FILES["poster"]) ||
        $_FILES["poster"]["error"] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES["poster"]["error"] !== UPLOAD_ERR_OK) {
        throw new RuntimeException("Upload failed.");
    }

    if ($_FILES["poster"]["size"] > 3 * 1024 * 1024) {
        throw new RuntimeException("Maximum image size is 3 MB.");
    }

    $image = getimagesize($_FILES["poster"]["tmp_name"]);

    if ($image === false) {
        throw new RuntimeException("Invalid image file.");
    }

    $allowed = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"
    ];

    $mime = $image["mime"];

    if (!isset($allowed[$mime])) {
        throw new RuntimeException("Use JPG, PNG, or WEBP.");
    }

    $folder = __DIR__ . "/images/";

    if (!is_dir($folder) &&
        !mkdir($folder, 0755, true)) {
        throw new RuntimeException("Cannot create images folder.");
    }

    $filename = bin2hex(random_bytes(16))
        . "." . $allowed[$mime];

    if (!move_uploaded_file(
        $_FILES["poster"]["tmp_name"],
        $folder . $filename
    )) {
        throw new RuntimeException("Could not save poster.");
    }

    return "images/" . $filename;
}

$message = "";
$error = "";
$editMovie = null;

// Add or update movie
if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["save_movie"])) {

    $id = (int)($_POST["id"] ?? 0);
    $title = trim($_POST["title"] ?? "");
    $genre = trim($_POST["genre"] ?? "");
    $year = filter_var(
        $_POST["release_year"] ?? "",
        FILTER_VALIDATE_INT
    );
    $rating = filter_var(
        $_POST["rating"] ?? "",
        FILTER_VALIDATE_FLOAT
    );
    $duration = filter_var(
        $_POST["duration"] ?? "0",
        FILTER_VALIDATE_INT
    );

    $director = trim($_POST["director"] ?? "");
    $cast = trim($_POST["cast_members"] ?? "");
    $synopsis = trim($_POST["synopsis"] ?? "");

    if (
        $title === "" || $genre === "" ||
        $year === false || $year < 1888 || $year > 2100 ||
        $rating === false || $rating < 0 || $rating > 10 ||
        $duration === false || $duration < 0 || $duration > 1000
    ) {
        $error = "Please check the movie information.";
    } else {
        try {
            $poster = uploadPoster();

            if ($id > 0 && $poster !== null) {
                $stmt = $conn->prepare(
                    "UPDATE movies SET
                     title=?, genre=?, release_year=?, rating=?,
                     duration=?, director=?, cast_members=?,
                     synopsis=?, poster=? WHERE id=?"
                );

                $stmt->bind_param(
                    "ssidissssi",
                    $title, $genre, $year, $rating, $duration,
                    $director, $cast, $synopsis, $poster, $id
                );

            } elseif ($id > 0) {
                $stmt = $conn->prepare(
                    "UPDATE movies SET
                     title=?, genre=?, release_year=?, rating=?,
                     duration=?, director=?, cast_members=?,
                     synopsis=? WHERE id=?"
                );

                $stmt->bind_param(
                    "ssidisssi",
                    $title, $genre, $year, $rating, $duration,
                    $director, $cast, $synopsis, $id
                );

            } else {
                $posterPath = $poster ?? "";

                $stmt = $conn->prepare(
                    "INSERT INTO movies
                     (title, genre, release_year, rating, duration,
                      director, cast_members, synopsis, poster)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "ssidissss",
                    $title, $genre, $year, $rating, $duration,
                    $director, $cast, $synopsis, $posterPath
                );
            }

            $stmt->execute();
            $stmt->close();

            header("Location: admin.php?success=1");
            exit;

        } catch (Throwable $e) {
            $error = "Could not save the movie. Check the data and image.";
        }
    }
}

// Delete movie
if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["delete_movie"])) {

    $id = (int)($_POST["delete_id"] ?? 0);

    $stmt = $conn->prepare(
        "DELETE FROM movies WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php?deleted=1");
    exit;
}

// Load movie to edit
if (isset($_GET["edit"])) {
    $id = (int)$_GET["edit"];

    $stmt = $conn->prepare(
        "SELECT * FROM movies WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $editMovie = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (isset($_GET["success"])) {
    $message = "Movie saved successfully!";
}

if (isset($_GET["deleted"])) {
    $message = "Movie deleted successfully!";
}

$result = $conn->query(
    "SELECT * FROM movies ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - MovieZone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav>
    <div class="logo">MovieZone Admin</div>
    <div>
        <a href="index.php">View Website</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<div class="container">
    <h1>Movie Management</h1>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="form-box">
        <h2><?= $editMovie ? "Edit Movie" : "Add New Movie" ?></h2>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id"
                value="<?= (int)($editMovie["id"] ?? 0) ?>">

            <label>Movie Title</label>
            <input name="title" maxlength="150" required
                value="<?= htmlspecialchars($editMovie["title"] ?? "") ?>">

            <label>Genre</label>
            <input name="genre" maxlength="80" required
                value="<?= htmlspecialchars($editMovie["genre"] ?? "") ?>">

            <label>Release Year</label>
            <input type="number" name="release_year"
                min="1888" max="2100" required
                value="<?= htmlspecialchars((string)($editMovie["release_year"] ?? "")) ?>">

            <label>Rating (0–10)</label>
            <input type="number" name="rating"
                min="0" max="10" step="0.1" required
                value="<?= htmlspecialchars((string)($editMovie["rating"] ?? "0")) ?>">

            <label>Duration in Minutes</label>
            <input type="number" name="duration"
                min="0" max="1000"
                value="<?= htmlspecialchars((string)($editMovie["duration"] ?? "0")) ?>">

            <label>Director</label>
            <input name="director" maxlength="150"
                value="<?= htmlspecialchars($editMovie["director"] ?? "") ?>">

            <label>Cast Members</label>
            <input name="cast_members"
                value="<?= htmlspecialchars($editMovie["cast_members"] ?? "") ?>">

            <label>Synopsis</label>
            <textarea name="synopsis" rows="4"><?= htmlspecialchars($editMovie["synopsis"] ?? "") ?></textarea>

            <label>Movie Poster (JPG, PNG, WEBP, max 3 MB)</label>
            <input type="file" name="poster"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

            <?php if (!empty($editMovie["poster"])): ?>
                <p>Current poster:</p>
                <img src="<?= htmlspecialchars($editMovie["poster"]) ?>"
                    alt="Current poster" width="90">
            <?php endif; ?>

            <br><br>
            <button type="submit" name="save_movie">Save Movie</button>

            <?php if ($editMovie): ?>
                <a class="btn" href="admin.php">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </div>

    <h2>Existing Movies</h2>

    <table>
        <tr>
            <th>Poster</th>
            <th>Title</th>
            <th>Genre</th>
            <th>Actions</th>
        </tr>

        <?php while ($movie = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if (!empty($movie["poster"])): ?>
                        <img
                            src="<?= htmlspecialchars($movie["poster"]) ?>"
                            alt="Movie poster">
                    <?php else: ?>
                        None
                    <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($movie["title"]) ?></td>
                <td><?= htmlspecialchars($movie["genre"]) ?></td>

                <td>
                    <a href="admin.php?edit=<?= (int)$movie["id"] ?>">
                        Edit
                    </a>

                    <form method="POST" style="display:inline"
                        onsubmit="return confirm('Delete this movie?')">
                        <input type="hidden" name="delete_id"
                            value="<?= (int)$movie["id"] ?>">

                        <button type="submit" name="delete_movie">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
