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
                        ['title' => 'Total Affectations', 'icon' => 'bi-person-check', 'color' => '#43a047', 'id' => 'assignment-count'],
                        ['title' => 'Équipements en Panne', 'icon' => 'bi-exclamation-triangle', 'color' => '#e53935', 'id' => 'maintenance-count'],
                        ['title' => 'Stock Disponible', 'icon' => 'bi-box', 'color' => '#fb8c00', 'id' => 'stock-count']
                    ];
                @endphp
        
                @foreach($cards as $card)
                <div class="dashboard-card">
                    <div class="icon-container">
                        <i class="bi {{ $card['icon'] }}" style="font-size: 2rem; color: {{ $card['color'] }}"></i>
                    </div>
                    <div class="dashboard-title">{{ $card['title'] }}</div>
                    <div class="dashboard-count" id="{{ $card['id'] }}">0</div>
                </div>
                @endforeach
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
                    <h5 style="text-align: center;">Statut des Employés</h5>
                    <canvas id="employeeAssignmentCircle" style="max-height: 220px;"></canvas>
        
                    <h5 style="text-align: center; margin-top: 2rem;">Statut des Contrats</h5>
                    <canvas id="contratCircleChart" style="max-height: 220px;"></canvas>
                </div>
            </div>
        
            {{-- Bar Chart --}}
            <div style="width: 100%; margin: 20px 0;">
                <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <!-- Chart Title -->
                        <!-- Year Select -->
                        <select id="yearSelector" style="padding: 5px 10px; font-size: 1rem; border-radius: 5px; border: 1px solid #ddd;" onchange="changeYear()">
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <!-- Add more years as needed -->
                        </select>
                        
                    </div>
                    <div style="position: relative; height: 500px;">
                        <canvas id="assignmentsBarChart"></canvas>
                    </div>
                </div>
            </div>
        
        
        
        
       

</div>
<!--script circle contrat-->
  <script>
     const total = {{ $totalContrats }};
    const expired = {{ $expiredContrats }};
    const active = {{ $activeContrats }};

    const ctx = document.getElementById('contratCircleChart').getContext('2d');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Expirés', 'Actifs'],
            datasets: [{
                label: 'Contrats',
                data: [expired, active],
                backgroundColor: ['#e74c3c', '#2ecc71'],
                borderColor: '#ffffff',
                borderWidth: 3,
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
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                // Show percentage in the center
                datalabels: {
                    display: true,
                    formatter: (value, ctx) => {
                        const sum = ctx.chart._metasets[0].total;
                        return (value * 100 / sum).toFixed(1) + "%";
                    },
                    color: '#000',
                }
            }
        },
    });
</script>

