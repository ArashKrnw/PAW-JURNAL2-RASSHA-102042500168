<?php
require 'koneksi.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT judul FROM materi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
    header("Location: materi.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['token'], $_POST['token'] ?? '')) {
        die("Token keamanan tidak valid.");
    }

    $stmt_del = $conn->prepare("DELETE FROM materi WHERE id = ?");
    $stmt_del->bind_param("i", $id);
    $stmt_del->execute();

    $_SESSION['pesan'] = "Materi berhasil dihapus.";
    header("Location: materi.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Materi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 500px; margin-top: 50px;">
        <div class="card">
            <h2>Hapus Materi?</h2>
            <p style="margin: 16px 0;">Apakah Anda yakin ingin menghapus <strong>"<?= htmlspecialchars($res['judul']); ?>"</strong>?</p>

            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token']); ?>">
                <button type="submit" class="btn btn-danger">Ya, hapus data</button>
                <a href="materi.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>