<?php
require '../../config/db.php';

if (!isset($_GET['id'])) {
    echo "ID booking tidak valid";
    exit();
}

$booking_id = $_GET['id'];

// Fetch detailed booking information
$query = "SELECT 
            b.id, 
            b.start_time, 
            b.end_time, 
            b.status, 
            b.purpose,
            b.agency,
            r.name AS room_name, 
            r.location AS room_location,
            r.capacity AS room_capacity,
            u.name AS user_name,
            u.nim AS user_nim,
            u.email AS user_email,
            u.img_prfl AS user_profile
          FROM bookings b
          JOIN rooms r ON b.room_id = r.id
          JOIN users u ON b.user_id = u.id
          WHERE b.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Booking tidak ditemukan";
    exit();
}

$booking = $result->fetch_assoc();
?>

<div class="space-y-4">
    <div class="flex items-center space-x-4 border-b pb-4">
        <img src="/uploads/profiles/<?php echo htmlspecialchars($booking['user_profile']); ?>" 
             alt="Foto Profil" 
             class="w-16 h-16 rounded-full object-cover">
        <div>
            <h3 class="text-lg font-bold text-green-800"><?php echo htmlspecialchars($booking['user_name']); ?></h3>
            <p class="text-gray-600"><?php echo htmlspecialchars($booking['user_nim']); ?></p>
            <p class="text-gray-600"><?php echo htmlspecialchars($booking['user_email']); ?></p>
        </div>
    </div>

    <div class="space-y-2">
        <div class="flex justify-between">
            <span class="font-medium text-green-700">Ruangan:</span>
            <span><?php echo htmlspecialchars($booking['room_name'] . ' (' . $booking['room_location'] . ')'); ?></span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-green-700">Kapasitas:</span>
            <span><?php echo htmlspecialchars($booking['room_capacity']); ?> orang</span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-green-700">Waktu:</span>
            <span>
                <?php 
                $start = new DateTime($booking['start_time']);
                $end = new DateTime($booking['end_time']);
                echo htmlspecialchars($start->format('d M Y H:i') . ' - ' . $end->format('H:i')); 
                ?>
            </span>
        </div>
        <div class="flex justify-between">
            <span class="font-medium text-green-700">Status:</span>
            <span class="
                <?php 
                switch($booking['status']) {
                    case 'pending': echo 'text-yellow-600'; break;
                    case 'approved': echo 'text-green-600'; break;
                    case 'rejected': echo 'text-red-600'; break;
                    case 'completed': echo 'text-blue-600'; break;
                }
                ?>
                font-bold uppercase">
                <?php echo htmlspecialchars($booking['status']); ?>
            </span>
        </div>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-medium text-green-700 mb-2">Tujuan Peminjaman:</h4>
        <p><?php echo htmlspecialchars($booking['purpose']); ?></p>
    </div>

    <div class="border-t pt-4">
        <h4 class="font-medium text-green-700 mb-2">Instansi/Organisasi:</h4>
        <p><?php echo htmlspecialchars($booking['agency']); ?></p>
    </div>
</div>