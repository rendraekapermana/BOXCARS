<?php
session_start();
include "db.php"; // Koneksi database

// Menangani penghapusan produk
if (isset($_POST['id'])) {
    $product_id = $_POST['id'];

    // Menghapus produk dari database
    $sql = "DELETE FROM products WHERE id = ?"; // Query untuk menghapus produk
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id); // Mengikat parameter

    if ($stmt->execute()) {
        header("Location: list-product.php?message=Product deleted successfully."); // Redirect dengan pesan sukses
        exit();
    } else {
        header("Location: list-product.php?error=Error deleting product."); // Redirect dengan pesan error
        exit();
    }

    $stmt->close(); // Menutup pernyataan
}

// Mengambil semua data produk
$sql = "SELECT * FROM products"; // Query untuk mengambil semua produk
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Products</title>
    <link rel="stylesheet" href="Styles/list-product.css"> <!-- Menghubungkan stylesheet -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    <!-- Menghubungkan font -->
</head>

<body>
    <sidebar>
        <nav class="sidebar"> <!-- Navigasi samping -->
            <a href="#" class="logo">
                <img src="Images/logo-black.svg" alt="BOXCARS Logo"> <!-- Logo -->
            </a>
            <ul class="sidebar-list">
                <li><a href="index.php" class="dashboard"><img src="Images/dashboard.svg" alt=""> Dashboard</a></li>
                <li><a href="list-product.php"><img src="Images/product.svg" alt=""> List Product</a></li>
                <li><a href="add-product.php"><img src="Images/add-product.svg" alt=""> Add Product</a></li>
            </ul>
        </nav>
    </sidebar>

    <div class="container">
        <navbar>
            <div class="navbar">
                <h1>List Product</h1> <!-- Judul halaman -->
            </div>
        </navbar>

        <main>
            <div class="product-list">
                <?php if (isset($_GET['message'])): ?> <!-- Menampilkan pesan sukses jika ada -->
                    <div class="message message-success">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php elseif (isset($_GET['error'])): ?> <!-- Menampilkan pesan error jika ada -->
                    <div class="message message-error">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="product-list-container">
                    <h1>Product Information</h1> <!-- Informasi produk -->
                    <div class="header">
                        <ul class="header-list">
                            <li>Image</li>
                            <li>Product Name</li>
                            <li>Category</li>
                            <li>Price</li>
                            <li>Description</li>
                            <li>Action</li>
                        </ul>
                    </div>
                    <div class="product-list-content">
                        <?php if ($result->num_rows > 0): ?> <!-- Cek apakah ada produk -->
                            <?php while ($row = $result->fetch_assoc()): ?> <!-- Looping untuk setiap produk -->
                                <div class="product-item">
                                    <div class="product-image">
                                        <img src="uploads/<?php echo htmlspecialchars($row['product_image']); ?>"
                                            alt="Product Image"> <!-- Gambar produk -->
                                    </div>
                                    <div class="product-name"><?php echo htmlspecialchars($row["product_name"]); ?></div>
                                    <div class="product-category"><?php echo htmlspecialchars($row["product_category"]); ?>
                                    </div>
                                    <p class="product-price">Rp <?php echo number_format($row["product_price"], 2); ?></p>
                                    <!-- Harga produk -->
                                    <div class="product-description">
                                        <?php echo htmlspecialchars($row["product_description"]); ?> <!-- Deskripsi produk -->
                                    </div>
                                    <div class="product-action">
                                        <form action="list-product.php" method="POST" style="display:inline;">
                                            <!-- Form untuk menghapus produk -->
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this product?');"
                                                style="border: none; background: none; padding: 0; cursor: pointer;">
                                                <img src="Images/bin.svg" alt="Delete"> <!-- Ikon hapus -->
                                            </button>
                                        </form>
                                        <div class="separator"></div>
                                        <a href="edit-product.php?id=<?php echo $row['id']; ?>">
                                            <!-- Tautan untuk mengedit produk -->
                                            <img src="Images/ic-edit.svg" alt="Edit" style="cursor: pointer;">
                                        </a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No products found.</p> <!-- Pesan jika tidak ada produk -->
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

<?php
$conn->close(); // Menutup koneksi database
?>