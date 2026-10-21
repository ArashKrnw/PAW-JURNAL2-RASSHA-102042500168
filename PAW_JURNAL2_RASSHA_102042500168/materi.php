<?php
require 'koneksi.php';

$cari = $_GET['cari'] ?? '';
$filter_mk = $_GET['mk_id'] ?? '';

$sql = "SELECT materi.*, mata_kuliah.nama AS nama_mk, mata_kuliah.kode AS kode_mk 
        FROM materi 
        JOIN mata_kuliah ON materi.mata_kuliah_id = mata_kuliah.id WHERE 1=1";

if (!empty($cari)) {
    $sql .= " AND materi.judul LIKE ?";
}
if (!empty($filter_mk)) {
    $sql .= " AND materi.mata_kuliah_id = ?";
}

$stmt = $conn->prepare($sql);

if (!empty($cari) && !empty($filter_mk)) {
    $param_cari = "%" . $cari . "%";
    $stmt->bind_param("si", $param_cari, $filter_mk);
} elseif (!empty($cari)) {
    $param_cari = "%" . $cari . "%";
    $stmt->bind_param("s", $param_cari);
} elseif (!empty($filter_mk)) {
    $stmt->bind_param("i", $filter_mk);
}

$stmt->execute();
$result = $stmt->get_result();

$mk_options = $conn->query("SELECT id, kode, nama FROM mata_kuliah ORDER BY kode ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Materi Pembelajaran</title>
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

    <div class="container">
        <?php if (!empty($_SESSION['pesan'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['pesan']); unset($_SESSION['pesan']); ?></div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Materi Pembelajaran</h1>
            <a href="tambah_materi.php" class="btn">+ Tambah Materi</a>
        </div>

        <form method="GET" style="display: flex; gap: 10px; margin-bottom: 20px;">
            <input type="text" name="cari" placeholder="Cari judul..." value="<?= htmlspecialchars($cari); ?>" style="width: 250px;">
            <select name="mk_id">
                <option value="">Semua Mata Kuliah</option>
                <?php while ($mk = $mk_options->fetch_assoc()): ?>
                    <option value="<?= $mk['id']; ?>" <?= ($filter_mk == $mk['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($mk['kode']); ?> - <?= htmlspecialchars($mk['nama']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn">Cari</button>
            <a href="materi.php" class="btn btn-secondary">Reset</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Judul Materi</th>
                    <th>Mata Kuliah</th>
                    <th>Tautan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows == 0): ?>
                    <tr><td colspan="4" style="text-align:center;">Data tidak ditemukan.</td></tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($row['judul']); ?></strong></td>
                            <td><?= htmlspecialchars($row['kode_mk']); ?> - <?= htmlspecialchars($row['nama_mk']); ?></td>
                            <td><a href="<?= htmlspecialchars($row['tautan']); ?>" target="_blank">Buka Tautan</a></td>
                            <td>
                                <a href="detail_materi.php?id=<?= $row['id']; ?>" class="btn" style="padding: 4px 8px; font-size: 12px; background: #0284c7;">Detail</a>
                                <a href="edit_materi.php?id=<?= $row['id']; ?>" class="btn btn-warning" style="padding: 4px 8px; font-size: 12px;">Edit</a>
                                <a href="hapus_materi.php?id=<?= $row['id']; ?>" class="btn btn-danger" style="padding: 4px 8px; font-size: 12px;">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>