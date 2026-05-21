<?php
session_start();

if (isset($_SESSION['admin'])) { header("Location: admin.php"); exit; }
if (isset($_SESSION['user']))  { header("Location: ../indexpp.php"); exit; }

$error   = "";
$success = "";
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'user';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/koneksi.php';
    $role     = isset($_POST['role']) ? $_POST['role'] : 'user';
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "Semua field wajib diisi.";
        $tab = $role;
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
        $tab = $role;
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
        $tab = $role;
    } else {
        if ($role === 'admin') {
            $cek = mysqli_prepare($koneksi, "SELECT id FROM admin WHERE username = ?");
            mysqli_stmt_bind_param($cek, "s", $username);
            mysqli_stmt_execute($cek);
            mysqli_stmt_store_result($cek);
            if (mysqli_stmt_num_rows($cek) > 0) {
                $error = "Username admin sudah digunakan.";
                $tab = 'admin';
            } else {
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $stmt = mysqli_prepare($koneksi, "INSERT INTO admin (username, password) VALUES (?, ?)");
                mysqli_stmt_bind_param($stmt, "ss", $username, $hashed);
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Akun admin berhasil dibuat! Silakan login.";
                    $tab = 'admin';
                } else {
                    $error = "Gagal membuat akun. Coba lagi.";
                    $tab = 'admin';
                }
            }
        } else {
            // User registration — butuh field tambahan
            $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $no_hp        = trim($_POST['no_hp'] ?? '');
            $alamat       = trim($_POST['alamat'] ?? '');

            if (empty($nama_lengkap) || empty($email) || empty($no_hp) || empty($alamat)) {
                $error = "Semua field data diri wajib diisi.";
                $tab = 'user';
            } else {
                $cek = mysqli_prepare($koneksi, "SELECT id FROM users WHERE username = ? OR email = ?");
                mysqli_stmt_bind_param($cek, "ss", $username, $email);
                mysqli_stmt_execute($cek);
                mysqli_stmt_store_result($cek);
                if (mysqli_stmt_num_rows($cek) > 0) {
                    $error = "Username atau email sudah terdaftar.";
                    $tab = 'user';
                } else {
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $stmt = mysqli_prepare($koneksi, "INSERT INTO users (nama, email, no_hp, username, password) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sssss", $nama_lengkap, $email, $no_hp, $username, $hashed);
                if (mysqli_stmt_execute($stmt)) {
                $success = "Akun berhasil dibuat! Silakan login.";
                $tab = 'user';
                } else {
                    $error = "Gagal membuat akun. Coba lagi.";
                        $tab = 'user';
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi – RuangTanam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --hijau:       #2d6a4f;
            --hijau-dark:  #1b4332;
            --hijau-light: #74c69d;
            --hijau-muda:  #d8f3dc;
            --hijau-pale:  #f0f7f2;
            --text-main:   #1a2e1e;
            --text-muted:  #6b7c72;
            --border:      #d0e4d8;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { overflow-x: hidden; }
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--hijau-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: -120px; left: -120px;
            width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(116,198,157,0.14) 0%, transparent 70%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -100px; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(116,198,157,0.10) 0%, transparent 70%);
            pointer-events: none;
        }
        .auth-card {
            background: white;
            border-radius: 20px;
            padding: 40px 44px 44px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.22);
            position: relative;
            z-index: 1;
        }
        .auth-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .auth-logo img { width: 34px; border-radius: 50%; }
        .auth-logo span {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem;
            color: var(--hijau-dark);
        }
        .role-tabs {
            display: flex;
            background: var(--hijau-pale);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 28px;
            gap: 4px;
        }
        .role-tab {
            flex: 1;
            padding: 9px 12px;
            border-radius: 9px;
            border: none;
            background: transparent;
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            text-align: center;
        }
        .role-tab.active {
            background: white;
            color: var(--hijau-dark);
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        }
        .auth-card h4 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            font-weight: 400;
            color: var(--text-main);
            margin-bottom: 6px;
        }
        .auth-card p.sub {
            font-size: 0.86rem;
            color: var(--text-muted);
            margin-bottom: 24px;
            line-height: 1.6;
        }
        label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
            display: block;
        }
        .form-control {
            font-family: 'Outfit', sans-serif;
            border-radius: 8px;
            font-size: 0.9rem;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            color: var(--text-main);
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .form-control:focus {
            outline: none;
            border-color: #40916c;
            box-shadow: 0 0 0 3px rgba(64,145,108,0.15);
        }
        .mb-3 { margin-bottom: 18px; }
        .btn-auth {
            font-family: 'Outfit', sans-serif;
            background: var(--hijau);
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            border: none;
            border-radius: 100px;
            padding: 12px;
            width: 100%;
            margin-top: 10px;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-auth:hover { background: var(--hijau-dark); transform: translateY(-1px); }
        .alert-error {
            background: #fff5f5;
            border: 1.5px solid #f5c6cb;
            color: #922b21;
            border-radius: 8px;
            padding: 11px 15px;
            font-size: 0.84rem;
            margin-bottom: 18px;
            line-height: 1.5;
        }
        .alert-success {
            background: var(--hijau-pale);
            border: 1.5px solid var(--hijau-muda);
            color: var(--hijau-dark);
            border-radius: 8px;
            padding: 11px 15px;
            font-size: 0.84rem;
            margin-bottom: 18px;
            font-weight: 600;
            line-height: 1.5;
        }
        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.82rem;
            color: var(--text-muted);
        }
        .auth-footer a {
            color: var(--hijau);
            font-weight: 600;
            text-decoration: none !important;
        }
        .auth-footer a:hover { color: var(--hijau-dark); }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--text-muted);
            text-decoration: none;
            margin-bottom: 20px;
        }
        .back-link:hover { color: var(--hijau); }
        .section-divider {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--hijau-light);
            margin: 20px 0 14px;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <img src="https://media.istockphoto.com/id/1045368942/vector/abstract-green-leaf-logo-icon-vector-design-ecology-icon-set-eco-icon.jpg?s=612x612&w=0&k=20&c=XIfHMI8r1G73blCpCBFmLIxCtOLx8qX0O3mZC9csRLs=" alt="Logo">
            <span>RuangTanam</span>
        </div>

        <a href="../indexpp.php" class="back-link">&#8592; Kembali ke Beranda</a>

        <!-- Role Tab Switcher -->
        <div class="role-tabs">
            <button class="role-tab <?= ($tab === 'user') ? 'active' : '' ?>" onclick="switchTab('user')">Daftar User</button>
            <button class="role-tab <?= ($tab === 'admin') ? 'active' : '' ?>" onclick="switchTab('admin')">Daftar Admin</button>
        </div>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Tab: User -->
        <div class="tab-panel <?= ($tab === 'user') ? 'active' : '' ?>" id="panel-user">
            <h4>Buat Akun User</h4>
            <p class="sub">Daftar untuk bisa memesan produk RuangTanam.</p>
            <form method="POST">
                <input type="hidden" name="role" value="user">
                <div class="section-divider">Data Diri</div>
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
                </div>
                <div class="mb-3">
                    <label>No. HP / WhatsApp</label>
                    <input type="tel" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2" placeholder="Jalan, Kota, Provinsi" required></textarea>
                </div>
                <div class="section-divider">Akun Login</div>
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Buat username unik" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn-auth">Buat Akun User</button>
            </form>
            <div class="auth-footer">
                Sudah punya akun? <a href="login.php?tab=user">Login di sini</a>
            </div>
        </div>

        <!-- Tab: Admin -->
        <div class="tab-panel <?= ($tab === 'admin') ? 'active' : '' ?>" id="panel-admin">
            <h4>Buat Akun Admin</h4>
            <p class="sub">Buat akun administrator baru.</p>
            <form method="POST">
                <input type="hidden" name="role" value="admin">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username admin" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                </div>
                <button type="submit" class="btn-auth">Buat Akun Admin</button>
            </form>
            <div class="auth-footer">
                Sudah punya akun admin? <a href="login.php?tab=admin">Login Admin di sini</a>
            </div>
        </div>
    </div>

    <script>
        function switchTab(role) {
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.getElementById('panel-' + role).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>