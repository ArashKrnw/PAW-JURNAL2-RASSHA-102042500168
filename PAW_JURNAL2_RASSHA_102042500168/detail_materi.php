<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT materi.*, mata_kuliah.nama AS nama_mk, mata_kuliah.kode AS kode_mk 
                        FROM materi 
                        JOIN mata_kuliah ON materi.mata_kuliah_id = mata_kuliah.id 
                        WHERE materi.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$materi = $stmt->get_result()->fetch_assoc();

if (!$materi) {
    header("Location: materi.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Materi - LMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h2>LMS EAD</h2>
        <div>
            <a href="index.php">Beranda</a>
            <a href="matakuliah.php">Mata Kuliah</a>
            <a href="materi.php" class="active">Materi</a>
            <a href="tugas.php">Tugas</a>
        </div>
    </div>

    <div class="container" style="max-width: 700px;">
        <div class="card">
            <span class="label"><?= htmlspecialchars($materi['kode_mk']); ?> - <?= htmlspecialchars($materi['nama_mk']); ?></span>
            <h1 style="margin-top: 8px; margin-bottom: 16px;"><?= htmlspecialchars($materi['judul']); ?></h1>
            
            <div style="margin-bottom: 20px; line-height: 1.8;">
                <label>Isi Materi:</label>
                <p style="white-space: pre-line; background: #f8fafc; padding: 16px; border-radius: 4px; border: 1px solid #e2e8f0;"><?= htmlspecialchars($materi['isi']); ?></p>
            </div>

            <div style="margin-bottom: 24px;">
                <label>Tautan Referensi:</label><br>
                <a href="<?= htmlspecialchars($materi['tautan']); ?>" target="_blank" class="btn" style="background: #2563eb; display: inline-block; margin-top: 6px;"><?= htmlspecialchars($materi['tautan']); ?></a>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="edit_materi.php?id=<?= $materi['id']; ?>" class="btn btn-warning">Edit Materi</a>
                <a href="materi.php" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</body>
</html>