<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require __DIR__ . '/koneksi.php';

if (isset($_GET['konfirmasi'])) {
    $id = (int)$_GET['konfirmasi'];
    mysqli_query($koneksi, "UPDATE pesanan SET status='Dikonfirmasi' WHERE id=$id");
    header("Location: admin.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id=$id");
    header("Location: admin.php");
    exit;
}

$result = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – RuangTanam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/styleadmin.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="admin.php">
            <img src="https://media.istockphoto.com/id/1045368942/vector/abstract-green-leaf-logo-icon-vector-design-ecology-icon-set-eco-icon.jpg?s=612x612&w=0&k=20&c=XIfHMI8r1G73blCpCBFmLIxCtOLx8qX0O3mZC9csRLs=" width="40" alt="Logo">
            <span>RuangTanam</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item d-flex align-items-center admin-greeting" style="pointer-events:none; user-select:none; margin-right:8px;">Halo,&nbsp;<strong><?= htmlspecialchars($_SESSION['admin']) ?></strong></li>
                <li class="nav-item"><a href="logout.php" class="nav-link btn-logout-nav">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="dashboard-wrap">
    <div class="container">

        <p class="page-title">Dashboard Pesanan</p>
        <p class="page-sub">Kelola semua pesanan masuk dari pembeli RuangTanam.</p>

        <?php
        $semua  = mysqli_num_rows($result);
        $q_bel  = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pesanan WHERE status='Belum Dikonfirmasi'");
        $q_kon  = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pesanan WHERE status='Dikonfirmasi'");
        $jml_bel = mysqli_fetch_assoc($q_bel)['jml'];
        $jml_kon = mysqli_fetch_assoc($q_kon)['jml'];
        ?>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="stat-card">
                    <div class="label">Total Pesanan</div>
                    <div class="value"><?= $semua ?></div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card belum">
                    <div class="label">Belum Dikonfirmasi</div>
                    <div class="value"><?= $jml_bel ?></div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card dikonfirmasi">
                    <div class="label">Dikonfirmasi</div>
                    <div class="value"><?= $jml_kon ?></div>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-card-header">Daftar Pesanan</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Paket</th>
                            <th>Unit</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($result, 0);
                        $no = 1;
                        if (mysqli_num_rows($result) === 0):
                        ?>
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <span>📋</span>
                                    Belum ada pesanan masuk.
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['telepon']) ?></td>
                            <td><?= $row['paket'] ?></td>
                            <td><?= $row['jumlah_unit'] ?></td>
                            <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($row['status'] === 'Dikonfirmasi'): ?>
                                    <span class="badge-konfirmasi">Dikonfirmasi</span>
                                <?php else: ?>
                                    <span class="badge-belum">Belum Dikonfirmasi</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <?php if ($row['status'] !== 'Dikonfirmasi'): ?>
                                    <a href="admin.php?konfirmasi=<?= $row['id'] ?>" class="btn-konfirmasi"
                                       onclick="return confirm('Konfirmasi pesanan ini?')">Konfirmasi</a>
                                    <?php endif; ?>
                                    <a href="admin.php?hapus=<?= $row['id'] ?>" class="btn-hapus"
                                       onclick="return confirm('Hapus pesanan ini? Tindakan tidak bisa dibatalkan.')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>