 @extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

     
        <style>
           .top5-table-card {
        background: #fff;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        margin: 2rem auto;
        max-width: 900px;
        overflow-x: auto;
    }

    .top5-table-card h3 {
        text-align: center;
        margin-bottom: 1.5rem;
        color: #333;
        font-size: 1.5rem;
    }

    table.top5-table {
        width: 100%;
        border-collapse: collapse;
    }

    table.top5-table th,
    table.top5-table td {
        padding: 1rem;
        text-align: center;
        border-bottom: 1px solid #eee;
    }

    table.top5-table th {
        background-color: #f8f8f8;
        color: #555;
        font-weight: 600;
    }

    table.top5-table tr:hover {
        background-color: #f1f1f1;
    }


            .dashboard-card .text-xs {
    font-size: 0.75rem;
    line-height: 1rem;
}

.dashboard-card select {
    padding: 0.25rem 0.5rem;
}
            body {
                font-family: 'Poppins', sans-serif;
            }
            .dashboard-card {
                background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
                border-radius: 2rem;
                padding: 1.5rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
                flex: 1;
                min-width: 200px;
                max-width: 240px;
                display: flex;
                flex-direction: column;
                align-items: center;
                transition: transform 0.3s ease;
            }
            .dashboard-card:hover {
                transform: translateY(-5px);
            }
            .icon-container {
                background: #e3f2fd;
                border-radius: 50%;
                padding: 1rem;
                margin-bottom: 1rem;
            }
            .dashboard-title {
                color: #333;
                font-size: 1rem;
                margin-bottom: 0.3rem;
            }
            .dashboard-count {
                font-size: 2rem;
                font-weight: 600;
                color: #1976d2;
            }
        </style>
        
        <div style="padding: 2rem;">
            {{-- Cards Section --}}
            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: space-between;">
                @php
                    $cards = [
                        ['title' => 'Total Équipements', 'icon' => 'bi-hdd-stack', 'color' => '#1976d2', 'id' => 'equipment-count'],
                        ['title' => 'Équipements en Panne', 'icon' => 'bi-exclamation-triangle', 'color' => '#e53935', 'id' => 'maintenance-count'],
                        ['title' => 'Stock Disponible', 'icon' => 'bi-box', 'color' => '#fb8c00', 'id' => 'stock-count']
                    ];
                @endphp
            
                <!-- Standard Cards -->
                @foreach($cards as $card)
                <div class="dashboard-card">
                    <div class="icon-container">
                        <i class="bi {{ $card['icon'] }}" style="font-size: 2rem; color: {{ $card['color'] }}"></i>
                    </div>
                    <div class="dashboard-title">{{ $card['title'] }}</div>
                    <div class="dashboard-count" id="{{ $card['id'] }}">0</div>
                </div>
                @endforeach
            
                <!-- Catégories Card (Special Layout) -->
                <div class="dashboard-card" style="position: relative;">
                    <!-- Selector in top-left corner -->
                    <div style="position: absolute; top: 10px; left: 10px; width: calc(100% - 20px);">
                        <form method="GET" action="{{ route('home') }}">
                            @if(isset($CategoriesList) && $CategoriesList->count())
                                <select name="categorie" id="categorie" onchange="this.form.submit()"
                                    class="text-xs block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1">
                                    <option value="">-- Toutes --</option>
                                    @foreach($CategoriesList as $categorie)
                                        <option value="{{ $categorie }}" {{ $selectedCategorie == $categorie ? 'selected' : '' }}>
                                            {{ ucfirst($categorie) }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-xs text-red-500">Aucune catégories</p>
                            @endif
                        </form>
                    </div>
            
                    <!-- Card Content (centered) -->
                    <div class="icon-container">
                        <i class="bi bi-tags" style="font-size: 2rem; color: #6f42c1"></i>
                    </div>
                    <div class="dashboard-title">Catégories</div>
                    <div class="dashboard-count">
                        @if(isset($selectedCategorie) && $selectedCategorie)
                            {{ $countForSelected ?? 0 }}
                            <div class="text-xs mt-1">dans {{ ucfirst($selectedCategorie) }}</div>
                        @else
                            {{ $totalCategories ?? 0 }}
                            <div class="text-xs mt-1">au total</div>
                        @endif
                    </div>
                </div>
            </div>
        
            {{-- Donut Chart --}}
            <div style="margin-top: 2rem; display: flex; flex-direction: row; gap: 2rem;">
                <div style="flex: 1;">
                    <div style="background: #fff; border-radius: 2rem; padding: 2rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);">
                        <canvas id="equipementDonutChart"></canvas>
                    </div>
                </div>
        
                {{-- Circle Charts --}}
                <div style="flex: 1;">
                    <h5 style="text-align: center;">Statut des Contrats</h5>
                    <canvas id="contratCircleChart" style="max-height: 220px;"></canvas>
                </div>
                
            </div>
            
        

            <div class="top5-table-card">
                <h3>Top 5 Équipements en Panne – {{ date('Y') }}</h3>
                <table class="top5-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Article</th>
                            <th>Nombre de pannes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($top5Pannes as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->article }}</td>
                                <td>{{ $item->total_pannes }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        
           
        
       

</div>
<script>
    const niceColors = [
        '#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0',
        '#9966FF', '#FF9F40', '#00A676', '#A52A2A',
        '#E6B0AA', '#AED6F1', '#F9E79F', '#D2B4DE'
    ];

    let colorIndex = 0;
    const colorMap = {}; // 🔥 You were missing this line

    function generateColor() {
        const color = niceColors[colorIndex % niceColors.length];
        colorIndex++;
        return color;
    }

    function getColorForCategory(category) {
        if (!colorMap[category]) {
            colorMap[category] = generateColor(); // Assign a color once
        }
        return colorMap[category];
    }
</script>

<script>
    async function renderTop5PannesChart() {
        try {
            const response = await fetch('top5-equipement-pannes');
            const data = await response.json();

            const labels = data.map(item => item.article);
            const values = data.map(item => item.total_pannes);
            const colors = labels.map(label => getColorForCategory(label));

            const ctx = document.getElementById('top5PannesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pannes par équipement',
                        data: values,
                        backgroundColor: colors,
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Top 5 équipements en panne en ' + new Date().getFullYear(),
                            font: {
                                size: 18
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        } catch (err) {
            console.error("Erreur lors du chargement du graphique :", err);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderTop5PannesChart();
    });
</script>



<!--script circle contrat-->
<script>
    const total = {{ $totalContrats }};
    const expired = {{ $expiredContrats }};
    const active = {{ $activeContrats }};

    const ctx = document.getElementById('contratCircleChart').getContext('2d');

    Chart.register(ChartDataLabels);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Expirés', 'Actifs'],
            datasets: [{
                label: 'Contrats',
                data: [expired, active],
                backgroundColor: ['#e74c3c', '#2ecc71'],
                borderColor: '#ffffff',
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#333',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const percentage = Math.round((value / total) * 100); // Rounded
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    color: '#000',
                    font: {
                        weight: 'bold',
                        size: 16,
                    },
                    formatter: (value, context) => {
                        const data = context.chart.data.datasets[0].data;
                        const totalValue = data.reduce((a, b) => a + b, 0);
                        const percentage = Math.round((value / totalValue) * 100); // Rounded
                        return `${percentage}%`;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>




<!--script de donut de repartition des equipements par sous-categorie-->
<script>
    async function renderEquipementDonutChart() {
        try {
            const response = await fetch('/api/equipement-by-sous-categorie');
            const data = await response.json();

            const labels = data.map(item => item.sous_categorie);
            const counts = data.map(item => item.total);

            const colors = labels.map(label => getColorForCategory(label));

            const ctx = document.getElementById('equipementDonutChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Équipements par sous-catégorie',
                        data: counts,
                        backgroundColor: colors, // use consistent colors
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        title: {
                            display: true,
                            text: 'Répartition des équipements par sous-catégorie'
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Erreur lors du chargement du graphique:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderEquipementDonutChart();
    });
</script>


<!--les cartes pour compter-->
    <script>
        async function updateEquipmentCount() {
            try {
                const response = await fetch('/api/equipment-count');
                const data = await response.json();
                document.getElementById('equipment-count').textContent = data.count;
            } catch (error) {
                console.error('Erreur lors du chargement des équipements:', error);
            }
        }
    
        // Run once on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateEquipmentCount();
    
            setInterval(updateEquipmentCount, 10000);
        });
        async function updateMaintenanceCount() {
            try {
                const response = await fetch('/api/maintenance-count');
                const data = await response.json();
                document.getElementById('maintenance-count').textContent = data.count;
            } catch (error) {
                console.error('Erreur lors du chargement des équipements en panne:', error);
            }
        }
    
        // Run once on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateMaintenanceCount();
    
            setInterval(updateMaintenanceCount, 10000);
        });
        async function updateStockCount() {
            try {
                const response = await fetch('/api/stock-count');
                const data = await response.json();
                document.getElementById('stock-count').textContent = data.count;
            } catch (error) {
                console.error('Erreur lors du chargement des équipements en stock:', error);
            }
        }
    
        // Run once on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateStockCount();
    
            setInterval(updateStockCount, 10000);
        });
        </script>

       
    

@include('layouts.sidebar')

@endsection
    
