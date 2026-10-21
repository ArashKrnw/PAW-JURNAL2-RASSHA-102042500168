<?php
require 'koneksi.php';

$q_mk = $conn->query("SELECT COUNT(*) AS total FROM mata_kuliah");
$total_mk = $q_mk->fetch_assoc()['total'];

$q_materi = $conn->query("SELECT COUNT(*) AS total FROM materi");
$total_materi = $q_materi->fetch_assoc()['total'];

$q_tugas = $conn->query("SELECT COUNT(*) AS total FROM tugas");
$total_tugas = $q_tugas->fetch_assoc()['total'];

$stmt = $conn->prepare("SELECT kode, nama FROM mata_kuliah ORDER BY kode ASC");
$stmt->execute();
$mk_list = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LMS - Beranda</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h2>Learning Management System</h2>
        <div>
            <a href="index.php" class="active">Beranda</a>
            <a href="matakuliah.php">Mata Kuliah</a>
            <a href="materi.php">Materi</a>
            <a href="tugas.php">Tugas</a>
        </div>
    </div>

    <div class="container">
        <h1>Beranda</h1>
        <p style="color: #64748b; margin-bottom: 24px;">Pengelolaan mata kuliah, materi pembelajaran, dan tugas.</p>

        <div class="card-grid">
            <div class="card">
                <h3>Mata Kuliah</h3>
                <div class="number"><?= $total_mk; ?></div>
            </div>
            <div class="card">
                <h3>Materi</h3>
                <div class="number"><?= $total_materi; ?></div>
            </div>
            <div class="card">
                <h3>Tugas</h3>
                <div class="number"><?= $total_tugas; ?></div>
            </div>
        </div>

        <div class="card">
            <h3>Mata Kuliah Tersedia</h3>
            <ul style="margin-top: 12px; padding-left: 20px;">
                <?php while ($row = $mk_list->fetch_assoc()): ?>
                    <li style="margin-bottom: 8px; font-weight: bold;">
                        <?= htmlspecialchars($row['kode']); ?> - <?= htmlspecialchars($row['nama']); ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</body>
</html>