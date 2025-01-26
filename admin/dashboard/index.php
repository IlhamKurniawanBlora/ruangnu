<?php
session_start();

// Authentication Check
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit();
}

$user = $_SESSION['user'];
require '../../config/db.php';

// Dashboard Metrics Queries
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalRooms = $conn->query("SELECT COUNT(*) as count FROM rooms")->fetch_assoc()['count'];
$pendingBookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'")->fetch_assoc()['count'];
$activeBookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'approved'")->fetch_assoc()['count'];
$completedBookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'completed'")->fetch_assoc()['count'];
$rejectedBookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'rejected'")->fetch_assoc()['count'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RuangNU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="bg-green-50 flex min-h-screen">
    <!-- Sidebar -->
    <?php include '../../components/sidebarAdmin.php'; ?>

    <!-- Main Dashboard -->
    <div class="flex-grow p-8 ml-72 mt-4 mr-4 bg-white rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-6 text-green-800">Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- User Card -->
            <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-100 flex items-center hover:shadow-xl transition-all">
                <div class="p-3 rounded-full bg-green-200 mr-4">
                    <i data-feather="users" class="text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-green-600 text-sm">Total Pengguna</h3>
                    <p class="text-2xl font-bold text-green-800"><?php echo $totalUsers; ?></p>
                </div>
            </div>

            <!-- Room Card -->
            <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-100 flex items-center hover:shadow-xl transition-all">
                <div class="p-3 rounded-full bg-green-200 mr-4">
                    <i data-feather="grid" class="text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-green-600 text-sm">Total Ruangan</h3>
                    <p class="text-2xl font-bold text-green-800"><?php echo $totalRooms; ?></p>
                </div>
            </div>

            <!-- Pending Bookings Card -->
            <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-100 flex items-center hover:shadow-xl transition-all">
                <div class="p-3 rounded-full bg-green-200 mr-4">
                    <i data-feather="clock" class="text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-green-600 text-sm">Pending Booking</h3>
                    <p class="text-2xl font-bold text-green-800"><?php echo $pendingBookings; ?></p>
                </div>
            </div>

            <!-- Active Bookings Card -->
            <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-100 flex items-center hover:shadow-xl transition-all">
                <div class="p-3 rounded-full bg-green-200 mr-4">
                    <i data-feather="calendar" class="text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-green-600 text-sm">Booking Aktif</h3>
                    <p class="text-2xl font-bold text-green-800"><?php echo $activeBookings; ?></p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Booking Chart -->
            <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-100 hover:shadow-xl transition-all">
                <h3 class="text-lg font-semibold mb-4 text-green-800">Status Booking</h3>
                <canvas id="bookingChart"></canvas>
            </div>

            <!-- User Role Distribution -->
            <div class="bg-green-50 p-6 rounded-lg shadow-md border border-green-100 hover:shadow-xl transition-all">
                <h3 class="text-lg font-semibold mb-4 text-green-800">Distribusi Peran Pengguna</h3>
                <canvas id="userRoleChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // Booking Status Chart
        // Booking Status Chart
        new Chart(document.getElementById('bookingChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Completed', 'Rejected'],
                datasets: [{
                    data: [
                        <?php echo $pendingBookings; ?>,
                        <?php echo $activeBookings; ?>,
                        <?php echo $completedBookings; ?>,
                        <?php echo $rejectedBookings; ?>
                    ],
                    backgroundColor: ['#F59E0B', '#10B981', '#3B82F6', '#EF4444']
                }]
            }
        });

        // User Role Chart
        new Chart(document.getElementById('userRoleChart'), {
            type: 'doughnut',
            data: {
                labels: ['Mahasiswa', 'Admin', 'Superadmin'],
                datasets: [{
                    data: [
                        <?php 
                        $mahasiswaCount = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'mahasiswa'")->fetch_assoc()['count'];
                        $adminCount = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'")->fetch_assoc()['count'];
                        $superadminCount = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'superadmin'")->fetch_assoc()['count'];
                        echo "$mahasiswaCount, $adminCount, $superadminCount";
                        ?>
                    ],
                    backgroundColor: ['#6366F1', '#10B981', '#EF4444']
                }]
            }
        });
    </script>
</body>
</html>