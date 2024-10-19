<?php
session_start();
include "db.php"; // Sertakan file koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Siapkan pernyataan SQL untuk mencegah SQL injection
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $stmt->store_result();

        // Periksa apakah email ada
        if ($stmt->num_rows > 0) {
            // Ikat hasil untuk mendapatkan password yang di-hash
            $stmt->bind_result($stored_password);
            $stmt->fetch();

            // Verifikasi password
            if (password_verify($password, $stored_password)) {
                $_SESSION['email'] = $email; // Simpan email dalam sesi
                header('Location: index.php'); // Alihkan ke halaman utama
                exit();
            } else {
                $error_message = "Email atau password tidak sesuai.";
            }
        } else {
            $error_message = "Email atau password tidak sesuai.";
        }
    } else {
        $error_message = "Kueri database gagal.";
    }

    $stmt->close(); // Tutup pernyataan
}
?>

<!-- Kode HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login BOXCARS</title>
    <link rel="stylesheet" href="Styles/login.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <main class="container">
        <div class="card">
            <img src="Images/logo-black.svg" alt="BOXCARS Logo" class="logo">
            <h1>Login ke akun Anda</h1>
            <p>Rasakan kemewahan otomotif, di mana keanggunan dan kekuatan berpadu.</p>
            <div class="form">
                <form action="" method="POST">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Masukkan email Anda" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password Anda" required>
                    <input type="submit" value="Login" class="btn-get-started">
                    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
                </form>
                <?php if (isset($error_message)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>