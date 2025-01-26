<?php
// hapus.php
require_once '../config/database.php'; // Pastikan file database sudah benar
require_once '../config/session.php'; // Pastikan session sudah dimulai

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'] ?? null;

    if ($userId) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $result = $stmt->execute();

            if ($result) {
                echo "<script>
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Pengguna berhasil dihapus.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Penghapusan pengguna gagal.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                </script>";
            }
        } catch (PDOException $e) {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan pada server: {$e->getMessage()}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'index.php';
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'ID pengguna tidak valid.',
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Pengguna</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        // Redirect back to index.php if accessed directly without POST
        if (!window.history.length || window.history.state === null) {
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>
