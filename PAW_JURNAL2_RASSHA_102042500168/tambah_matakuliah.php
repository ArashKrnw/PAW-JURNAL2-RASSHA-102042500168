<?php
require 'koneksi.php';

$error = '';
$kode = $nama = $dosen = $semester = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode     = trim($_POST['kode']);
    $nama     = trim($_POST['nama']);
    $dosen    = trim($_POST['dosen']);
    $semester = (int) $_POST['semester'];

    if (strlen($kode) < 3 || strlen($kode) > 20) {
        $error = 'Kode harus 3-20 karakter.';
    } elseif (strlen($nama) < 3 || strlen($nama) > 100) {
        $error = 'Nama mata kuliah harus 3-100 karakter.';
    } elseif ($semester < 1 || $semester > 14) {
        $error = 'Semester harus antara 1-14.';
    } else {
        $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE kode = ?");
        $stmt_cek->bind_param("s", $kode);
        $stmt_cek->execute();
        if ($stmt_cek->get_result()->num_rows > 0) {
            $error = 'Kode mata kuliah sudah digunakan.';
        } else {
            $stmt = $conn->prepare("INSERT INTO mata_kuliah (kode, nama, dosen, semester) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $kode, $nama, $dosen, $semester);
            if ($stmt->execute()) {
                $_SESSION['pesan'] = 'Mata kuliah berhasil ditambahkan.';
                header('Location: matakuliah.php');
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Tambah Mata Kuliah</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="card" style="margin-top: 20px;">
            <div class="form-group">
                <label>Kode Mata Kuliah</label>
                <input type="text" name="kode" value="<?= htmlspecialchars($kode); ?>" required>
            </div>
            <div class="form-group">
                <label>Nama Mata Kuliah</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($nama); ?>" required>
            </div>
            <div class="form-group">
                <label>Dosen Pengampu</label>
                <input type="text" name="dosen" value="<?= htmlspecialchars($dosen); ?>" required>
            </div>
            <div class="form-group">
                <label>Semester (1-14)</label>
                <input type="number" name="semester" value="<?= htmlspecialchars($semester); ?>" required>
            </div>
            <button type="submit" class="btn">Simpan Data</button>
            <a href="matakuliah.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>