<!--script circle employee-->
<script>
    fetch('{{ route('employee.assignment.percentage') }}')
        .then(response => response.json())
        .then(data => {
            const assigned = data.assigned;
            const total = data.total;
            const unassigned = total - assigned;

            const assignedPercentage = Math.round((assigned / total) * 100);
            const unassignedPercentage = 100 - assignedPercentage;

            const ctx = document.getElementById('employeeAssignmentCircle').getContext('2d');
            const employeeCircleChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Employées Assigné', 'Employées Non Assigné'],
                    datasets: [{
                        label: 'Répartition des employés',
                        data: [assigned, unassigned],
                        backgroundColor: ['#36A2EB', '#FF6384'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        tooltip: {
                            enabled: true
                        },
                        legend: {
                            display: true,
                            position: 'bottom'
                        },
                        datalabels: {
                            display: true,
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 16
                            },
                            formatter: function(value, context) {
                                const totalValue = context.chart._metasets[0].total;
                                const percent = Math.round((value / totalValue) * 100);
                                return percent + '%';
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
</script>




<!--script bart des affectations par mois-->
<script>
   let assignmentsBarChart = null;

async function renderAssignmentsBarChart(year) {
    try {
        const response = await fetch(`/api/assignments-by-month?year=${year}`);

        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const data = await response.json();
        const ctx = document.getElementById('assignmentsBarChart');
        
        if (!ctx) {
            throw new Error('Élément canvas introuvable');
        }

        if (assignmentsBarChart) {
            assignmentsBarChart.destroy();
        }

        assignmentsBarChart = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: data.labels, // Mois en français depuis le backend
                datasets: data.datasets.map(dataset => ({
                    ...dataset,
                    backgroundColor: dataset.backgroundColor || getRandomColor()
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45,
                            padding: 10
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Nombre d\'affectations'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label} : ${context.raw}`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: `Affectations d'équipements par mois (${year})`,
                        font: { size: 16 },
                        padding: { top: 10, bottom: 30 }
                    }
                }
            }
        });

    } catch (error) {
        console.error('Erreur lors du chargement du graphique :', error);
        const container = document.getElementById('assignmentsBarChart');
        if (container) {
            container.innerHTML = `
                <div style="color: red; padding: 20px; text-align: center;">
                    Erreur lors du chargement du graphique : ${error.message}
                    <br><br>
                    <button onclick="renderAssignmentsBarChart()" 
                            style="padding: 5px 10px; background: #f44336; color: white; border: none; border-radius: 4px;">
                        Réessayer
                    </button>
                </div>
            `;
        }
    }
}

// Fonction pour générer une couleur aléatoire
function getRandomColor() {
    const colors = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
        '#FF9F40', '#C9CBCF', '#00A676', '#A52A2A', '#E6B0AA',
        '#AED6F1', '#F9E79F', '#D2B4DE', '#AAB7B8', '#85C1E9'
    ];
    return colors[Math.floor(Math.random() * colors.length)];
}

// Fonction pour changer l'année
function changeYear() {
    const selectedYear = document.getElementById('yearSelector').value;
    renderAssignmentsBarChart(selectedYear); // Call the function to render the chart with the selected year
}


document.addEventListener('DOMContentLoaded', () => {
    renderAssignmentsBarChart(new Date().getFullYear()); // Initial render for the current year

    // Attach the change event listener to the year select element
    const yearSelector = document.getElementById('yearSelector');
    yearSelector.addEventListener('change', () => {
        changeYear();
    });
});
async function loadYearSelector() {
    try {
        const response = await fetch('/api/assignments-by-year');
        const data = await response.json();

        const yearSelector = document.getElementById('yearSelector');
        yearSelector.innerHTML = ''; // Clear current options

        // Add an initial "Select Year" option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Sélectionner l\'année';
        yearSelector.appendChild(defaultOption);

        // Add options for each year available in the response
        data.years.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearSelector.appendChild(option);
        });

        // Optionally, you can trigger a chart update for the selected year
        const currentYear = new Date().getFullYear();
        yearSelector.value = currentYear;  // Set the current year as the default value
        renderAssignmentsBarChart(currentYear); // Render the chart for the current year
    } catch (error) {
        console.error('Erreur lors du chargement des années:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadYearSelector(); // Load the years when the page loads

    // Set up an event listener for when the user selects a different year
    document.getElementById('yearSelector').addEventListener('change', function () {
        const selectedYear = this.value;
        renderAssignmentsBarChart(selectedYear); // Re-render the chart for the selected year
    });
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

            const ctx = document.getElementById('equipementDonutChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Équipements par sous-catégorie',
                        data: counts,
                        backgroundColor: [
                            '#00bcd4', '#f44336', '#4caf50', '#ff9800', '#9c27b0', '#3f51b5', '#009688'
                        ],
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
        async function updateAssignmentCount() {
            try {
                const response = await fetch('/api/assignment-count');
                const data = await response.json();
                document.getElementById('assignment-count').textContent = data.count;
            } catch (error) {
                console.error('Erreur lors du chargement des équipements affecte:', error);
            }
        }
    
        // Run once on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateAssignmentCount();
    
            setInterval(updateAssignmentCount, 10000);
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
    
