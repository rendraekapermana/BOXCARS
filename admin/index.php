<?php
session_start();
include "db.php"; // Menghubungkan ke database

// Skrip logout
if (isset($_GET['logout'])) {
    session_destroy(); // Menghancurkan sesi
    header("Location: login.php"); // Redirect ke halaman login setelah logout
    exit();
}

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['email'])) { // Memeriksa keberadaan email di sesi
    header("Location: login.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Mengambil data produk
$sql = "SELECT * FROM products"; // Query untuk mengambil semua produk
$result = $conn->query($sql);
?>

<!-- Kode HTML -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Styles/style.css"> <!-- Menghubungkan stylesheet -->
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
        rel="stylesheet"> <!-- Menghubungkan font -->
</head>

<body>
    <sidebar>
        <nav class="sidebar"> <!-- Navigasi samping -->
            <a href="#" class="logo">
                <img src="Images/logo-black.svg" alt="BOXCARS Logo"> <!-- Logo -->
            </a>
            <ul class="sidebar-list">
                <li>
                    <a href="index.php" class="dashboard">
                        <img src="Images/dashboard.svg" alt="" class="dashboard-icon"> Dashboard
                    </a>
                </li>
                <li>
                    <a href="list-product.php">
                        <img src="Images/product.svg" alt=""> List Product</a>
                </li>
                <li>
                    <a href="add-product.php">
                        <img src="Images/add-product.svg" alt=""> Add Product</a>
                </li>
                <li>
                    <a href="?logout" class="logout">Logout</a> <!-- Tautan untuk logout -->
                </li>
            </ul>
        </nav>
    </sidebar>

    <div class="container">
        <navbar>
            <div class="navbar">
                <h1>Dashboard</h1> <!-- Judul halaman -->
            </div>
        </navbar>

        <main>
            <div class="card-container"> <!-- Container untuk kartu statistik -->
                <div class="card">
                    <div class="total-user">
                        <p>Total User</p>
                        <h1>40,689</h1> <!-- Jumlah total pengguna -->
                        <img class="icon" src="Images/ic-user.svg" alt="product"> <!-- Ikon pengguna -->
                        <p class="growth-info">
                            <img src="Images/ic-trending-up.svg" alt="trending"> <!-- Ikon pertumbuhan -->
                            <span class="percentage">8.5%</span> Up from yesterday
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="total-user">
                        <p>Total Product</p> <!-- Judul kartu -->
                        <h1>40,689</h1> <!-- Jumlah total produk -->
                        <img class="icon" src="Images/ic-product.svg" alt="product"> <!-- Ikon produk -->
                        <p class="growth-info">
                            <img src="Images/ic-trending-up.svg" alt="trending">
                            <span class="percentage">1.3%</span> Up from past week
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="total-user">
                        <p>Total Sales</p> <!-- Judul kartu -->
                        <h1>40,689</h1> <!-- Jumlah total penjualan -->
                        <img class="icon" src="Images/ic-sales.svg" alt="product"> <!-- Ikon penjualan -->
                        <p class="growth-info">
                            <img src="Images/ic-trending-down.svg" alt="trending">
                            <span class="percentage-down">4.3%</span> Down from yesterday
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="total-user">
                        <p>Total Pending Orders</p> <!-- Judul kartu -->
                        <h1>40,689</h1> <!-- Jumlah total pesanan tertunda -->
                        <img class="icon" src="Images/ic-pending.svg" alt="product"> <!-- Ikon pesanan tertunda -->
                        <p class="growth-info">
                            <img src="Images/ic-trending-up.svg" alt="trending">
                            <span class="percentage">1.8%</span> Up from yesterday
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-deals-details"> <!-- Detail penawaran -->
                <h1>Deals Details</h1>
                <div class="product-details"> <!-- Judul kolom detail produk -->
                    <p>Product Name</p>
                    <p>Location</p>
                    <p>Date</p>
                    <p>Piece</p>
                    <p>Amount</p>
                    <p>Status</p>
                </div>
                <div class="product-listing"> <!-- Daftar produk -->
                    <?php if ($result->num_rows > 0): ?> <!-- Cek apakah ada produk -->
                        <?php while ($row = $result->fetch_assoc()): ?> <!-- Looping untuk setiap produk -->
                            <div class="product-listing-item">
                                <p class="product-name">
                                    <?php echo htmlspecialchars($row["product_name"]); ?> <!-- Nama produk -->
                                    <img src="uploads/<?php echo $row['product_image']; ?>" alt="product" class="product-image">
                                    <!-- Gambar produk -->
                                </p>
                                <p class="product-location">Malang</p> <!-- Lokasi produk -->
                                <p class="product-date">19-10-2024</p> <!-- Tanggal produk -->
                                <p class="product-piece">1</p> <!-- Jumlah produk -->
                                <p class="product-amount">Rp <?php echo number_format($row["product_price"], 2); ?></p>
                                <!-- Harga produk -->
                                <p class="product-status">Available</p> <!-- Status produk -->
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No products found.</p> <!-- Pesan jika tidak ada produk -->
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

<?php
$conn->close(); // Menutup koneksi database
?>