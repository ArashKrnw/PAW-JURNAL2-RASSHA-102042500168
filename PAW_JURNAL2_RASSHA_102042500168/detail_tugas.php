<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT tugas.*, mata_kuliah.nama AS nama_mk, mata_kuliah.kode AS kode_mk 
                        FROM tugas 
                        JOIN mata_kuliah ON tugas.mata_kuliah_id = mata_kuliah.id 
                        WHERE tugas.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$tugas = $stmt->get_result()->fetch_assoc();

if (!$tugas) {
    header("Location: tugas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tugas - LMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h2>LMS EAD</h2>
        <div>
            <a href="index.php">Beranda</a>
            <a href="matakuliah.php">Mata Kuliah</a>
            <a href="materi.php">Materi</a>
            <a href="tugas.php" class="active">Tugas</a>
        </div>
    </div>

    <div class="container" style="max-width: 700px;">
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span class="label"><?= htmlspecialchars($tugas['kode_mk']); ?> - <?= htmlspecialchars($tugas['nama_mk']); ?></span>
                    <h1 style="margin-top: 8px; margin-bottom: 16px;"><?= htmlspecialchars($tugas['judul']); ?></h1>
                </div>
                <span class="badge badge-<?= strtolower($tugas['status']); ?>">
                    <?= htmlspecialchars($tugas['status']); ?>
                </span>
            </div>

            <div style="margin-bottom: 20px; line-height: 1.8;">
                <label>Instruksi Tugas:</label>
                <p style="white-space: pre-line; background: #f8fafc; padding: 16px; border-radius: 4px; border: 1px solid #e2e8f0;"><?= htmlspecialchars($tugas['instruksi']); ?></p>
            </div>

            <div style="margin-bottom: 24px;">
                <label>Tenggat Waktu:</label>
                <p style="font-weight: bold; color: #dc2626;"><?= date('d F Y', strtotime($tugas['tenggat'])); ?></p>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="edit_tugas.php?id=<?= $tugas['id']; ?>" class="btn btn-warning">Edit Tugas</a>
                <a href="tugas.php" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</body>
</html>