<?php
require 'koneksi.php';

$cari = $_GET['cari'] ?? '';
$sql = "SELECT * FROM mata_kuliah";
if (!empty($cari)) {
    $sql .= " WHERE kode LIKE ? OR nama LIKE ?";
}
$sql .= " ORDER BY kode ASC";

$stmt = $conn->prepare($sql);
if (!empty($cari)) {
    $param_cari = "%" . $cari . "%";
    $stmt->bind_param("ss", $param_cari, $param_cari);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mata Kuliah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h2>LMS EAD</h2>
        <div>
            <a href="index.php">Beranda</a>
            <a href="matakuliah.php" class="active">Mata Kuliah</a>
            <a href="materi.php">Materi</a>
            <a href="tugas.php">Tugas</a>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($_SESSION['pesan'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Mata Kuliah</h1>
            <a href="tambah_matakuliah.php" class="btn">+ Tambah Mata Kuliah</a>
        </div>

        <form method="GET" style="display: flex; gap: 10px; margin-bottom: 20px;">
            <input type="text" name="cari" placeholder="Cari kode atau nama..." value="<?= htmlspecialchars($cari); ?>" style="width: 300px;">
            <button type="submit" class="btn">Cari</button>
            <a href="matakuliah.php" class="btn btn-secondary">Reset</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Mata Kuliah</th>
                    <th>Dosen Pengampu</th>
                    <th>Semester</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows == 0): ?>
                    <tr><td colspan="5" style="text-align:center;">Data tidak ditemukan.</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['kode']); ?></strong></td>
                            <td><?= htmlspecialchars($row['nama']); ?></td>
                            <td><?= htmlspecialchars($row['dosen']); ?></td>
                            <td>Semester <?= htmlspecialchars($row['semester']); ?></td>
                            <td>
                                <a href="edit_matakuliah.php?id=<?= $row['id']; ?>" class="btn btn-warning" style="padding: 4px 8px; font-size: 12px;">Edit</a>
                                <a href="hapus_matakuliah.php?id=<?= $row['id']; ?>" class="btn btn-danger" style="padding: 4px 8px; font-size: 12px;">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>