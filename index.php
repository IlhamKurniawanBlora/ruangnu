<?php
    
    session_start();
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        require 'config/db.php';
        
        // Fetch complete user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $profile = $result->fetch_assoc();
    } else {
        $_SESSION['user'] = null;
    };
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RuangNU - Sistem Peminjaman Ruang</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/scrollmagic@2.0.8/scrollmagic/uncompressed/ScrollMagic.min.js"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation from navigationApp.php is already included -->
    <?php include 'components/navigationApp.php';  ?>
    
    <!-- Hero Section -->
    <?php include 'components/heroSection.php'; ?>
    
    <!-- About Section -->
    <?php include 'components/aboutSection.php'; ?>
    
    <!-- Features Section -->
    <?php include 'components/featureSection.php'; ?>
    
    <!-- Profile Section -->
    <?php include 'components/profile-dev.php'; ?>

    <!-- Contact Section -->
    <?php include 'components/contactSection.php'; ?>
    
    <!-- Footer -->
    <?php include 'components/footerApp.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const controller = new ScrollMagic.Controller();

            const sections = document.querySelectorAll('section');
            sections.forEach((section) => {
                new ScrollMagic.Scene({
                    triggerElement: section,
                    triggerHook: 0.7,
                    reverse: false
                })
                .setClassToggle(section, 'fadeInUp')
                .addTo(controller);
            });
        });
    </script>
</body>
</html>