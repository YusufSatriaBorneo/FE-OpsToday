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
        // function refreshPage() {
        //     console.log('Refreshing page...');
        //     location.reload();
        // }
        // setInterval(refreshPage, 30000);
        // fetchAbsensiData();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = new bootstrap.Carousel(document.getElementById('engineerTaskSlider'), {
            interval: 20000,
            wrap: true
        });
    });
</script>

<!-- Fungsi refresh Engineer On Leave -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        refreshEngineerOnLeave();
        updateEngineerOnLeaveCount();
    });
    let engineerLeaves = [];

    function refreshEngineerOnLeave() {
        fetch('/api/engineer-leaves')
            .then(response => response.json())
            .then(data => {
                engineerLeaves = data;
                const tbody = document.getElementById('engineer-leave-content');
                tbody.innerHTML = ''; // Kosongkan konten sebelum menambahkan data baru

                if (data.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td colspan="2" class="text-center">No engineer on leave</td>
                    `;
                    tbody.appendChild(row);
                } else {
                    data.forEach(leave => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${leave.engineer_name}</td>
                            <td>${formatDate(leave.start_date)} - ${formatDate(leave.end_date)}</td>
                        `;
                        tbody.appendChild(row);
                    });
                }
            })
            .catch(error => console.error('Error refreshing Engineer On Leave:', error));
    }
    setInterval(refreshEngineerOnLeave, 43200000); // Refresh setiap 3 detik

    function updateEngineerOnLeaveCount() {
        fetch('/api/engineer-leaves')
            .then(response => response.json())
            .then(data => {
                const count = data.length;
                document.getElementById('status-on-leave').textContent = count;
            })
            .catch(error => console.error('Error updating engineer on leave count:', error));
    }
    setInterval(updateEngineerOnLeaveCount, 600000); // Refresh setiap 3 detik
</script>

