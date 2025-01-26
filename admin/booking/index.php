<?php
session_start();

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'superadmin')) {
    header("Location: ../../login.php");
    exit();
}

require '../../config/db.php';

// Handle async status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['new_status'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update booking status
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $booking_id);
        $stmt->execute();

        // Log the status change
        $log_stmt = $conn->prepare("INSERT INTO booking_logs (booking_id, action, log_message) VALUES (?, ?, ?)");
        $log_message = "Status berubah menjadi " . $new_status;
        $log_action = $new_status === 'approved' ? 'approved' : 
                     ($new_status === 'rejected' ? 'rejected' : 'updated');
        $log_stmt->bind_param("iss", $booking_id, $log_action, $log_message);
        $log_stmt->execute();

        // Commit transaction
        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit();
}

// Fetch bookings with detailed information
$query = "SELECT 
            b.id, 
            b.start_time, 
            b.end_time, 
            b.status, 
            b.purpose,
            b.agency,
            r.name AS room_name, 
            r.location AS room_location,
            u.name AS user_name,
            u.nim AS user_nim,
            u.email AS user_email
          FROM bookings b
          JOIN rooms r ON b.room_id = r.id
          JOIN users u ON b.user_id = u.id
          ORDER BY b.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
<body class="flex bg-green-50">
    <?php include '../../components/sidebarAdmin.php'; ?>

    <main class="flex-grow p-6 ml-72">
        <div class="bg-white rounded-lg shadow-lg border border-green-100 p-8">
            <h1 class="text-3xl font-bold text-green-800 mb-6">Manajemen Booking</h1>

            <?php if ($result->num_rows === 0): ?>
                <div class="text-center py-8 text-gray-500">
                    Tidak ada booking saat ini
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-green-50 border-b border-green-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Ruangan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Peminjam</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-green-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-green-100">
                            <?php while ($booking = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-green-50 transition-colors" data-booking-id="<?php echo $booking['id']; ?>">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <?php 
                                        $start = new DateTime($booking['start_time']);
                                        $end = new DateTime($booking['end_time']);
                                        echo htmlspecialchars($start->format('d M Y H:i') . ' - ' . $end->format('H:i')); 
                                        ?>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($booking['room_name'] . ' (' . $booking['room_location'] . ')'); ?>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap"><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <select class="booking-status-select 
                                            <?php 
                                            switch($booking['status']) {
                                                case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'approved': echo 'bg-green-100 text-green-800'; break;
                                                case 'rejected': echo 'bg-red-100 text-red-800'; break;
                                                case 'completed': echo 'bg-blue-100 text-blue-800'; break;
                                            }
                                            ?> 
                                            px-2 py-1 rounded-full text-xs font-medium">
                                            <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="approved" <?php echo $booking['status'] == 'approved' ? 'selected' : ''; ?>>Disetujui</option>
                                            <option value="rejected" <?php echo $booking['status'] == 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
                                            <option value="completed" <?php echo $booking['status'] == 'completed' ? 'selected' : ''; ?>>Selesai</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <a href="#" class="view-booking text-green-600 hover:text-green-900 mr-3" 
                                           data-booking-id="<?php echo $booking['id']; ?>">
                                            <i data-feather="eye" class="w-4 h-4 inline-block"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal for Booking Details -->
    <div id="bookingDetailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-green-800">Detail Booking</h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div id="bookingDetailContent" class="space-y-4">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        // Status update handler
        document.querySelectorAll('.booking-status-select').forEach(select => {
            select.addEventListener('change', function() {
                const bookingId = this.closest('tr').dataset.bookingId;
                const newStatus = this.value;

                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=update_status&booking_id=${bookingId}&new_status=${newStatus}`
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Gagal memperbarui status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
            });
        });

        // Booking detail modal
        document.querySelectorAll('.view-booking').forEach(viewBtn => {
            viewBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const bookingId = this.dataset.bookingId;
                const modal = document.getElementById('bookingDetailModal');
                const content = document.getElementById('bookingDetailContent');

                // Fetch booking details via AJAX (you'll need to create a detail fetch endpoint)
                fetch(`detail.php?id=${bookingId}`)
                    .then(response => response.text())
                    .then(html => {
                        content.innerHTML = html;
                        modal.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat detail booking');
                    });
            });
        });

        // Close modal
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('bookingDetailModal').classList.add('hidden');
        });
    </script>
</body>
</html>