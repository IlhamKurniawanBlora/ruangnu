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
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .fc-header-toolbar {
            padding: 0.5rem 0 1.5rem 0;
            margin-bottom: 0 !important;
        }

        .fc-toolbar-title {
            color: #065f46 !important;
            font-weight: 700 !important;
            font-size: 1.5rem !important;
        }

        .fc-button {
            padding: 0.5rem 1rem !important;
            font-weight: 500 !important;
            border-radius: 0.5rem !important;
            transition: all 0.2s ease !important;
        }

        .fc-button-primary {
            background: linear-gradient(135deg, #10B981, #059669) !important;
            border: none !important;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2) !important;
        }
        
        .fc-button-primary:hover {
            background: linear-gradient(135deg, #059669, #047857) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3) !important;
        }
        
        .fc-button-active {
            background: linear-gradient(135deg, #047857, #065f46) !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        .fc-day {
            background: white !important;
            transition: background-color 0.2s ease !important;
        }

        .fc-day:hover {
            background: #f0fdf4 !important;
        }

        .fc-day-today {
            background: #ecfdf5 !important;
        }

        .fc-day-today .fc-daygrid-day-number {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white !important;
            padding: 4px 12px !important;
            border-radius: 9999px;
            margin: 8px;
            font-weight: 600;
        }

        .fc-daygrid-day-number {
            color: #1f2937 !important;
            padding: 8px !important;
            font-weight: 500;
            font-size: 0.95rem !important;
        }
        
        .fc-day-sat, .fc-day-sun {
            background: #f8fafc !important;
        }

        .fc-day-past {
            background: #ffffff !important;
        }

        .fc th {
            padding: 1rem 0 !important;
            background: #ffffff !important;
            color: #4b5563 !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            font-size: 0.75rem !important;
            letter-spacing: 0.05em;
        }

        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #f3f4f6 !important;
        }

        .fc-scrollgrid {
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid #e5e7eb !important;
        }

        .fc-view-harness {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .fc-event {
            border: none !important;
            margin: 2px !important;
            padding: 2px !important;
        }

        .fc-daygrid-event {
            border-radius: 0.5rem !important;
            padding: 4px 8px !important;
            font-size: 0.875rem !important;
        }

        .fc-event-main {
            padding: 2px 4px !important;
        }

        .fc-event-time {
            font-size: 0.75rem !important;
            font-weight: 500 !important;
        }

        .fc-event-title {
            font-weight: 500 !important;
        }

        .fc-popover {
            border-radius: 0.5rem !important;
            border: none !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }

        .fc-popover-header {
            background: linear-gradient(135deg, #10B981, #059669) !important;
            color: white !important;
            padding: 0.75rem !important;
            font-weight: 600 !important;
            border-top-left-radius: 0.5rem !important;
            border-top-right-radius: 0.5rem !important;
        }

        .fc-popover-body {
            padding: 0.75rem !important;
        }

        .fc-more-link {
            background: linear-gradient(135deg, #10B981, #059669) !important;
            color: white !important;
            padding: 2px 8px !important;
            border-radius: 9999px !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
        }

        .fc-timegrid-slot {
            height: 45px !important;
        }

        .fc-timegrid-axis {
            padding: 1rem !important;
            font-weight: 500 !important;
            color: #4b5563 !important;
        }

        .fc-timegrid-now-indicator-line {
            border-color: #10B981 !important;
            border-width: 2px !important;
        }

        .fc-timegrid-now-indicator-arrow {
            border-color: #10B981 !important;
            border-width: 5px !important;
        }

        /* Hover effects */
        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        /* Modal styling tetap sama dengan penyesuaian */
        #bookingDetailModal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            backdrop-filter: blur(8px);
        }
        
        #bookingDetailModal .bg-white {
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
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