<!DOCTYPE html>
<html>
<head>
    <title>Grafik Accuracy KNN</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="font-family: Arial; padding: 30px;">

    <h2>Grafik Accuracy Dan Nilai K</h2>

    <canvas id="accuracyChart" width="900" height="400"></canvas>

    <script>
        const labels = @json(array_column($results, 'k'));
        const accuracyData = @json(array_column($results, 'accuracy'));

        new Chart(document.getElementById('accuracyChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Accuracy (%)',
                    data: accuracyData,
                    borderWidth: 3,
                    tension: 0.2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Accuracy (%)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Nilai K'
                        }
                    }
                }
            }
        });
    </script>
    <br><br>

    <h2>Confusion Matrix</h2>

    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 80%;">
        <thead>
            <tr>
                <th>Actual \ Prediksi</th>
                @foreach($labels as $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($labels as $actual)
                <tr>
                    <td><strong>{{ $actual }}</strong></td>
                    @foreach($labels as $predicted)
                        <td>{{ $confusion[$actual][$predicted] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>

    <h2>Precision, Recall, dan F1-Score</h2>

    <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 80%;">
        <thead>
            <tr>
                <th>Status Gizi</th>
                <th>Precision</th>
                <th>Recall</th>
                <th>F1-Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metrics as $label => $metric)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $metric['precision'] }}</td>
                    <td>{{ $metric['recall'] }}</td>
                    <td>{{ $metric['f1_score'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>