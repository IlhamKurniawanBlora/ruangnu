<?php
$bookingsQuery = "SELECT b.*, u.name as user_name, r.name as room_name, u.nim, r.location 
                 FROM bookings b 
                 JOIN users u ON b.user_id = u.id 
                 JOIN rooms r ON b.room_id = r.id 
                 WHERE b.status IN ('approved', 'pending')
                 ORDER BY b.start_time ASC";
$bookingsResult = $conn->query($bookingsQuery);

$events = [];
while ($booking = $bookingsResult->fetch_assoc()) {
    $color = $booking['status'] === 'approved' ? '#10B981' : '#F59E0B';
    $bookingData = [
        'id' => $booking['id'],
        'user_name' => $booking['user_name'],
        'nim' => $booking['nim'],
        'room_name' => $booking['room_name'],
        'location' => $booking['location'],
        'start_time' => $booking['start_time'],
        'end_time' => $booking['end_time'],
        'purpose' => $booking['purpose'],
        'agency' => $booking['agency'],
        'status' => $booking['status']
    ];
    
    $events[] = [
        'id' => $booking['id'],
        'title' => $booking['room_name'] . ' - ' . $booking['user_name'],
        'start' => $booking['start_time'],
        'end' => $booking['end_time'],
        'backgroundColor' => $color,
        'borderColor' => $color,
        'extendedProps' => [
            'booking' => $bookingData
        ]
    ];
}
?>

<div class="mt-8">
    <h2 class="text-xl font-semibold mb-4 bg-gradient-to-r from-green-800 to-emerald-600 bg-clip-text text-transparent">Kalender Peminjaman</h2>
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow-md border border-green-100/50 p-6">
        <div class="flex gap-4 mb-4">
            <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-gradient-to-r from-green-500 to-green-600 mr-2"></div>
                <span class="text-sm font-medium text-gray-700">Disetujui</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-gradient-to-r from-yellow-400 to-yellow-500 mr-2"></div>
                <span class="text-sm font-medium text-gray-700">Pending</span>
            </div>
        </div>
        <div id="calendar" class="fc-custom-container"></div>
    </div>
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

<script>
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

// Update bagian styling FullCalendar
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .fc-custom-container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .fc-header-toolbar {
            padding: 0.5rem 0 1.5rem 0;
            margin-bottom: 0 !important;
        }

        .fc-toolbar-title {
            color: #065f46 !important;
            font-weight: 700 !important;
            font-size: 1.5rem !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Glassmorphism Buttons */
        .fc-button {
            background: rgba(255, 255, 255, 0.25) !important;
            backdrop-filter: blur(4px) !important;
            border: 1px solid rgba(255, 255, 255, 0.18) !important;
            padding: 0.6rem 1.2rem !important;
            font-weight: 500 !important;
            border-radius: 0.5rem !important;
            color: #065f46 !important;
            transition: all 0.3s ease !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
        }
        
        .fc-button:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(5, 150, 105, 0.9)) !important;
            transform: translateY(-2px);
            color: white !important;
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.2) !important;
        }
        
        .fc-button-active {
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.9), rgba(4, 120, 87, 0.9)) !important;
            color: white !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        /* Calendar Body */
        .fc-view {
            background: rgba(255, 255, 255, 0.5) !important;
            backdrop-filter: blur(4px);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .fc-day {
            background: rgba(255, 255, 255, 0.3) !important;
            backdrop-filter: blur(4px);
            transition: all 0.3s ease !important;
        }

        .fc-day:hover {
            background: rgba(240, 253, 244, 0.5) !important;
        }

        .fc-day-today {
            background: rgba(236, 253, 245, 0.6) !important;
        }

        /* Event Styling */
        .fc-event {
            backdrop-filter: blur(4px);
            border: none !important;
            margin: 2px !important;
            transition: all 0.3s ease !important;
        }

        .fc-event:hover {
            transform: translateY(-1px) scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header and Navigation */
        .fc th {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(4px);
            color: #065f46 !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            font-size: 0.75rem !important;
            letter-spacing: 0.05em;
            padding: 1rem 0 !important;
        }

        /* More Link */
        .fc-more-link {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(5, 150, 105, 0.9)) !important;
            color: white !important;
            padding: 2px 8px !important;
            border-radius: 9999px !important;
            backdrop-filter: blur(4px);
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Popover */
        .fc-popover {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px) !important;
            border: 1px solid rgba(255, 255, 255, 0.18) !important;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15) !important;
            border-radius: 0.5rem !important;
        }

        .fc-popover-header {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(5, 150, 105, 0.9)) !important;
            color: white !important;
            padding: 0.75rem !important;
            font-weight: 600 !important;
        }

        /* Modal */
        #bookingDetailModal {
            backdrop-filter: blur(8px);
        }

        #bookingDetailModal .bg-white {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
        }

        /* Custom Scrollbar */
        .fc-scroller::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .fc-scroller::-webkit-scrollbar-track {
            background: rgba(243, 244, 246, 0.5);
        }

        .fc-scroller::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #10B981, #059669);
            border-radius: 3px;
        }

        .fc-scroller::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }
    </style>
`);
</script> 