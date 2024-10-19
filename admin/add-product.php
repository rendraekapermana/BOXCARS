<?php
session_start();
include "db.php"; // Menghubungkan ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $product_category = $_POST['product_category'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];

    // Mengelola unggahan file
    $targetDir = "uploads/";
    $fileName = basename($_FILES["product_image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Cek apakah file sudah ada
    if (file_exists($targetFilePath)) {
        $_SESSION['message'] = "Error: File already exists.";
    } else {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
            // Upload berhasil, simpan ke database
            $sql = "INSERT INTO products (product_name, product_category, product_price, product_description, product_image) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdss", $product_name, $product_category, $product_price, $product_description, $fileName);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Product added successfully!";
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Failed to upload image.";
        }
    }

    // Redirect kembali ke form untuk menampilkan pesan
    header("Location: add-product.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="Styles/add-product.css"> <!-- Menghubungkan stylesheet -->
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
            </ul>
        </nav>
    </sidebar>

    <div class="container">
        <navbar>
            <div class="navbar">
                <h1>Add Product</h1> <!-- Judul halaman -->
            </div>
        </navbar>

        <main>
            <div class="general-information">
                <div class="image-upload-container">
                    <h1>Product Information</h1> <!-- Informasi produk -->
                    <form action="add-product.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="product-name">Product Name:</label>
                            <input type="text" id="product-name" name="product_name" required
                                placeholder="Enter product name">
                        </div>

                        <div class="form-group">
                            <label for="product-description">Product Description:</label>
                            <input type="text" id="product-description" name="product_description" required
                                placeholder="Enter product description">
                        </div>

                        <div class="form-group">
                            <label for="product-category">Product Category:</label>
                            <select name="product_category" id="product-category" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="SUV">SUV</option>
                                <option value="Sedan">Sedan</option>
                                <option value="Supercar">Supercar</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="product-price">Product Price:</label>
                            <input type="number" id="product-price" name="product_price" min="0" required
                                placeholder="Enter product price">
                        </div>

                        <div class="form-group">
                            <label for="product-image">Upload Image:</label>
                            <input type="file" id="product-image" name="product_image" required><br>
                        </div>

                        <button type="submit" class="upload-btn">Add Product</button>
                    </form>

                    <!-- Tampilkan pesan jika ada -->
                    <?php
                    if (isset($_SESSION['message'])) {
                        // Menggunakan kata yang sesuai untuk mendeteksi kelas
                        $class = strpos($_SESSION['message'], 'successfully') !== false ? 'success' : 'error';
                        echo "<div class='message $class'>" . $_SESSION['message'] . "</div>";
                        unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>
</body>

</html>