<?php
require 'config/db.php';

if (!isset($_GET['id'])) {
    echo "ID ruangan tidak valid";
    exit();
}

$room_id = $_GET['id'];

// Fetch room details
$query = "SELECT 
            r.id, 
            r.name, 
            r.location, 
            r.capacity, 
            r.is_available, 
            b.start_time AS last_booking_start, 
            b.end_time AS last_booking_end, 
            b.user_id AS last_booking_user_id, 
            u.name AS last_booking_user_name, 
            u.nim AS last_booking_user_nim
          FROM rooms r
          LEFT JOIN bookings b ON r.id = b.room_id AND b.id = (
              SELECT MAX(id) FROM bookings WHERE room_id = r.id
          )
          LEFT JOIN users u ON b.user_id = u.id
          WHERE r.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Ruangan tidak ditemukan";
    exit();
}

$room = $result->fetch_assoc();
?>

<div class="space-y-4">
    <!-- Room Details -->
    <div class="border-b pb-4">
        <h3 class="text-xl font-bold text-green-800"><?php echo htmlspecialchars($room['name']); ?></h3>
        <p class="text-gray-600"><?php echo htmlspecialchars($room['location']); ?></p>
    </div>

    <div class="space-y-2">
        <div class="flex justify-between">
            <span class="font-medium text-green-700">Kapasitas:</span>
            <span><?php echo htmlspecialchars($room['capacity']); ?> orang</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-green-700">Status:</span>
            <span class="
                <?php 
                switch($room['is_available']) {
                    case 'available': echo 'text-green-600'; break;
                    case 'occupied': echo 'text-red-600'; break;
                    case 'maintenance': echo 'text-yellow-600'; break;
                }
                ?>
                font-bold uppercase">
                <?php echo htmlspecialchars($room['is_available']); ?>
            </span>
        </div>
    </div>

    <!-- Last Booking Details -->
    <?php if ($room['last_booking_user_id']): ?>
        <div class="border-t pt-4">
            <h4 class="font-medium text-green-700 mb-2">Peminjaman Terakhir:</h4>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium text-green-700">Pengguna:</span>
                    <span><?php echo htmlspecialchars($room['last_booking_user_name'] . ' (' . $room['last_booking_user_nim'] . ')'); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-green-700">Waktu:</span>
                    <span>
                        <?php 
                        $start = new DateTime($room['last_booking_start']);
                        $end = new DateTime($room['last_booking_end']);
                        echo htmlspecialchars($start->format('d M Y H:i') . ' - ' . $end->format('H:i')); 
                        ?>
                    </span>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="border-t pt-4">
            <h4 class="font-medium text-green-700 mb-2">Peminjaman Terakhir:</h4>
            <p class="text-gray-500">Belum ada peminjaman untuk ruangan ini.</p>
        </div>
    <?php endif; ?>
</div>
