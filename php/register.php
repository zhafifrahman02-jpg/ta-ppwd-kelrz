<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}
if (isset($_SESSION['user'])) {
    header("Location: toko.php");
    exit;
}

$error_admin  = "";
$error_user   = "";
$active_tab   = "user";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/koneksi.php';

    // ── REGISTER ADMIN ──
    if (isset($_POST['register_admin'])) {
        $active_tab = "admin";
        $username   = trim($_POST['username_admin']);
        $password   = trim($_POST['password_admin']);
        $confirm    = trim($_POST['confirm_admin']);

        if (empty($username) || empty($password) || empty($confirm)) {
            $error_admin = "Semua field wajib diisi.";
        } elseif (strlen($password) < 6) {
            $error_admin = "Password minimal 6 karakter.";
        } elseif ($password !== $confirm) {
            $error_admin = "Konfirmasi password tidak cocok.";
        } else {
            $cek = mysqli_prepare($koneksi, "SELECT id FROM admin WHERE username = ?");
            mysqli_stmt_bind_param($cek, "s", $username);
            mysqli_stmt_execute($cek);
            mysqli_stmt_store_result($cek);

            if (mysqli_stmt_num_rows($cek) > 0) {
                $error_admin = "Username sudah digunakan.";
            } else {
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $stmt   = mysqli_prepare($koneksi, "INSERT INTO admin (username, password) VALUES (?, ?)");
                mysqli_stmt_bind_param($stmt, "ss", $username, $hashed);

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: login.php?registered=1");
                    exit;
                } else {
                    $error_admin = "Gagal membuat akun. Coba lagi.";
                }
            }
        }
    }

    // ── REGISTER USER ──
    if (isset($_POST['register_user'])) {
        $active_tab = "user";
        $nama       = trim($_POST['nama_lengkap']);
        $email      = trim($_POST['email']);
        $no_hp      = trim($_POST['no_hp']);
        $alamat     = trim($_POST['alamat']);
        $username   = trim($_POST['username_user']);
        $password   = trim($_POST['password_user']);
        $confirm    = trim($_POST['confirm_user']);

        if (empty($nama) || empty($email) || empty($no_hp) || empty($alamat) || empty($username) || empty($password) || empty($confirm)) {
            $error_user = "Semua field wajib diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_user = "Format email tidak valid.";
        } elseif (strlen($password) < 6) {
            $error_user = "Password minimal 6 karakter.";
        } elseif ($password !== $confirm) {
            $error_user = "Konfirmasi password tidak cocok.";
        } else {
            $cek = mysqli_prepare($koneksi, "SELECT id FROM users WHERE username = ? OR email = ?");
            mysqli_stmt_bind_param($cek, "ss", $username, $email);
            mysqli_stmt_execute($cek);
            mysqli_stmt_store_result($cek);

            if (mysqli_stmt_num_rows($cek) > 0) {
                $error_user = "Username atau email sudah digunakan.";
            } else {
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $stmt   = mysqli_prepare($koneksi, "INSERT INTO users (nama_lengkap, email, no_hp, alamat, username, password) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssssss", $nama, $email, $no_hp, $alamat, $username, $hashed);

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: login.php?registered=1");
                    exit;
                } else {
                    $error_user = "Gagal mendaftar. Coba lagi.";
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
    <title>Daftar – RuangTanam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Montserrat', sans-serif; }

        body {
            background: #f0f4ee;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .login-card {
            background: white;
            border-radius: 12px;
            padding: 44px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        .login-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .login-logo img { width: 36px; }
        .login-logo span { font-size: 20px; font-weight: 800; color: #5a9e2f; }

        /* Tab */
        .tab-wrapper {
            display: flex;
            background: #f0f4ee;
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 24px;
        }
        .tab-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 9px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #888;
            cursor: pointer;
            transition: all 0.2s;
        }
        .tab-btn.active {
            background: white;
            color: #5a9e2f;
            box-shadow: 0 1px 6px rgba(0,0,0,0.1);
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; }

        h4 { font-size: 17px; font-weight: 700; margin-bottom: 6px; color: #111; }
        p.sub { font-size: 13px; color: #888; margin-bottom: 24px; }

        label { font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px; display: block; }

        .form-control {
            border-radius: 6px; font-size: 13px;
            padding: 10px 14px; border: 1px solid #ddd; width: 100%;
        }
        .form-control:focus {
            border-color: #5a9e2f;
            box-shadow: 0 0 0 3px rgba(90,158,47,0.12);
            outline: none;
        }

        .btn-login {
            background: #5a9e2f; color: white;
            font-weight: 700; font-size: 14px;
            border: none; border-radius: 6px;
            padding: 11px; width: 100%;
            margin-top: 8px; transition: background 0.2s;
            cursor: pointer;
        }
        .btn-login:hover { background: #457a24; }

        .alert-error {
            background: #fff0f0; border: 1px solid #f5c6c6;
            color: #c0392b; border-radius: 6px;
            padding: 10px 14px; font-size: 13px; margin-bottom: 16px;
        }

        .divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }

        .section-title {
            font-size: 11px; font-weight: 700;
            color: #aaa; text-transform: uppercase;
            letter-spacing: 0.8px; margin-bottom: 14px;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        <img src="https://media.istockphoto.com/id/1045368942/vector/abstract-green-leaf-logo-icon-vector-design-ecology-icon-set-eco-icon.jpg?s=612x612&w=0&k=20&c=XIfHMI8r1G73blCpCBFmLIxCtOLx8qX0O3mZC9csRLs=" alt="Logo">
        <span>RuangTanam</span>
    </div>

    <!-- Tab Button -->
    <div class="tab-wrapper">
        <button class="tab-btn <?= $active_tab === 'user'  ? 'active' : '' ?>" onclick="switchTab('user', event)">Pembeli</button>
        <button class="tab-btn <?= $active_tab === 'admin' ? 'active' : '' ?>" onclick="switchTab('admin', event)">Admin</button>
    </div>

    <!-- ── Tab: Pembeli ── -->
    <div class="tab-content <?= $active_tab === 'user' ? 'active' : '' ?>" id="tab-user">
        <h4>Buat Akun</h4>
        <p class="sub">Daftar untuk mulai berbelanja di RuangTanam.</p>

        <?php if ($error_user): ?>
            <div class="alert-error"><?= htmlspecialchars($error_user) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="section-title">Data Pribadi</div>

            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
            </div>
            <div class="mb-3">
                <label>Nomor HP</label>
                <input type="tel" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>

            <hr class="divider">
            <div class="section-title">Data Akun</div>

            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username_user" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password_user" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_user" class="form-control" placeholder="Ulangi password" required>
            </div>

            <button type="submit" name="register_user" class="btn-login">Daftar Sekarang</button>
        </form>

        <div class="text-center mt-3" style="font-size:13px; color:#888;">
            Sudah punya akun?
            <a href="login.php" style="color:#5a9e2f; font-weight:600; text-decoration:none;">Login di sini</a>
        </div>
    </div>

    <!-- ── Tab: Admin ── -->
    <div class="tab-content <?= $active_tab === 'admin' ? 'active' : '' ?>" id="tab-admin">
        <h4>Daftar Akun Admin</h4>
        <p class="sub">Buat akun administrator baru.</p>

        <?php if ($error_admin): ?>
            <div class="alert-error"><?= htmlspecialchars($error_admin) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username_admin" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password_admin" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_admin" class="form-control" placeholder="Ulangi password" required>
            </div>

            <button type="submit" name="register_admin" class="btn-login">Buat Akun Admin</button>
        </form>

        <div class="text-center mt-3" style="font-size:13px; color:#888;">
            Sudah punya akun?
            <a href="login.php" style="color:#5a9e2f; font-weight:600; text-decoration:none;">Login di sini</a>
        </div>
    </div>
</div>

<script>
    function switchTab(tab, event) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
    }
</script>
</body>
</html>