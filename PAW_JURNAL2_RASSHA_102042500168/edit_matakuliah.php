<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    header("Location: matakuliah.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode     = trim($_POST['kode']);
    $nama     = trim($_POST['nama']);
    $dosen    = trim($_POST['dosen']);
    $semester = (int) $_POST['semester'];

    $stmt_cek = $conn->prepare("SELECT id FROM mata_kuliah WHERE kode = ? AND id <> ?");
    $stmt_cek->bind_param("si", $kode, $id);
    $stmt_cek->execute();
    if ($stmt_cek->get_result()->num_rows > 0) {
        $error = 'Kode mata kuliah sudah digunakan oleh data lain.';
    } else {
        $stmt_u = $conn->prepare("UPDATE mata_kuliah SET kode=?, nama=?, dosen=?, semester=? WHERE id=?");
        $stmt_u->bind_param("sssii", $kode, $nama, $dosen, $semester, $id);
        $stmt_u->execute();

        if ($stmt_u->affected_rows === 0) {
            $_SESSION['pesan'] = 'Tidak ada perubahan data.';
        } else {
            $_SESSION['pesan'] = 'Mata kuliah berhasil diperbarui.';
        }
        header('Location: matakuliah.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Edit Mata Kuliah</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="card" style="margin-top: 20px;">
            <div class="form-group">
                <label>Kode Mata Kuliah</label>
                <input type="text" name="kode" value="<?= htmlspecialchars($data['kode']); ?>" required>
            </div>
            <div class="form-group">
                <label>Nama Mata Kuliah</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label>Dosen Pengampu</label>
                <input type="text" name="dosen" value="<?= htmlspecialchars($data['dosen']); ?>" required>
            </div>
            <div class="form-group">
                <label>Semester</label>
                <input type="number" name="semester" value="<?= htmlspecialchars($data['semester']); ?>" required>
            </div>
            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
            <a href="matakuliah.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>