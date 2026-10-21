<?php
require 'koneksi.php';

$error = '';
$mk_id = $judul = $instruksi = $tenggat = $status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mk_id     = (int) $_POST['mata_kuliah_id'];
    $judul     = trim($_POST['judul']);
    $instruksi = trim($_POST['instruksi']);
    $tenggat   = trim($_POST['tenggat']);
    $status    = trim($_POST['status']);

    $allowed_status = ['Draf', 'Aktif', 'Selesai'];

    if (empty($mk_id)) {
        $error = 'Pilih mata kuliah terlebih dahulu.';
    } elseif (strlen($judul) < 3 || strlen($judul) > 150) {
        $error = 'Judul tugas harus 3-150 karakter.';
    } elseif (!in_array($status, $allowed_status)) {
        $error = 'Status hanya boleh Draf, Aktif, atau Selesai.';
    } else {
        $stmt = $conn->prepare("INSERT INTO tugas (mata_kuliah_id, judul, instruksi, tenggat, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $mk_id, $judul, $instruksi, $tenggat, $status);
        if ($stmt->execute()) {
            $_SESSION['pesan'] = 'Tugas berhasil ditambahkan.';
            header('Location: tugas.php');
            exit;
        }
    }
}

$mk_list = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY kode ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Tambah Tugas</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="card" style="margin-top: 20px;">
            <div class="form-group">
                <label>Mata Kuliah</label>
                <select name="mata_kuliah_id" required>
                    <option value="">-- Pilih Mata Kuliah --</option>
                    <?php while ($mk = $mk_list->fetch_assoc()): ?>
                        <option value="<?= $mk['id']; ?>" <?= ($mk_id == $mk['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($mk['kode']); ?> - <?= htmlspecialchars($mk['nama']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Judul Tugas</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($judul); ?>" required>
            </div>
            <div class="form-group">
                <label>Instruksi Tugas</label>
                <textarea name="instruksi" rows="4" required><?= htmlspecialchars($instruksi); ?></textarea>
            </div>
            <div class="form-group">
                <label>Tenggat</label>
                <input type="date" name="tenggat" value="<?= htmlspecialchars($tenggat); ?>" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="Draf">Draf</option>
                    <option value="Aktif" selected>Aktif</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn">Simpan Data</button>
            <a href="tugas.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>