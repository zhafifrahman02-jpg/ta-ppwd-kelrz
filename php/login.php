<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}
if (isset($_SESSION['user'])) {
    header("Location: indexpp.php");
    exit;
}

$error_admin = "";
$error_user  = "";
$active_tab  = "user";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/koneksi.php';
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // ── LOGIN ADMIN ──
    if (isset($_POST['login_admin'])) {
        $active_tab = "admin";
        $username   = trim($_POST['username_admin']);
        $password   = trim($_POST['password_admin']);

        $stmt = mysqli_prepare($koneksi, "SELECT * FROM admin WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $data = mysqli_fetch_assoc($result);
            if (password_verify($password, $data['password'])) {
                $_SESSION['admin'] = $data['username'];
                header("Location: admin.php");
                exit;
            } else {
                $error_admin = "Username atau password salah.";
            }
        } else {
            $error_admin = "Username atau password salah.";
        }
    }

    // ── LOGIN USER ──
    if (isset($_POST['login_user'])) {
        $active_tab = "user";
        $username   = trim($_POST['username_user']);
        $password   = trim($_POST['password_user']);

        $stmt = mysqli_prepare($koneksi, "SELECT * FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $data = mysqli_fetch_assoc($result);
            if (password_verify($password, $data['password'])) {
                $_SESSION['user']      = $data['username'];
                $_SESSION['user_id']   = $data['id'];
                $_SESSION['user_nama'] = $data['nama_lengkap'];
                header("Location: indexpp.php");
                exit;
            } else {
                $error_user = "Username atau password salah.";
            }
        } else {
            $error_user = "Username atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin – RuangTanam</title>
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

        .login-logo img {
            width: 36px;
        }

        .login-logo span {
            font-size: 20px;
            font-weight: 800;
            color: #5a9e2f;
        }

        .login-card h4 {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #111;
        }

        .login-card p.sub {
            font-size: 13px;
            color: #888;
            margin-bottom: 24px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 6px;
            font-size: 13px;
            padding: 10px 14px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #5a9e2f;
            box-shadow: 0 0 0 3px rgba(90,158,47,0.12);
        }

        .btn-login {
            background: #5a9e2f;
            color: white;
            font-weight: 700;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            padding: 11px;
            width: 100%;
            margin-top: 8px;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: #457a24;
        }

        .alert-error {
            background: #fff0f0;
            border: 1px solid #f5c6c6;
            color: #c0392b;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .tab-wrapper {
            display: flex;
            background: #f0f4ee;
            border-radius: 8px;
            padding: 4px;
            margin-bottom: 24px;
        }
        .tab-btn {
            flex: 1; border: none;
            background: transparent;
            padding: 9px; border-radius: 6px;
            font-size: 13px; font-weight: 600;
            color: #888; cursor: pointer;
            transition: all 0.2s;
        }
        .tab-btn.active {
            background: white; color: #5a9e2f;
            box-shadow: 0 1px 6px rgba(0,0,0,0.1);
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
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

    <!-- Tab Pembeli -->
    <div class="tab-content <?= $active_tab === 'user' ? 'active' : '' ?>" id="tab-user">
        <?php if ($error_user): ?>
            <div class="alert-error"><?= htmlspecialchars($error_user) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['registered'])): ?>
            <div class="alert-success">Akun berhasil dibuat! Silakan login.</div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username_user" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password_user" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login_user" class="btn-login">Masuk</button>
        </form>

        <div class="text-center mt-3" style="font-size:13px; color:#888;">
            Belum punya akun?
            <a href="register.php" style="color:#5a9e2f; font-weight:600; text-decoration:none;">Daftar di sini</a>
        </div>
    </div>

    <!-- Tab: Admin -->
    <div class="tab-content <?= $active_tab === 'admin' ? 'active' : '' ?>" id="tab-admin">
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
                <input type="password" name="password_admin" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login_admin" class="btn-login">Masuk sebagai Admin</button>
        </form>
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