<!-- Fungsi refresh Absensi -->
<script>
    function formatTimeToIndonesia(time) {
        if (!time) return ''; // Jika waktu tidak ada, kembalikan string kosong

        // Mengubah waktu dari UTC ke UTC+7 (Asia/Jakarta)
        const utcDate = new Date(time); // Waktu dari API (dalam UTC)

        // Menambahkan offset +7 jam secara manual
        utcDate.setHours(utcDate.getHours() - 8);

        // Format waktu ke string sesuai zona waktu Jakarta
        const options = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false // Menggunakan format 24 jam
        };

        return utcDate.toLocaleTimeString('id-ID', options); // Format dengan `toLocaleTimeString`
    }
    document.addEventListener('DOMContentLoaded', function() {
        refreshAbsensiData();
    });

    function refreshAbsensiData() {
        fetch('/api/absensi')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('absensi-card-container');
                const noDataMessage = document.getElementById('no-data-message');
                container.innerHTML = ''; // Kosongkan konten sebelum menambahkan data baru

                // Asumsikan data.data jika struktur JSON dari API berbentuk { data: [...] }
                const absensiData = data.data || data; // Pastikan strukturnya sesuai dengan respons API Anda

                if (Array.isArray(absensiData) && absensiData.length > 0) {
                    if (noDataMessage) {
                        noDataMessage.style.display = 'none';
                    }
                    absensiData.forEach(item => {
                        const card = document.createElement('div');
                        card.className = 'col-12 col-md-1-7';
                        card.innerHTML = `
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-sm me-3">
                                            <img src="../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" loading="lazy" />
                                        </div>
                                        <small class="text-truncate"><a href="https://teams.microsoft.com/l/chat/0/0?users=Yusuf.Borneo@kpc.co.id" target="_blank">${item.fsName || 'N/A'}</a></small>
                                    </div>
                                                                    <p class="card-text mb-2">
                                    ${item.status1 === 'Absen' ? 
                                        '<span class="text-muted">Not Available</span>' : 
                                        '<span class="text-muted">' + formatTimeToIndonesia(item.CI) + '</span>'}
                                </p>
                                    ${getBadge(item)}
                                </div>
                            </div>
                        `;
                        container.appendChild(card);
                    });
                } else {
                    if (noDataMessage) {
                        noDataMessage.style.display = 'block';
                    }
                }
            })
            .catch(error => console.error('Error refreshing absensi data:', error));
    }

    function getBadge(item) {
        let badge = '';

        // Cek apakah engineer sedang cuti
        const isOnLeave = item.isOnLeave;
        // Mengecek apakah isOnProgress ada dan bernilai true atau 1
        if (isOnLeave) {
            badge = '<span class="badge bg-label-secondary rounded-pill">Cuti</span>';
        } else if (item.isOnProgress) {
            badge = '<span class="badge bg-label-info rounded-pill">On Remote</span>';
        } else if (item.status1 === 'Hadir') {
            badge = '<span class="badge bg-label-success rounded-pill">Available</span>';
        } else if (item.status1 === 'Keluar') {
            badge = '<span class="badge bg-label-warning rounded-pill">Out of Office</span>';
        } else {
            badge = '<span class="badge bg-label-danger rounded-pill">Not Available</span>';
        }
        return badge;
    }

    // Refresh setiap 30 detik, sesuaikan dengan kebutuhan Anda
    setInterval(refreshAbsensiData, 60000);

    function formatDate(dateString) {
        const [year, month, day] = dateString.split('-');
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
        return `${day} ${monthNames[parseInt(month) - 1]} ${year}`;
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchEngineerTasks();
        setInterval(fetchEngineerTasks, 60000);
    });

    let engineerTasksChart; // Variabel untuk menyimpan instance chart

    function fetchEngineerTasks() {
        fetch('/api/engineer-tasks')
            .then(response => response.json())
            .then(data => {
                // Urutkan data berdasarkan task_count secara menurun
                data.sort((a, b) => a.task_count - b.task_count);

                const labels = data.map(task => task.engineer_name);
                const notStartedTasks = data.map(task => task.task_count <= 10 ? task.task_count : 0);
                const maxTaskLimitReached = data.map(task => task.task_count > 10 ? task.task_count : 0);

                const ctx = document.getElementById('engineerTasksChart').getContext('2d');

                // Jika chart sudah ada, hancurkan instance sebelumnya
                if (engineerTasksChart) {
                    engineerTasksChart.data.labels = labels;
                    engineerTasksChart.data.datasets[0].data = notStartedTasks;
                    engineerTasksChart.data.datasets[1].data = maxTaskLimitReached;
                    engineerTasksChart.update();
                } else {
                    // Jika chart belum ada, buat chart baru
                    engineerTasksChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: 'Not Started Tasks',
                                    data: notStartedTasks,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Max Task Limit Reached',
                                    data: maxTaskLimitReached,
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                datalabels: {
                                    anchor: 'end', // Menempatkan label di akhir bar
                                    align: 'top', // Menyelaraskan label di bagian atas bar
                                    formatter: (value) => value > 0 ? value : '', // Menampilkan nilai yang lebih besar dari 0
                                    color: 'black', // Warna teks label
                                    font: {
                                        weight: 'bold' // Membuat teks lebih tebal
                                    }
                                }
                            }
                        },
                        plugins: [ChartDataLabels] // Tambahkan plugin `ChartDataLabels` ke chart
                    });
                }
            })
            .catch(error => console.error('Error fetching engineer tasks:', error));
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchStatusCounts();
        setInterval(fetchStatusCounts, 60000); // Refresh setiap 30 detik
    });

    function fetchStatusCounts() {
        fetch('/api/status-count')
            .then(response => response.json())
            .then(data => {
                document.getElementById('status-hadir').innerText = data.Hadir;
                document.getElementById('status-keluar').innerText = data.Keluar;
                document.getElementById('status-absen').innerText = data.Absen;
            })
            .catch(error => console.error('Error fetching status counts:', error));
    }
</script>
<script>
    function updateClock() {
        const now = new Date();
        const gmtPlus8 = new Date(now.getTime() + (8 * 60 * 60 * 1000));

        const hours = String(gmtPlus8.getUTCHours()).padStart(2, '0');
        const minutes = String(gmtPlus8.getUTCMinutes()).padStart(2, '0');
        const seconds = String(gmtPlus8.getUTCSeconds()).padStart(2, '0');

        const day = String(gmtPlus8.getUTCDate()).padStart(2, '0');
        const month = String(gmtPlus8.getUTCMonth() + 1).padStart(2, '0'); // Months are zero-based
        const year = gmtPlus8.getUTCFullYear();

        const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        const dayOfWeek = daysOfWeek[gmtPlus8.getUTCDay()];

        const timeString = `${hours}:${minutes}:${seconds}`;
        const dateString = `${day}-${month}-${year}`;

        document.getElementById('digital-clock').textContent = timeString;
        document.getElementById('current-date').textContent = dateString;
        document.getElementById('current-day').textContent = dayOfWeek;
    }

    setInterval(updateClock, 1000);
    updateClock(); // Initial call to display clock immediately
</script>
@endpush