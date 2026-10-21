<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    header("Location: materi.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mk_id  = (int) $_POST['mata_kuliah_id'];
    $judul  = trim($_POST['judul']);
    $isi    = trim($_POST['isi']);
    $tautan = trim($_POST['tautan']);

    if (!preg_match('/^https?:\/\/.+$/', $tautan)) {
        $error = 'Gunakan tautan http/https yang valid.';
    } else {
        $stmt_u = $conn->prepare("UPDATE materi SET mata_kuliah_id=?, judul=?, isi=?, tautan=? WHERE id=?");
        $stmt_u->bind_param("isssi", $mk_id, $judul, $isi, $tautan, $id);
        $stmt_u->execute();

        if ($stmt_u->affected_rows === 0) {
            $_SESSION['pesan'] = 'Tidak ada perubahan data.';
        } else {
            $_SESSION['pesan'] = 'Materi berhasil diperbarui.';
        }
        header('Location: materi.php');
        exit;
    }
}

$mk_list = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY kode ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Edit Materi</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

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
                <label>Judul Materi</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']); ?>" required>
            </div>
            <div class="form-group">
                <label>Isi Materi</label>
                <textarea name="isi" rows="5" required><?= htmlspecialchars($data['isi']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Tautan Referensi</label>
                <input type="text" name="tautan" value="<?= htmlspecialchars($data['tautan']); ?>" required>
            </div>
            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
            <a href="materi.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>