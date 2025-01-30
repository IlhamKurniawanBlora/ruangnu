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
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/id.global.min.js"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 flex min-h-screen">
    <!-- Sidebar -->
    <?php include '../../components/sidebarAdmin.php'; ?>

    <!-- Main Dashboard -->
    <div class="flex-grow p-8 ml-72 mt-4 mr-4 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-6 bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent">Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- User Card -->
            <div class="bg-gradient-to-br from-green-100 to-emerald-50 p-6 rounded-lg shadow-md border border-green-100/50 flex items-center hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="p-3 rounded-full bg-gradient-to-br from-green-200 to-emerald-300 mr-4">
                    <i data-feather="users" class="text-green-700"></i>
                </div>
                <div>
                    <h3 class="text-green-700 text-sm font-medium">Total Pengguna</h3>
                    <p class="text-2xl font-bold bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent"><?php echo $totalUsers; ?></p>
                </div>
            </div>

            <!-- Room Card -->
            <div class="bg-gradient-to-br from-green-100 to-emerald-50 p-6 rounded-lg shadow-md border border-green-100/50 flex items-center hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="p-3 rounded-full bg-gradient-to-br from-green-200 to-emerald-300 mr-4">
                    <i data-feather="grid" class="text-green-700"></i>
                </div>
                <div>
                    <h3 class="text-green-700 text-sm font-medium">Total Ruangan</h3>
                    <p class="text-2xl font-bold bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent"><?php echo $totalRooms; ?></p>
                </div>
            </div>

            <!-- Pending Bookings Card -->
            <div class="bg-gradient-to-br from-green-100 to-emerald-50 p-6 rounded-lg shadow-md border border-green-100/50 flex items-center hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="p-3 rounded-full bg-gradient-to-br from-green-200 to-emerald-300 mr-4">
                    <i data-feather="clock" class="text-green-700"></i>
                </div>
                <div>
                    <h3 class="text-green-700 text-sm font-medium">Pending Booking</h3>
                    <p class="text-2xl font-bold bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent"><?php echo $pendingBookings; ?></p>
                </div>
            </div>

            <!-- Active Bookings Card -->
            <div class="bg-gradient-to-br from-green-100 to-emerald-50 p-6 rounded-lg shadow-md border border-green-100/50 flex items-center hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="p-3 rounded-full bg-gradient-to-br from-green-200 to-emerald-300 mr-4">
                    <i data-feather="calendar" class="text-green-700"></i>
                </div>
                <div>
                    <h3 class="text-green-700 text-sm font-medium">Booking Aktif</h3>
                    <p class="text-2xl font-bold bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent"><?php echo $activeBookings; ?></p>
                </div>
            </div>
        </div>

        <!-- Pending Bookings Section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4 bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent">Peminjaman Menunggu Persetujuan</h2>
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow-md border border-green-100/50 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-green-100 to-emerald-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-green-700">Peminjam</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-green-700">Ruangan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-green-700">Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-green-700">Tujuan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-green-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-green-100/50">
                        <?php
                        $pendingQuery = "SELECT b.*, u.name as user_name, r.name as room_name, u.nim, r.location, r.capacity 
                                       FROM bookings b 
                                       JOIN users u ON b.user_id = u.id 
                                       JOIN rooms r ON b.room_id = r.id 
                                       WHERE b.status = 'pending' 
                                       ORDER BY b.created_at DESC 
                                       LIMIT 5";
                        $pendingResult = $conn->query($pendingQuery);
                        
                        if ($pendingResult->num_rows > 0):
                            while ($booking = $pendingResult->fetch_assoc()):
                                $start = new DateTime($booking['start_time']);
                                $end = new DateTime($booking['end_time']);
                        ?>
                            <tr class="hover:bg-green-50">
                                <td class="px-4 py-3"><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                <td class="px-4 py-3"><?php echo $start->format('d M Y H:i'); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($booking['purpose']); ?></td>
                                <td class="px-4 py-3 space-x-2">
                                    <button 
                                        onclick="showBookingDetail(<?php echo htmlspecialchars(json_encode($booking)); ?>)"
                                        class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600">
                                        Detail
                                    </button>
                                    <button 
                                        onclick="updateBookingStatus(<?php echo $booking['id']; ?>, 'approved')"
                                        class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600">
                                        Setujui
                                    </button>
                                    <button 
                                        onclick="updateBookingStatus(<?php echo $booking['id']; ?>, 'rejected')"
                                        class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600">
                                        Tolak
                                    </button>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                    Tidak ada peminjaman yang menunggu persetujuan
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Approved Bookings Section -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4 bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent">Kalender Peminjaman</h2>
            <?php include 'kalender.php'; ?>
        </div>

        <!-- Booking Detail Modal -->
        <div id="bookingDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
            <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4 relative">
                <h3 class="text-xl font-semibold mb-4 bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent">Detail Peminjaman</h3>
                <div id="bookingDetailContent" class="space-y-3">
                    <!-- Content will be populated by JavaScript -->
                </div>
                <div class="mt-6 text-right">
                    <button onclick="closeBookingDetail()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <!-- Booking Chart -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-lg shadow-md border border-green-100/50 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <h3 class="text-lg font-semibold mb-4 bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent">Status Booking</h3>
                <canvas id="bookingChart"></canvas>
            </div>

            <!-- User Role Distribution -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-lg shadow-md border border-green-100/50 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <h3 class="text-lg font-semibold mb-4 bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent">Distribusi Peran Pengguna</h3>
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

        // Fungsi untuk mengupdate status booking
        function updateBookingStatus(bookingId, status) {
            if (!confirm('Apakah Anda yakin ingin ' + (status === 'approved' ? 'menyetujui' : 'menolak') + ' peminjaman ini?')) {
                return;
            }

            fetch('../booking/index.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update_status&booking_id=${bookingId}&new_status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Gagal memperbarui status peminjaman');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui status');
            });
        }

        function showBookingDetail(booking) {
            const modal = document.getElementById('bookingDetailModal');
            const content = document.getElementById('bookingDetailContent');
            
            // Debug log
            console.log('Received booking data:', booking);
            
            // Pastikan booking object ada
            if (!booking) {
                console.error('Booking data is undefined');
                return;
            }
            
            try {
                // Format waktu dengan penanganan error
                let startTime, endTime;
                try {
                    startTime = new Date(booking.start_time).toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    endTime = new Date(booking.end_time).toLocaleString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } catch (e) {
                    console.error('Error formatting dates:', e);
                    startTime = booking.start_time;
                    endTime = booking.end_time;
                }

                // Gunakan nullish coalescing untuk nilai default
                const userName = booking.user_name ?? 'Tidak tersedia';
                const nim = booking.nim ?? 'Tidak tersedia';
                const roomName = booking.room_name ?? 'Tidak tersedia';
                const location = booking.location ?? 'Tidak tersedia';
                const agency = booking.agency ?? 'Tidak tersedia';
                const purpose = booking.purpose ?? 'Tidak tersedia';
                const status = booking.status ?? 'Tidak tersedia';
                
                // Template dengan status dan styling yang lebih baik
                content.innerHTML = `
                    <div class="space-y-4">
                        <p class="flex justify-between items-center border-b pb-2">
                            <span class="font-medium text-gray-700">Status:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium ${
                                status === 'approved' ? 'bg-green-100 text-green-800' :
                                status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                status === 'rejected' ? 'bg-red-100 text-red-800' :
                                'bg-gray-100 text-gray-800'
                            }">
                                ${status.charAt(0).toUpperCase() + status.slice(1)}
                            </span>
                        </p>
                        <p class="flex justify-between items-center border-b pb-2">
                            <span class="font-medium text-gray-700">Peminjam:</span>
                            <span class="text-gray-900">${userName} ${nim !== 'Tidak tersedia' ? `(${nim})` : ''}</span>
                        </p>
                        <p class="flex justify-between items-center border-b pb-2">
                            <span class="font-medium text-gray-700">Ruangan:</span>
                            <span class="text-gray-900">${roomName}</span>
                        </p>
                        <p class="flex justify-between items-center border-b pb-2">
                            <span class="font-medium text-gray-700">Lokasi:</span>
                            <span class="text-gray-900">${location}</span>
                        </p>
                        <p class="flex justify-between items-center border-b pb-2">
                            <span class="font-medium text-gray-700">Waktu:</span>
                            <span class="text-gray-900">${startTime} - ${endTime}</span>
                        </p>
                        <p class="flex justify-between items-center border-b pb-2">
                            <span class="font-medium text-gray-700">Instansi:</span>
                            <span class="text-gray-900">${agency}</span>
                        </p>
                        <div class="border-b pb-2">
                            <p class="font-medium text-gray-700 mb-1">Tujuan:</p>
                            <p class="text-gray-900">${purpose}</p>
                        </div>
                    </div>
                    ${status === 'pending' ? `
                        <div class="flex justify-end gap-2 mt-4">
                            <button 
                                onclick="updateBookingStatus(${booking.id}, 'approved')"
                                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors">
                                Setujui
                            </button>
                            <button 
                                onclick="updateBookingStatus(${booking.id}, 'rejected')"
                                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors">
                                Tolak
                            </button>
                        </div>
                    ` : ''}
                `;
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
            } catch (error) {
                console.error('Error showing booking detail:', error);
                alert('Terjadi kesalahan saat menampilkan detail peminjaman');
            }
        }

        function closeBookingDetail() {
            const modal = document.getElementById('bookingDetailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('bookingDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingDetail();
            }
        });

        // Tambahkan CSS untuk scrollbar yang lebih baik
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                /* Styling untuk scrollbar */
                .overflow-y-auto::-webkit-scrollbar {
                    width: 4px;
                }
                
                .overflow-y-auto::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 2px;
                }
                
                .overflow-y-auto::-webkit-scrollbar-thumb {
                    background: #10B981;
                    border-radius: 2px;
                }
                
                .overflow-y-auto::-webkit-scrollbar-thumb:hover {
                    background: #059669;
                }
            </style>
        `);

        // Initialize FullCalendar
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'id',
                events: <?php echo json_encode($events); ?>,
                eventClick: function(info) {
                    const booking = info.event.extendedProps.booking;
                    console.log('Event clicked:', info.event);
                    console.log('Booking data:', booking);
                    if (booking) {
                        showBookingDetail(booking);
                    } else {
                        console.error('No booking data found in event');
                    }
                },
                eventContent: function(arg) {
                    let timeText = '';
                    if (arg.event.allDay) {
                        timeText = 'Sepanjang hari';
                    } else {
                        const start = new Date(arg.event.start);
                        timeText = start.toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        });
                    }

                    return {
                        html: `
                            <div class="fc-content p-1.5 rounded-md bg-gradient-to-r ${
                                arg.event.backgroundColor === '#10B981' 
                                ? 'from-green-500 to-green-600' 
                                : 'from-yellow-400 to-yellow-500'
                            } shadow-sm">
                                <div class="text-xs font-medium text-white">${timeText}</div>
                                <div class="text-xs font-medium text-white truncate">${arg.event.title}</div>
                            </div>
                        `
                    };
                },
                eventDidMount: function(info) {
                    info.el.setAttribute('data-tooltip', info.event.title);
                },
                dayMaxEvents: true,
                height: 800,
                expandRows: true,
                slotMinTime: '07:00:00',
                slotMaxTime: '20:00:00',
                allDaySlot: false,
                slotDuration: '00:30:00',
                slotLabelInterval: '01:00',
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari'
                },
                views: {
                    timeGrid: {
                        dayMaxEvents: 4,
                        dayMaxEventRows: 4,
                    }
                },
                nowIndicator: true,
                navLinks: true,
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5],
                    startTime: '07:00',
                    endTime: '20:00',
                }
            });
            calendar.render();
        });

        // Update CSS untuk styling FullCalendar
        document.head.insertAdjacentHTML('beforeend', `
            <style>
                .fc-custom-container {
                    position: relative;
                    z-index: 1;
                }
                
                .fc-popover {
                    z-index: 100 !important;
                }
                
                .fc-view-harness {
                    z-index: 0;
                }
                
                #bookingDetailModal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    z-index: 9999;
                }
                
                #bookingDetailModal .bg-white {
                    max-height: 90vh;
                    overflow-y: auto;
                }
                
                /* Tambahkan styling untuk scrollbar modal */
                #bookingDetailModal .bg-white::-webkit-scrollbar {
                    width: 4px;
                }
                
                #bookingDetailModal .bg-white::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 2px;
                }
                
                #bookingDetailModal .bg-white::-webkit-scrollbar-thumb {
                    background: #10B981;
                    border-radius: 2px;
                }
                
                #bookingDetailModal .bg-white::-webkit-scrollbar-thumb:hover {
                    background: #059669;
                }
            </style>
        `);
    </script>
</body>
</html>