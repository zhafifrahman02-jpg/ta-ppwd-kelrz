<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RuangTanam – Hydroponics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="indexpp.php">
                <img src="https://media.istockphoto.com/id/1045368942/vector/abstract-green-leaf-logo-icon-vector-design-ecology-icon-set-eco-icon.jpg?s=612x612&w=0&k=20&c=XIfHMI8r1G73blCpCBFmLIxCtOLx8qX0O3mZC9csRLs=" width="40" alt="SRI Logo">
                RuangTanam
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link" href="indexpp.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#paket">Paket</a></li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['user'])): ?>
                            <a class="nav-link active-page" href="formpp.php">Pesan Sekarang</a>
                        <?php else: ?>
                            <a class="nav-link active-page" href="login.php">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
                <div class="ms-lg-3 d-flex align-items-center gap-2">
                    <?php if (isset($_SESSION['user'])): ?>
                        <span style="font-size:13px; font-weight:600; style="font-size:13px; font-weight:600; color:var(--hijau);">
                            Halo, <?= htmlspecialchars($_SESSION['user_nama']) ?>
                        </span>
                        <a href="logout.php" style="font-size:13px; font-weight:600; color:#888; text-decoration:none;">Keluar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
       <div id="carousel" class="carousel slide w-100" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="5000">
                    <img src="../img/verticalfarm.jpg" class="d-block w-100" alt="Vertical Farm">
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="../img/hydroponics-system-planting-vegetables-herbs-without-using-soil-health.jpg" class="d-block w-100" alt="Hydroponics System">
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="../img/fresh-green-lettuce-leaves-close-up.jpg" class="d-block w-100" alt="Fresh Lettuce">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div class="hero-content">
                <h1>Perkenalkan <span class="teks-hijau">RuangTanam</span></h1>
                <p class="slogan">Bertani di Rumah. Tanpa Lahan, Tanpa Ribet.</p>
                <p class="keterangan">
                    Sistem pertanian vertikal hidroponik berbasis IoT untuk ekosistem perkotaan.
                    Rak kokoh dan kontroler otomatis memastikan nutrisi, air, dan cahaya presisi
                    24 jam tanpa pengawasan manual.
                </p>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="formpp.php" class="btn-hero">Pesan Sekarang</a>
                <?php else: ?>
                    <a href="login.php" class="btn-hero">Login untuk Memesan</a>
                <?php endif; ?>
                
                <nav class="nav-links">
                <a class="hero-nav-item" href="#tentang">
                    <span>Tentang</span>
                    <div class="underline"></div>
                </a>
                <a class="hero-nav-item" href="#gallery">
                    <span>Gallery</span>
                    <div class="underline"></div>
                </a>
                <a class="hero-nav-item" href="#paket">
                    <span>Paket</span>
                    <div class="underline"></div>
                </a>
            </nav>
        </div>
    </section>

    <section id="tentang" class="tentang-section">
        <div class="tentang-split">
            <!-- Kiri: Teks -->
            <div class="tentang-left">
                <span class="section-label-tag">Tentang Kami</span>
                <h2>Tentang <span class="teks-hijau">RuangTanam</span></h2>
                <p>RuangTanam adalah sistem pertanian vertikal hidroponik yang dirancang khusus untuk ekosistem perkotaan. Kami adalah perusahaan teknologi pertanian urban yang merancang sistem hidroponik dalam ruangan (<em>indoor hydroponic</em>) yang cerdas, estetik, dan mudah digunakan oleh siapa saja.</p>
                <p>Dari anak kos hingga pemilik restoran, dari pemula hingga sekolah yang ingin menghadirkan lab pertanian modern — RuangTanam hadir sebagai solusinya.</p>
                <p>Setiap produk kami dirancang dengan filosofi yang sama: teknologi yang menyederhanakan, bukan memperumit. Dengan sistem otomasi penyiraman, pencahayaan LED full spectrum, hingga pemantauan IoT langsung dari genggaman tangan Anda — merawat kebun kini semudah mengisi daya ponsel.</p>
                <p>Kami percaya bahwa masa depan pangan dimulai dari rumah. Dan rumah itu bisa dimulai dari sudut ruang tamu Anda.</p>
            </div>
            <!-- Kanan: Gambar -->
            <div class="tentang-right">
                <img src="../img/verticalfarm.jpg" alt="Pertanian Hidroponik Smart Rack IoT">
            </div>
        </div>
    </section>

    <section id="gallery" class="gallery-section">
        <div class="paket-content text-center">
            <span class="section-label-tag">Galeri</span>
            <h2>Gallery <span class="teks-hijau">RuangTanam</span></h2>
            <p class="gallery-sub">Lihat hasil nyata dari sistem Smart Rack IoT kami.</p>
        </div>
        <div class="gallery-grid mx-auto" style="max-width:1000px;">
            <img src="../img/verticalfarm.jpg" alt="Vertical Farm">
            <img src="../img/hydroponics-system-planting-vegetables-herbs-without-using-soil-health.jpg" alt="Hydroponics System">
            <img src="../img/fresh-green-lettuce-leaves-close-up.jpg" alt="Fresh Lettuce">
        </div>
    </section>

    <section id="paket" class="paket-section">
        <div class="paket-content">
            <span class="section-label-tag">Harga &amp; Paket</span>
            <h2>Pilihan Paket <span class="teks-hijau">RuangTanam</span></h2>
            <p class="paket-sub">Tiga pilihan paket sesuai kebutuhan dan skala penggunaan Anda.</p>
            <div class="paket-table-wrap">
                <table class="paket-table">
                    <thead>
                        <tr>
                            <th>Spesifikasi</th>
                            <th>BASIC<br><span>The Starter Kit</span></th>
                            <th class="col-featured">REGULAR<br><span>The Smart Choice</span></th>
                            <th>LENGKAP<br><span>The Pro-Education</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="harga-row">
                            <td>Harga</td>
                            <td>Rp 1.850.000</td>
                            <td class="col-featured">Rp 3.200.000</td>
                            <td>Rp 5.500.000</td>
                        </tr>
                        <tr>
                            <td>Material Rak</td>
                            <td>PVC High Quality</td>
                            <td class="col-featured">Aluminium / PVC Premium</td>
                            <td>Aluminium Industrial Grade</td>
                        </tr>
                        <tr>
                            <td>Lubang Tanam</td>
                            <td>27–30 lubang</td>
                            <td class="col-featured">27–30 lubang</td>
                            <td>27–30 lubang + Roda Pengunci</td>
                        </tr>
                        <tr>
                            <td>Otomasi</td>
                            <td>Timer Digital (lampu & pompa)</td>
                            <td class="col-featured">IoT Controller + Layar Sentuh</td>
                            <td>Full Smart System via WiFi</td>
                        </tr>
                        <tr>
                            <td>Sensor</td>
                            <td>—</td>
                            <td class="col-featured">Suhu & Kelembapan</td>
                            <td>Suhu, Kelembapan + Water Level</td>
                        </tr>
                        <tr>
                            <td>Monitoring Jarak Jauh</td>
                            <td>Tidak</td>
                            <td class="col-featured">Tidak (via layar unit)</td>
                            <td>Ya — Dashboard Web / App</td>
                        </tr>
                        <tr>
                            <td>Pencahayaan LED</td>
                            <td>White-Blue Spectrum</td>
                            <td class="col-featured">Full Spectrum Pink-Purple</td>
                            <td>High-Intensity (Adjustable)</td>
                        </tr>
                        <tr>
                            <td>Bonus</td>
                            <td>Nutrisi AB Mix + 2 Benih</td>
                            <td class="col-featured">Rockwool + Nutrisi 500ml + 3 Benih Premium</td>
                            <td>Starter Kit Lengkap</td>
                        </tr>
                        <tr>
                            <td>Ekstra</td>
                            <td>—</td>
                            <td class="col-featured">—</td>
                            <td>Training Online 1 Jam + E-Book Panduan</td>
                        </tr>
                        <tr>
                            <td>Cocok Untuk</td>
                            <td>Pemula / Hobiis</td>
                            <td class="col-featured">Hunian Modern / Apartemen</td>
                            <td>Sekolah / Kantor / Pro</td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-content">
            <h2>Siap Panen Sayur Segar di Rumah?</h2>
            <p>Pilih paket RuangTanam Anda dan mulai panen dalam 30 hari.</p>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="formpp.php" class="btn-cta">Pesan Sekarang</a>
            <?php else: ?>
                <a href="login.php" class="btn-cta">Login untuk Memesan</a>
            <?php endif; ?>
        </div>
    </section>

    <footer class="sri-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-5">
                    <h3>RuangTanam</h3>
                    <p>RuangTanam — solusi pertanian vertikal hidroponik untuk ekosistem perkotaan.</p>
                </div>
                <div class="col-12 col-md-4">
                    <h5>Kontak</h5>
                    <p>Email: RuangTanam@farming.com<br>
                    Phone: +62 812 3456 789<br>
                    Lokasi: Jakarta, Indonesia</p>
                </div>
            </div>
            <hr class="my-3">
            <p class="text-center footer-bottom mb-0">&copy; 2026 RuangTanam. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>