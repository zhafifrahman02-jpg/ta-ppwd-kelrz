<?php
/**
 * Jalankan file ini SEKALI di browser untuk membuat akun admin default.
 * Setelah berhasil, HAPUS file ini dari server untuk keamanan.
 * Akses: http://localhost/ta-ppwd-kelrz/php/seed_admin.php
 */
require __DIR__ . '/koneksi.php';

$username = 'admin';
$password = 'admin123';
$hashed   = password_hash($password, PASSWORD_BCRYPT);

$cek = mysqli_prepare($koneksi, "SELECT id FROM admin WHERE username = ?");
mysqli_stmt_bind_param($cek, "s", $username);
mysqli_stmt_execute($cek);
mysqli_stmt_store_result($cek);

if (mysqli_stmt_num_rows($cek) > 0) {
    echo "<p style='font-family:sans-serif;color:orange;'>⚠️ Admin '<b>$username</b>' sudah ada. Tidak perlu seed ulang.</p>";
} else {
    $stmt = mysqli_prepare($koneksi, "INSERT INTO admin (username, password) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $username, $hashed);
    if (mysqli_stmt_execute($stmt)) {
        echo "<p style='font-family:sans-serif;color:green;'>✅ Admin default berhasil dibuat!<br>Username: <b>$username</b><br>Password: <b>$password</b></p>";
        echo "<p style='font-family:sans-serif;color:red;'><b>⚠️ Segera hapus file ini dari server!</b></p>";
    } else {
        echo "<p style='font-family:sans-serif;color:red;'>❌ Gagal membuat admin: " . mysqli_error($koneksi) . "</p>";
    }
}
?>
