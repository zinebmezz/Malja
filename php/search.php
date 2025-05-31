<?php

session_start();
require_once 'db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$query = $_GET['query'] ?? '';
$specialization = $_GET['specialization'] ?? '';
$rating = $_GET['rating'] ?? '';

$sql = "
  SELECT tp.*, u.full_name, u.id AS user_id 
  FROM therapist_profile tp
  JOIN users u ON u.id = tp.user_id
  WHERE 1
";

$params = [];

if (!empty($query)) {
 $sql .= " AND (
  u.full_name LIKE :q1 OR
  tp.specialization LIKE :q2 OR
  tp.bio LIKE :q3
)";
$params['q1'] = '%' . $query . '%';
$params['q2'] = '%' . $query . '%';
$params['q3'] = '%' . $query . '%';
}

if (!empty($specialization)) {
  $sql .= " AND tp.specialization = :specialization";
  $params['specialization'] = $specialization;
}

if (!empty($rating)) {
  $sql .= " AND tp.rating >= :rating";
  $params['rating'] = $rating;
}

$sql .= " ORDER BY tp.rating DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$therapists = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Search Results - Malja’</title>
 <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: rgb(249, 241, 241);
      margin: 0;
      padding: 0;
      color: #333;
    }

   header {
  background: rgb(249, 241, 241);
  padding: 3rem 1rem 2rem;

}

.header-container {
  max-width: 1000px;
  margin: 0 auto;
  background: #eadede;
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
  text-align: center;
}

.header-container h1 {
  font-size: 2.4rem;
  color: #4a3f35;
  margin-bottom: 0.5rem;
}

.subtitle {
  font-size: 16px;
  color: #27231e;
  margin-bottom: 1.5rem;
}
    h1 {
      color: #4a3f35;
      font-size: 2rem;
      margin-bottom: 1rem;
    }

    .filters {

  backdrop-filter: blur(8px);
  border-radius: 16px;
  padding: 1.2rem 2rem;
  margin: 0 auto 2rem;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 1rem;
  max-width: 1000px;

  transition: all 0.3s ease;
}
.filters input,
.filters select {
  padding: 0.7rem 1rem;
  border-radius: 10px;
  border: 1px solid #232221;
  font-size: 1rem;
  background-color:#f0e8e8;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
  color: #4a3f35;
  transition: border 0.3s, box-shadow 0.3s;
  min-width: 180px;
}

.filters input:focus,
.filters select:focus {
  outline: none;
  border-color: #4e4c4a;
  box-shadow: 0 0 0 3px rgba(191, 163, 136, 0.2);
}
.filters input:hover,
.filters select:hover {
  border-color: #c7ae94;
}

    .therapist-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      padding: 0 2rem 3rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 1rem;
    }

    .card h3 {
      margin: 0.5rem 0 0.2rem;
      color: #413f3e;
    }

    .card .specialization {
      font-size: 0.95rem;
      color:#413f3e;
      margin-bottom: 0.5rem;
    }

    .card p {
      font-size: 0.92rem;
      text-align: center;
    }

    .rating {
      color: #f4b400;
      font-size: 1.1rem;
      margin: 0.3rem 0 0.7rem;
    }

    .btn {
      background-color: #ecd1d1;
      color: #161212;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
      font-weight: bold;
    }

    .btn:hover {
      background-color:#dbc1c1;
    }

    @media (max-width: 600px) {
      .filters {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<header>
    <div class="header-container">
      <h1>Meet Your Therapist</h1>
      <p class="subtitle">Find support, healing, and guidance tailored to you</p>

     
      <form class="filters" method="GET" action="search.php">
        <input class="search" type="text" name="query" placeholder="Search by name" value="<?= htmlspecialchars($query) ?>" />
        <select name="specialization">
          <option value="">All Specializations</option>
          <option value="anxiety" <?= $specialization === 'anxiety' ? 'selected' : '' ?>>Anxiety</option>
          <option value="trauma" <?= $specialization === 'trauma' ? 'selected' : '' ?>>Trauma</option>
          <option value="children" <?= $specialization === 'children' ? 'selected' : '' ?>>Children</option>
          <option value="relationships" <?= $specialization === 'relationships' ? 'selected' : '' ?>>Relationships</option>
          <option value="addiction" <?= $specialization === 'addiction' ? 'selected' : '' ?>>Addiction</option>
        </select>
        <select name="rating">
          <option value="">All Ratings</option>
          <option value="3" <?= $rating === '3' ? 'selected' : '' ?>>3★ and up</option>
          <option value="4" <?= $rating === '4' ? 'selected' : '' ?>>4★ and up</option>
          <option value="5" <?= $rating === '5' ? 'selected' : '' ?>>5★ only</option>
        </select>
        <button class="btn" type="submit">Search</button>
      </form>
    </div>
  </header>

  <section class="therapist-grid">
    <?php if (empty($therapists)): ?>
      <p style='text-align:center;'>No therapists found matching your search.</p>
    <?php else: ?>
      <?php foreach ($therapists as $t): ?>
        <div class="card">
          <?php
            $img = !empty($t['profile_picture']) ? 'uploads/profile-pic/' . $t['profile_picture'] : 'image/default.png';
          ?>
          <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($t['full_name']) ?>" />
          <h3><?= htmlspecialchars($t['full_name']) ?></h3>
          <div class="specialization"><?= htmlspecialchars(ucfirst($t['specialization'])) ?></div>
          <div class="rating">★ <?= number_format($t['rating'] ?? 0, 1) ?></div>
          <p><?= htmlspecialchars($t['bio']) ?></p>
          <a class="btn" href="viewprofile.php?id=<?= $t['user_id'] ?>">View Profile</a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</body>
</html>