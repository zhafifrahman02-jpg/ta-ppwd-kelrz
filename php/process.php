<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan – SRI Smart Rack IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2">
                <img src="https://media.istockphoto.com/id/1045368942/vector/abstract-green-leaf-logo-icon-vector-design-ecology-icon-set-eco-icon.jpg?s=612x612&w=0&k=20&c=XIfHMI8r1G73blCpCBFmLIxCtOLx8qX0O3mZC9csRLs=" width="40" alt="SRI Logo">
                RuangTanam
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                </ul>
            </div>
        </div>
    </nav>

    <div class="process-wrap">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-7">
                    <div class="process-card">

<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<div class="alert-error"><strong>Akses tidak valid.</strong> Silakan isi formulir terlebih dahulu.</div>';
    echo '<a href="../formpp.html" class="btn btn-hijau mt-3 d-block text-center">Kembali ke Form</a>';
    exit;
}

$nama              = htmlspecialchars(trim($_POST['nama'] ?? ''));
$email             = htmlspecialchars(trim($_POST['email'] ?? ''));
$telepon           = htmlspecialchars(trim($_POST['telepon'] ?? ''));
$umur              = (int)($_POST['umur'] ?? 0);
$jenis_kelamin     = htmlspecialchars($_POST['jenis_kelamin'] ?? '');
$alamat            = htmlspecialchars(trim($_POST['alamat'] ?? ''));
$kategori_pengguna = htmlspecialchars($_POST['kategori_pengguna'] ?? '');
$paket             = htmlspecialchars($_POST['paket'] ?? '');
$jumlah_unit       = (int)($_POST['jumlah_unit'] ?? 0);
$catatan           = htmlspecialchars(trim($_POST['catatan'] ?? ''));
$promo             = isset($_POST['promo']);

$errors = [];
if (empty($nama))                               $errors[] = "Nama lengkap wajib diisi.";
if (empty($email))                              $errors[] = "Email wajib diisi.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid.";
if (empty($telepon))                            $errors[] = "Nomor telepon wajib diisi.";
if ($umur < 10 || $umur > 99)                   $errors[] = "Umur harus antara 10–99 tahun.";
if (empty($jenis_kelamin))                      $errors[] = "Jenis kelamin wajib dipilih.";
if (empty($alamat))                             $errors[] = "Alamat pengiriman wajib diisi.";
if (empty($kategori_pengguna))                  $errors[] = "Kategori pengguna wajib dipilih.";
if (empty($paket))                              $errors[] = "Paket wajib dipilih.";
if ($jumlah_unit < 1)                           $errors[] = "Jumlah unit minimal 1.";

if (!empty($errors)) {
    echo '<div class="alert-error"><strong>Pesanan gagal diproses.</strong><ul>';
    foreach ($errors as $e) echo '<li>' . $e . '</li>';
    echo '</ul></div>';
    echo '<a href="../formpp.html" class="btn btn-hijau mt-3 d-block text-center">Kembali & Perbaiki</a>';
    exit;
}

$harga_paket = [
    "BASIC"   => 1850000,
    "REGULAR" => 3200000,
    "LENGKAP" => 5500000,
];

$tagline_paket = [
    "BASIC"   => "The Starter Kit",
    "REGULAR" => "The Smart Choice",
    "LENGKAP" => "The Pro-Education",
];

$harga_satuan = $harga_paket[$paket];
$subtotal     = $harga_satuan * $jumlah_unit;

if ($jumlah_unit >= 5) {
    $persen_diskon = 15;
    $ket_diskon    = "Diskon pembelian 5+ unit";
} elseif ($jumlah_unit >= 3) {
    $persen_diskon = 10;
    $ket_diskon    = "Diskon pembelian 3–4 unit";
} elseif ($jumlah_unit >= 2) {
    $persen_diskon = 5;
    $ket_diskon    = "Diskon pembelian 2 unit";
} else {
    $persen_diskon = 0;
    $ket_diskon    = "";
}

$nilai_diskon = $subtotal * ($persen_diskon / 100);
$total_bayar  = $subtotal - $nilai_diskon;

switch ($paket) {
    case "BASIC":
        $ongkir = 75000;
        break;
    case "REGULAR":
        $ongkir = 100000;
        break;
    case "LENGKAP":
        $ongkir = 0;
        break;
    default:
        $ongkir = 75000;
}

