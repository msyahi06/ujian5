<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $pesanan = $_POST['pesanan'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];

    $check_stmt = $conn->prepare("SELECT * FROM users WHERE pesanan = ?");
    $check_stmt->bind_param("s", $pesanan);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Pesanan sudah ada. Silakan pilih pesanan lain.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (nama, pesanan, alamat, telepon) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $pesanan, $alamat, $telepon);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Pesanan berhasil dikirim.";
        } else {
            echo "Gagal memesan.";
        };

        $stmt->close();
    }

    $check_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan</title>
</head>
<style>
body {
    margin: 0;
    padding: 0;
    align-items: center;
    justify-content: center;
}

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

h2 {
    text-align: center;
    margin-bottom: 2rem;
}

form {
    max-width: 400px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

input {
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background-color: red;
    cursor: pointer;
    border-radius: 5px;
    padding: 7px;
}

p {
    text-align: center;
    margin-top: 2rem;
}
</style>
<body>
    <div class="container">
        <h2>Pesan disini</h2>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        <form action="pesanan.php" method="post">
            <input type="text" name="nama" placeholder="Nama" required>
            <input type="text" name="pesanan" placeholder="Pesanan" required>
            <input type="text" name="alamat" placeholder="Alamat" required>
            <input type="text" name="telepon" placeholder="08123456789" required>
            <button type="submit" class="btn btn-teal">Pesan</button>
        </form>
    </div>
</body>
</html>

