<?php
require 'koneksi.php';

$error = '';
$mk_id = $judul = $isi = $tautan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mk_id  = (int) $_POST['mata_kuliah_id'];
    $judul  = trim($_POST['judul']);
    $isi    = trim($_POST['isi']);
    $tautan = trim($_POST['tautan']);

    if (empty($mk_id)) {
        $error = 'Pilih mata kuliah terlebih dahulu.';
    } elseif (strlen($judul) < 3 || strlen($judul) > 150) {
        $error = 'Judul materi harus 3-150 karakter.';
    } elseif (!preg_match('/^https?:\/\/.+$/', $tautan)) {
        $error = 'Gunakan tautan http/https yang valid.';
    } else {
        $stmt = $conn->prepare("INSERT INTO materi (mata_kuliah_id, judul, isi, tautan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $mk_id, $judul, $isi, $tautan);
        if ($stmt->execute()) {
            $_SESSION['pesan'] = 'Materi berhasil ditambahkan.';
            header('Location: materi.php');
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
    <title>Tambah Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Tambah Materi</h1>

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
                <label>Judul Materi</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($judul); ?>" required>
            </div>
            <div class="form-group">
                <label>Isi Materi</label>
                <textarea name="isi" rows="5" required><?= htmlspecialchars($isi); ?></textarea>
            </div>
            <div class="form-group">
                <label>Tautan Referensi</label>
                <input type="text" name="tautan" value="<?= htmlspecialchars($tautan); ?>" placeholder="https://..." required>
            </div>
            <button type="submit" class="btn">Simpan Data</button>
            <a href="materi.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>