$total_akhir = $total_bayar + $ongkir;

$kode = "SRI-" . strtoupper(substr(str_replace(' ', '', $nama), 0, 3))
      . "-" . date('dmY')
      . "-" . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);

if ($umur < 18) {
    $kategori_umur = "Pelajar";
} elseif ($umur < 30) {
    $kategori_umur = "Dewasa Muda";
} elseif ($umur < 50) {
    $kategori_umur = "Dewasa";
} else {
    $kategori_umur = "Senior";
}

$sapaan = ($jenis_kelamin === "Laki-laki") ? "Bapak" : "Ibu";
?>

                        <div style="margin-bottom:20px;">
                            <h2 style="font-size:22px; font-weight:800; margin-bottom:4px;">Pesanan Berhasil Diterima</h2>
                            <p style="color:#666; font-size:13px;">
                                Terima kasih, <?= $sapaan . ' ' . $nama ?>.<br>
                                Kode Pesanan: <span class="kode-pesanan"><?= $kode ?></span>
                            </p>
                        </div>

                        <p class="section-label">Data Pemesan</p>
                        <table class="summary-table">
                            <?php
                            $data_pemesan = [
                                "Nama Lengkap"       => $nama,
                                "Email"              => $email,
                                "WhatsApp / Telepon" => $telepon,
                                "Umur"               => $umur . " tahun (" . $kategori_umur . ")",
                                "Jenis Kelamin"      => $jenis_kelamin,
                                "Kategori Pengguna"  => $kategori_pengguna,
                                "Alamat Pengiriman"  => $alamat,
                            ];
                            foreach ($data_pemesan as $key => $val) {
                                echo "<tr><td>{$key}</td><td>{$val}</td></tr>";
                            }
                            ?>
                        </table>

                        <p class="section-label">Detail Pesanan</p>
                        <table class="summary-table">
                            <tr>
                                <td>Paket</td>
                                <td><?= $paket ?> – <?= $tagline_paket[$paket] ?></td>
                            </tr>
                            <tr>
                                <td>Harga Satuan</td>
                                <td>Rp <?= number_format($harga_satuan, 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td>Jumlah Unit</td>
                                <td><?= $jumlah_unit ?> unit</td>
                            </tr>
                            <tr>
                                <td>Subtotal</td>
                                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                            <?php if ($persen_diskon > 0): ?>
                            <tr>
                                <td><?= $ket_diskon ?> (<?= $persen_diskon ?>%)</td>
                                <td style="color:#2e7d32;">- Rp <?= number_format($nilai_diskon, 0, ',', '.') ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Ongkos Kirim</td>
                                <td><?= $ongkir === 0 ? 'Gratis' : 'Rp ' . number_format($ongkir, 0, ',', '.') ?></td>
                            </tr>
                            <tr class="total-row">
                                <td>Total Bayar</td>
                                <td>Rp <?= number_format($total_akhir, 0, ',', '.') ?></td>
                            </tr>
                        </table>

                        <?php if (!empty($catatan)): ?>
                        <p class="section-label">Catatan</p>
                        <p style="font-size:13px; color:#555; font-style:italic;">"<?= $catatan ?>"</p>
                        <?php endif; ?>

                        <p style="font-size:13px; color:#888; margin-top:14px;">
                            <?= $promo ? 'Anda akan menerima tips berkebun dan info produk terbaru dari SRI.' : 'Anda tidak berlangganan info produk.' ?>
                        </p>



                        <a href="../indexpp.html" class="btn btn-hijau w-100 mt-4 py-2">Kembali ke Halaman Utama</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="sri-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-5">
                    <h3>SRI</h3>
                    <p>Smart Rack IoT — solusi pertanian vertikal hidroponik untuk ekosistem perkotaan.</p>
                </div>
                <div class="col-12 col-md-4">
                    <h5>Kontak</h5>
                    <p>Email: info@sri-farming.com<br>
                    Phone: +62 812 3456 789<br>
                    Lokasi: Jakarta, Indonesia</p>
                </div>
            </div>
            <hr class="my-3">
            <p class="text-center footer-bottom mb-0">&copy; 2026 SRI – Smart Rack IoT. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>