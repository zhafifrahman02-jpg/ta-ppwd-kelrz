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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Montserrat', sans-serif; }

        body {
            background: #f4f7f2;
            min-height: 100vh;
        }

        .navbar-admin {
            background: white;
            border-bottom: 2px solid #eaf5df;
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .navbar-brand-admin {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand-admin img {
            width: 34px;
        }

        .navbar-brand-admin span {
            font-size: 18px;
            font-weight: 800;
            color: #222;
        }

        .badge-admin {
            font-size: 10px;
            font-weight: 700;
            background: #eaf5df;
            color: #457a24;
            padding: 3px 9px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-logout {
            font-size: 13px;
            font-weight: 600;
            color: #888;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 7px 16px;
            background: white;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            color: #c0392b;
            border-color: #c0392b;
        }

        .dashboard-wrap {
            padding: 36px 0 60px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: #111;
            margin-bottom: 4px;
        }

        .page-sub {
            font-size: 13px;
            color: #888;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #5a9e2f;
        }

        .stat-card .label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 6px;
        }

        .stat-card .value {
            font-size: 28px;
            font-weight: 800;
            color: #111;
        }

        .stat-card.belum {
            border-left-color: #e67e22;
        }

        .stat-card.dikonfirmasi {
            border-left-color: #5a9e2f;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            font-weight: 700;
            color: #222;
        }

        .table thead th {
            background: #f8faf6;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #777;
            border-bottom: 1px solid #eee;
            padding: 13px 16px;
            white-space: nowrap;
        }

        .table tbody td {
            font-size: 13px;
            color: #333;
            padding: 13px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f5f5f5;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover td {
            background: #fafff7;
        }

        .badge-belum {
            font-size: 11px;
            font-weight: 600;
            background: #fff4e6;
            color: #d35400;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .badge-konfirmasi {
            font-size: 11px;
            font-weight: 600;
            background: #eaf5df;
            color: #457a24;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .btn-konfirmasi {
            font-size: 12px;
            font-weight: 700;
            background: #5a9e2f;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 6px 13px;
            text-decoration: none;
            transition: background 0.2s;
            display: inline-block;
        }

        .btn-konfirmasi:hover {
            background: #457a24;
            color: white;
        }

        .btn-hapus {
            font-size: 12px;
            font-weight: 700;
            background: white;
            color: #c0392b;
            border: 1px solid #e8b4b0;
            border-radius: 5px;
            padding: 5px 13px;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-hapus:hover {
            background: #c0392b;
            color: white;
            border-color: #c0392b;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #bbb;
            font-size: 14px;
        }

        .empty-state span {
            display: block;
            font-size: 36px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<nav class="navbar-admin">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <a href="admin.php" class="navbar-brand-admin">
                <img src="https://media.istockphoto.com/id/1045368942/vector/abstract-green-leaf-logo-icon-vector-design-ecology-icon-set-eco-icon.jpg?s=612x612&w=0&k=20&c=XIfHMI8r1G73blCpCBFmLIxCtOLx8qX0O3mZC9csRLs=" alt="Logo">
                <span>RuangTanam</span>
            </a>
            <span class="badge-admin">Admin Panel</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span style="font-size:13px; color:#555;">Halo, <strong><?= $_SESSION['admin'] ?></strong></span>
            <a href="logout.php" class="btn-logout">Logout</a>
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
