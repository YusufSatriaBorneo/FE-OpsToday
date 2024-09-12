@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        function fetchAbsensiData() {
            $.ajax({
                url: "{{ route('absensi.dashboard') }}",
                method: "GET",
                success: function(data) {
                    $('#absensi-table-body1').html(data);
                }
            });
        }
        function refreshPage() {
            console.log('Refreshing page...');
            location.reload();
        }
        setInterval(refreshPage, 30000);
        fetchAbsensiData();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = new bootstrap.Carousel(document.getElementById('engineerTaskSlider'), {
            interval: 10000,
            wrap: true
        });
    });
</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
        refreshEngineerOnLeave();
    });
    
    function refreshEngineerOnLeave() {
        fetch('/api/engineer-leaves')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('engineer-leave-content');
                tbody.innerHTML = ''; // Kosongkan konten sebelum menambahkan data baru
                
                data.forEach(leave => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${leave.engineer_name}</td>
                        <td>${leave.start_date} - ${leave.end_date}</td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error('Error refreshing Engineer On Leave:', error));
    }
    setInterval(refreshEngineerOnLeave, 3000); // Refresh setiap 30 detik
</script>
<script src="{{ $chart->cdn() }}"></script>
{{ $chart->script() }}
@endpush