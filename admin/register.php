<?php
session_start();
include "db.php"; // Koneksi database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Cek email sudah terdaftar
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error_message = "Email sudah terdaftar."; // Email sudah ada
    } else {
        // Tambah pengguna baru
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: login.php"); // Redirect ke halaman login
            exit();
        } else {
            $error_message = "Kesalahan saat pendaftaran."; // Kesalahan saat pendaftaran
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - BOXCARS</title>
    <link rel="stylesheet" href="Styles/login.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <main class="container">
        <div class="card">
            <img src="Images/logo-black.svg" alt="BOXCARS Logo" class="logo">
            <h1>Create an account</h1>
            <p>Unleash the luxury of automobiles.</p>
            <div class="form">
                <form action="" method="POST">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <input type="submit" value="Create Account" class="btn-get-started">
                    <p>Already have an account? <a href="login.php">Sign in</a></p>
                </form>
                <?php if (isset($error_message)): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>