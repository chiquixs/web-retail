document.addEventListener("DOMContentLoaded", function () {
    fetch("index.php?page=admin_daily_sales")
        .then(response => response.json())
        .then(data => {
            // Akses array 'dates' dan 'revenues' secara langsung
            const labels = data.dates;
            const revenues = data.revenues;
            new Chart(document.getElementById("dailySalesChart"), {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Daily Revenue",
                        data: revenues,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
});