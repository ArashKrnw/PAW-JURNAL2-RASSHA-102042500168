<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM tugas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    header("Location: tugas.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mk_id     = (int) $_POST['mata_kuliah_id'];
    $judul     = trim($_POST['judul']);
    $instruksi = trim($_POST['instruksi']);
    $tenggat   = trim($_POST['tenggat']);
    $status    = trim($_POST['status']);

    $stmt_u = $conn->prepare("UPDATE tugas SET mata_kuliah_id=?, judul=?, instruksi=?, tenggat=?, status=? WHERE id=?");
    $stmt_u->bind_param("issssi", $mk_id, $judul, $instruksi, $tenggat, $status, $id);
    $stmt_u->execute();

    if ($stmt_u->affected_rows === 0) {
        $_SESSION['pesan'] = 'Tidak ada perubahan data.';
    } else {
        $_SESSION['pesan'] = 'Tugas berhasil diperbarui.';
    }
    header('Location: tugas.php');
    exit;
}

$mk_list = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY kode ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Edit Tugas</h1>

        <form method="POST" class="card" style="margin-top: 20px;">
            <div class="form-group">
                <label>Mata Kuliah</label>
                <select name="mata_kuliah_id" required>
                    <?php while ($mk = $mk_list->fetch_assoc()): ?>
                        <option value="<?= $mk['id']; ?>" <?= ($data['mata_kuliah_id'] == $mk['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($mk['kode']); ?> - <?= htmlspecialchars($mk['nama']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Judul Tugas</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" required>
            </div>
            <div class="form-group">
                <label>Instruksi Tugas</label>
                <textarea name="instruksi" rows="4" required><?= htmlspecialchars($data['instruksi']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Tenggat</label>
                <input type="date" name="tenggat" value="<?= htmlspecialchars($data['tenggat']); ?>" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="Draf" <?= ($data['status'] === 'Draf') ? 'selected' : ''; ?>>Draf</option>
                    <option value="Aktif" <?= ($data['status'] === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Selesai" <?= ($data['status'] === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
            <a href="tugas.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>