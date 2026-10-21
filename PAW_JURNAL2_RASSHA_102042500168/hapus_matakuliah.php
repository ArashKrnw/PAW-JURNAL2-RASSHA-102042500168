<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT nama FROM mata_kuliah WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
    header("Location: matakuliah.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
        die("Token keamanan tidak valid.");
    }

    try {
        $stmt_del = $conn->prepare("DELETE FROM mata_kuliah WHERE id = ?");
        $stmt_del->bind_param("i", $id);
        $stmt_del->execute();

        $_SESSION['pesan'] = "Mata kuliah berhasil dihapus.";
        header("Location: matakuliah.php");
        exit;
    } catch (mysqli_sql_exception $e) {
        $_SESSION['error'] = "Data belum tersimpan. Mata kuliah masih memiliki materi atau tugas. Hapus data terkait terlebih dahulu.";
        header("Location: matakuliah.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 500px; margin-top: 50px;">
        <div class="card">
            <h2>Hapus Mata Kuliah?</h2>
            <p style="margin: 16px 0;">Apakah Anda yakin ingin menghapus <strong>"<?= htmlspecialchars($res['nama']); ?>"</strong>?</p>
            <p style="color: #64748b; font-size: 13px; margin-bottom: 20px;">Data baru dihapus setelah tombol konfirmasi dikirim.</p>

            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
                <button type="submit" class="btn btn-danger">Ya, hapus data</button>
                <a href="matakuliah.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>