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
<script src="{{ $chart->cdn() }}"></script>
{{ $chart->script() }}
@endpush