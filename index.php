
<?php
require_once 'db.php';

$search = trim($_GET['search'] ?? '');
$genre = trim($_GET['genre'] ?? '');

$sql = "SELECT * FROM movies WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND title LIKE ?";
    $params[] = "%{$search}%";
    $types .= "s";
}

if ($genre !== '') {
    $sql .= " AND genre LIKE ?";
    $params[] = "%{$genre}%";
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$movies = [];
while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
}

$stmt->close();

$genres = [];
$genreResult = $conn->query("SELECT genre FROM movies");

if ($genreResult) {
    while ($row = $genreResult->fetch_assoc()) {
        foreach (explode(',', $row['genre'] ?? '') as $g) {
            $g = trim($g);
            if ($g !== '') {
                $genres[$g] = $g;
            }
        }
    }
}

$featured = null;

if (!empty($movies)) {
    foreach ($movies as $movie) {
        if (stripos($movie['title'], 'The Maze Runner') !== false) {
            $featured = $movie;
            break;
        }
    }

    if (!$featured) {
        $featured = $movies[0];
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function posterPath($poster) {
    $poster = trim((string)$poster);
    if ($poster === '') {
        return '';
    }

    
    return $poster;
}

$trending = array_values(array_filter($movies, function ($movie) use ($featured) {
    return !$featured || (int)$movie['id'] !== (int)$featured['id'];
}));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieZone | Discover Movies</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .hero {
            position: relative;
            min-height: 430px;
            display: flex;
            align-items: center;
            overflow: hidden;
            border-bottom: 1px solid var(--border);
            background: #111720;
        }

        .hero-backdrop {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center 30%;
            opacity: .42;
            filter: blur(1px);
            transform: scale(1.03);
        }

        .hero-shade {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                #080b10 0%,
                rgba(8,11,16,.94) 27%,
                rgba(8,11,16,.58) 65%,
                rgba(8,11,16,.25) 100%
            ),
            linear-gradient(0deg, #080b10 0%, transparent 55%);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 1350px;
            margin: auto;
            padding: 55px 0;
        }

        .hero-content h1 {
            max-width: 620px;
            margin: 10px 0;
            font-size: clamp(34px, 5vw, 58px);
        }

        .hero-tag {
            color: var(--gold);
            text-transform: uppercase;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .hero-meta {
            color: #e5e7eb;
            margin: 12px 0;
        }

        .hero-description {
            max-width: 560px;
            color: #d1d5db;
            line-height: 1.8;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-outline {
            background: rgba(8,11,16,.7);
            color: white;
            border-color: #6b7280;
        }

        .btn-outline:hover {
            background: #202936;
            color: var(--gold);
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .section-heading h2 {
            margin: 0;
        }

        .section-heading span {
            color: var(--gold);
        }

        .movie-card .rating {
            color: var(--gold);
            font-weight: bold;
        }

        .empty-state {
            background: var(--panel);
            padding: 30px;
            border-radius: 12px;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 700px) {
            .hero {
                min-height: 390px;
            }

            .hero-content {
                padding: 35px 0;
            }

            .hero-shade {
                background: linear-gradient(
                    90deg,
                    rgba(8,11,16,.96),
                    rgba(8,11,16,.72)
                ), linear-gradient(0deg, #080b10, transparent);
            }
        }

        
/* Final banner fix */
.hero {
    position: relative !important;
    min-height: 465px !important;
    padding: 55px 5% !important;
    display: flex !important;
    align-items: center !important;
    overflow: hidden !important;
    isolation: isolate !important;
    background: #080b10 !important;
}

.hero-backdrop {
    position: absolute !important;
    inset: 0 !important;
    z-index: -2 !important;
    background-size: cover !important;
    background-position: 72% 30% !important;
    background-repeat: no-repeat !important;
    opacity: 1 !important;
    filter: none !important;
    transform: none !important;
}

.hero-shade {
    position: absolute !important;
    inset: 0 !important;
    z-index: -1 !important;
    background: linear-gradient(
        90deg,
        rgba(8, 11, 16, 0.96) 0%,
        rgba(8, 11, 16, 0.78) 34%,
        rgba(8, 11, 16, 0.30) 65%,
        rgba(8, 11, 16, 0.05) 100%
    ) !important;
    pointer-events: none;
}

.hero-content {
    position: relative !important;
    z-index: 1 !important;
    width: 90% !important;
    max-width: 1350px !important;
    margin: auto !important;
}

@media (max-width: 700px) {
    .hero {
        min-height: 420px !important;
        padding: 35px 5% !important;
    }

    .hero-backdrop {
        background-position: 65% center !important;
    }
}

    </style>
</head>
<body>

<nav>
    <a href="index.php" class="logo" style="margin-left:0">
        🎬 Next Flick
    </a>

    <div>
        <a href="index.php">Home</a>
        <a href="#movies">Movies</a>
        <a href="#genres">Genres</a>
        <a href="login.php">Admin</a>
    </div>
</nav>




<?php if ($featured && $search === '' && $genre === ''): ?>

<section class="hero">
    <div class="hero-backdrop"
         style="background-image: url('images/maze-runner-banner.png');">
    </div>

    <div class="hero-shade"></div>

    <div class="hero-content">
        <span class="hero-tag">MOVIEZONE ORIGINAL PICKS</span>

        <h1><?= e($featured['title'] ?? 'Discover Your Next Movie') ?></h1>

        <div class="hero-meta">
            <?= e($featured['release_year'] ?? '') ?>
            · <?= e($featured['genre'] ?? 'Movies') ?>
            · ★ <?= e($featured['rating'] ?? 'N/A') ?>
        </div>

        <p class="hero-description">
            <?= e($featured['synopsis'] ?? $featured['description'] ?? 'Discover your next favorite movie.') ?>
        </p>

        <div class="hero-actions">
            <a class="btn" href="movie.php?id=<?= (int)$featured['id'] ?>">
                ▶ Watch Details
            </a>
            <a class="btn btn-outline" href="#movies">
                Browse Movies
            </a>
        </div>
    </div>
</section>

<?php endif; ?>


<main class="container" id="movies">

    <div class="section-heading">
        <h2>
            <?= ($search !== '' || $genre !== '') ? 'Search Results' : '🔥 Trending Now' ?>
        </h2>
        <span><?= count($movies) ?> movies</span>
    </div>

    <form method="GET" action="index.php" class="search-form" id="genres">
        <input
            type="search"
            name="search"
            placeholder="Search movie titles..."
            value="<?= e($search) ?>"
        >

        <select name="genre">
            <option value="">All Genres</option>
            <?php foreach ($genres as $g): ?>
                <option value="<?= e($g) ?>"
                    <?= $genre === $g ? 'selected' : '' ?>>
                    <?= e($g) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Search</button>
        <a href="index.php" class="btn btn-outline">Reset</a>
    </form>

    <?php if (empty($movies)): ?>
        <div class="empty-state">
            <h3>No movies found</h3>
            <p>Try another movie title or choose a different genre.</p>
            <a href="index.php" class="btn">Show All Movies</a>
        </div>
    <?php else: ?>

        <div class="movie-grid">
            <?php foreach ($movies as $movie): ?>
                <?php $poster = posterPath($movie['poster'] ?? ''); ?>

                <article class="movie-card">
                    <?php if ($poster !== ''): ?>
                        <img
                            src="<?= e($poster) ?>"
                            alt="<?= e($movie['title']) ?> poster"
                            loading="lazy"
                        >
                    <?php else: ?>
                        <div style="height:285px;display:grid;place-items:center;color:var(--muted)">
                            🎬 No poster
                        </div>
                    <?php endif; ?>

                    <div class="movie-info">
                        <h3><?= e($movie['title']) ?></h3>

                        <p class="muted">
                            <?= e($movie['genre']) ?>
                            · <?= e($movie['release_year']) ?>
                        </p>

                        <p class="rating">
                            ★ <?= e($movie['rating']) ?>/10
                        </p>

                        <a class="btn"
                           href="movie.php?id=<?= (int)$movie['id'] ?>">
                            View Details
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</main>

<footer>
    <p>© <?= date('Y') ?> MovieZone. Your movie collection.</p>
</footer>

</body>
</html>
