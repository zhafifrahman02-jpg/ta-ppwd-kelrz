<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/koneksi.php';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepared Statement — aman dari SQL Injection
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
            $error = "Username atau password salah.";
        }
    } else {
        $error = "Username atau password salah.";
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
            color: #222;
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
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="https://media.istockphoto.com/id/1045368942/vector/abstract-green-leaf-logo-icon-vector-design-ecology-icon-set-eco-icon.jpg?s=612x612&w=0&k=20&c=XIfHMI8r1G73blCpCBFmLIxCtOLx8qX0O3mZC9csRLs=" alt="Logo">
            <span>RuangTanam</span>
        </div>

        <h4>Login Admin</h4>
        <p class="sub">Halaman ini hanya untuk administrator.</p>

        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>
        <div class="text-center mt-3" style="font-size:13px; color:#888;">
            Belum punya akun? 
            <a href="register.php" style="color:#5a9e2f; font-weight:600; text-decoration:none;">Daftar di sini</a>
        </div>
    </div>
</body>
</html>
