<?php
session_start();
include "db.php";

// Cek apakah pengguna telah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Mendapatkan ID produk dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mengambil data produk dari database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Produk tidak ditemukan.");
    }

    $product = $result->fetch_assoc();
    $current_image = $product['product_image']; // Menyimpan gambar saat ini
} else {
    die("ID produk tidak valid.");
}

// Proses pengeditan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];
    $product_image = $_FILES['product_image']['name'];
    $target_dir = "../admin/uploads/";
    $target_file = $target_dir . basename($product_image);

    // Memproses gambar baru jika diunggah
    if ($product_image) {
        // Upload file gambar baru
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            // Update query dengan gambar baru
            $sql = "UPDATE products SET product_name=?, product_description=?, product_price=?, product_image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsi", $product_name, $product_description, $product_price, $product_image, $id);
        } else {
            header("Location: edit-product.php?id=$id&error=Error uploading file.");
            exit();
        }
    } else {
        // Jika tidak ada gambar baru, gunakan gambar yang ada
        $sql = "UPDATE products SET product_name=?, product_description=?, product_price=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $product_name, $product_description, $product_price, $id);
    }

    // Eksekusi pernyataan
    if ($stmt->execute()) {
        header("Location: list-product.php"); // Redirect ke halaman produk setelah berhasil
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="Styles/edit-product.css">
</head>

<body>
    <h1>Edit Product</h1>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="edit-product.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>"
            required>

        <label for="product_price">Price:</label>
        <input type="number" step="0.01" name="product_price" value="<?php echo $product['product_price']; ?>" required>

        <label for="product_description">Description:</label>
        <textarea name="product_description"
            required><?php echo htmlspecialchars($product['product_description']); ?></textarea>

        <label for="product_image">Product Image:</label>
        <input type="file" name="product_image">
        <p>Current Image:</p>
        <?php if (isset($current_image) && $current_image): ?>
            <img src="../admin/uploads/<?php echo htmlspecialchars($current_image); ?>" alt="Product Image" width="150">
        <?php else: ?>
            <p>No image available.</p>
        <?php endif; ?>
        <div class="button">
        <button type="submit">Update Product</button>
        </div>
    </form>
</body>

</html>