<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mahasiswa') {
    header("Location: login.php");
    exit();
}

require 'config/db.php';

// Check room availability
function checkRoomAvailability($conn, $room_id, $start_time, $end_time) {
    $check_query = "SELECT COUNT(*) as conflicts 
                    FROM bookings 
                    WHERE room_id = ? 
                    AND status IN ('pending', 'approved') 
                    AND (
                        (start_time <= ? AND end_time >= ?) OR
                        (start_time <= ? AND end_time >= ?) OR
                        (start_time >= ? AND end_time <= ?)
                    )";
    
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("issssss", 
        $room_id, 
        $start_time, $start_time,
        $end_time, $end_time,
        $start_time, $end_time
    );
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['conflicts'] == 0;
}

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $start_time = $_POST['start_date'] . ' ' . $_POST['start_time'] . ':00';
    $end_time = $_POST['start_date'] . ' ' . $_POST['end_time'] . ':00';
    $purpose = $_POST['purpose'];
    $agency = $_POST['agency'];
    $user_id = $_SESSION['user']['id'];

    // Validate inputs
    if (empty($room_id) || empty($start_time) || empty($end_time) || empty($purpose) || empty($agency)) {
        $error = "Semua field harus diisi!";
    } elseif (strtotime($start_time) >= strtotime($end_time)) {
        $error = "Waktu mulai harus sebelum waktu selesai!";
    } else {
        // Check room availability
        if (checkRoomAvailability($conn, $room_id, $start_time, $end_time)) {
            // Insert booking
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, start_time, end_time, purpose, agency) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissss", $user_id, $room_id, $start_time, $end_time, $purpose, $agency);
            
            if ($stmt->execute()) {
                // Log booking creation
                $booking_id = $conn->insert_id;
                $log_stmt = $conn->prepare("INSERT INTO booking_logs (booking_id, action, log_message) VALUES (?, 'created', 'Booking baru dibuat')");
                $log_stmt->bind_param("i", $booking_id);
                $log_stmt->execute();

                $success = "Booking berhasil diajukan!";
            } else {
                $error = "Gagal membuat booking!";
            }
        } else {
            $error = "Ruangan tidak tersedia pada waktu yang dipilih!";
        }
    }
}

// Fetch available rooms
$rooms_query = "SELECT * FROM rooms WHERE is_available = true";
$rooms_result = $conn->query($rooms_query);

// Fetch user's booking history
$bookings_query = "SELECT b.*, r.name as room_name 
                   FROM bookings b 
                   JOIN rooms r ON b.room_id = r.id 
                   WHERE b.user_id = ? 
                   ORDER BY b.created_at DESC";
$bookings_stmt = $conn->prepare($bookings_query);
$bookings_stmt->bind_param("i", $_SESSION['user']['id']);
$bookings_stmt->execute();
$bookings_result = $bookings_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link href="/assets/css/glass-style.css" rel="stylesheet">
</head>
<body class="bg-green-50">
<?php include 'components/navigationApp.php';  ?>

    <div class="container mx-auto px-4 py-8 lg:py-32 md:py-16">
        <h1 class="text-3xl font-bold text-green-800 mb-6">Booking Ruangan</h1>

        <!-- Room Cards -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <?php while($room = $rooms_result->fetch_assoc()): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition hover:scale-105">
                    <img src="uploads/rooms/<?php echo htmlspecialchars($room['img_room']); ?>" 
                         alt="<?php echo htmlspecialchars($room['name']); ?>" 
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-bold text-green-800 mb-2"><?php echo htmlspecialchars($room['name']); ?></h2>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($room['location']); ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                <i data-feather="users" class="w-4 h-4 inline-block mr-1"></i>
                                Kapasitas: <?php echo htmlspecialchars($room['capacity']); ?> orang
                            </span>
                            <div class="space-x-2">
                                <button onclick="showRoomDetail(<?php echo $room['id']; ?>)" 
                                        class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-600 transition">
                                    Preview
                                </button>
                                <button onclick="showBookingForm(<?php echo $room['id']; ?>)" 
                                        class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-600 transition">
                                    Booking
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Booking History -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-green-800 mb-6">Riwayat Booking</h2>
            
            <?php if($bookings_result->num_rows === 0): ?>
                <div class="text-center text-gray-500">
                    Belum ada riwayat booking
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-green-50 border-b border-green-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Ruangan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Tujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($booking = $bookings_result->fetch_assoc()): ?>
                                <tr class="hover:bg-green-50 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap"><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <?php 
                                        $start = new DateTime($booking['start_time']);
                                        echo htmlspecialchars($start->format('d M Y')); 
                                        ?>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <?php 
                                        $start = new DateTime($booking['start_time']);
                                        $end = new DateTime($booking['end_time']);
                                        echo htmlspecialchars($start->format('H:i') . ' - ' . $end->format('H:i')); 
                                        ?>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="
                                            <?php 
                                            switch($booking['status']) {
                                                case 'pending': echo 'bg-yellow-100 text-yellow-800';
                                                    break;
                                                case 'approved': echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'rejected': echo 'bg-red-100 text-red-800';
                                                    break;
                                                case 'completed': echo 'bg-blue-100 text-blue-800';
                                                    break;
                                            }
                                            ?> 
                                            px-2 py-1 rounded-full text-xs uppercase">
                                            <?php echo htmlspecialchars($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4"><?php echo htmlspecialchars($booking['purpose']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Room Detail Modal -->
    <div id="roomDetailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-green-800" id="roomDetailTitle"></h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div id="roomDetailContent"></div>
        </div>
    </div>

    <!-- Booking Form Modal -->
    <div id="bookingFormModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-green-800">Booking Ruangan</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form id="bookingForm" method="POST" class="space-y-4">
                <input type="hidden" name="room_id" id="bookingRoomId">
                <div>
                    <label class="block text-green-700 font-bold mb-2">Tanggal</label>
                    <input type="date" name="start_date" required class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-green-700 font-bold mb-2">Waktu Mulai</label>
                        <input type="time" name="start_time" required class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-green-700 font-bold mb-2">Waktu Selesai</label>
                        <input type="time" name="end_time" required class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div>
                    <label class="block text-green-700 font-bold mb-2">Tujuan Peminjaman</label>
                    <textarea name="purpose" required class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>
                <div>
                    <label class="block text-green-700 font-bold mb-2">Instansi/Organisasi</label>
                    <input type="text" name="agency" required class="w-full px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition">
                    Ajukan Booking
                </button>
            </form>
        </div>
    </div>

    <script>
        feather.replace();

        function showRoomDetail(roomId) {
            fetch(`room-detail.php?id=${roomId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('roomDetailContent').innerHTML = html;
                    document.getElementById('roomDetailModal').classList.remove('hidden');
                });
        }

        function showBookingForm(roomId) {
            document.getElementById('bookingRoomId').value = roomId;
            document.getElementById('bookingFormModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('roomDetailModal').classList.add('hidden');
            document.getElementById('bookingFormModal').classList.add('hidden');
        }

        <?php if(isset($error)): ?>
            document.addEventListener('DOMContentLoaded', () => {
                alert('<?php echo $error; ?>');
            });
        <?php endif; ?>

        <?php if(isset($success)): ?>
            document.addEventListener('DOMContentLoaded', () => {
                alert('<?php echo $success; ?>');
            });
        <?php endif;
        ?>
    </script>
</body>