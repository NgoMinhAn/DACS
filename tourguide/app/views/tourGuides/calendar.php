<!-- Guide Calendar Page -->
<div class="container py-4">
    <h2 class="mb-4">My Calendar</h2>
    <div id="guide-calendar"></div>
</div>

<!-- FullCalendar CSS & JS (CDN) -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('guide-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 650,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: <?php echo json_encode($calendar_events ?? []); ?>,
        eventColor: '#0d6efd',
        eventClick: function(info) {
            if (info.event.extendedProps.booking_url) {
                window.location.href = info.event.extendedProps.booking_url;
            }
        },
        selectable: true,
        select: function(info) {
            // Optionally, you can open a modal here to set availability for the selected date
            alert('Selected: ' + info.startStr + (info.endStr ? ' to ' + info.endStr : ''));
        }
    });
    calendar.render();
});
</script>

<style>
#guide-calendar {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    padding: 20px;
}
</style>