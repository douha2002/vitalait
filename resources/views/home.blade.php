@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div style="margin-top: 30px; padding: 0 20px;">

        <div style="
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap; /* Ensures they wrap on smaller screens */
    margin-top: 30px;">

        <div style="
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 12px;
    text-align: center;
    width: 100%;
    max-width: 220px;
    margin: auto;
    transition: all 0.3s ease;
">
    <div style="display: flex; flex-direction: column; align-items: center;">
        <!-- Icon -->
        <div style="
            background-color: #e0f7fa;
            border-radius: 50%;
            padding: 15px;
            margin-bottom: 15px;
        ">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#00bcd4" class="bi bi-hdd-stack" viewBox="0 0 16 16">
                <path d="M14 4H2a1 1 0 0 0-1 1v1h14V5a1 1 0 0 0-1-1Zm1 2.5H1v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1ZM14 11H2a1 1 0 0 0-1 1v1h14v-1a1 1 0 0 0-1-1Zm1 2.5H1v.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-.5Z"/>
            </svg>
        </div>

        <!-- Title -->
        <h5 style="margin: 0; font-size: 1rem; color: #333;">Nombre total d'équipements</h5>

        <!-- Count -->
        <h2 id="equipment-count" style="font-size: 2.2rem; font-weight: bold; margin: 10px 0 0; color: #00bcd4;">
            0
        </h2>
    </div>
        </div>
   
    <div style="
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 12px;
    text-align: center;
    width: 100%;
    max-width: 220px;
    margin: auto;
    transition: all 0.3s ease;
">
    <div style="display: flex; flex-direction: column; align-items: center;">
        <!-- Icon -->
        <div style="
            background-color: #e0f7fa;
            border-radius: 50%;
            padding: 15px;
            margin-bottom: 15px;
        ">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#00bcd4" class="bi bi-person-check" viewBox="0 0 16 16">
                <path d="M15.854 5.146a.5.5 0 0 1 0 .708l-3.182 3.182a.5.5 0 0 1-.707 0l-1.182-1.182a.5.5 0 1 1 .707-.707l.829.828 2.828-2.828a.5.5 0 0 1 .707 0z"/>
                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm6-4c-2.577 0-4.129 1.292-4.828 2H11.83c-.699-.708-2.25-2-4.828-2z"/>
                <path d="M8 7a3 3 0 1 0-6 0 3 3 0 0 0 6 0z"/>
            </svg>
        </div>
  <!-- Title -->
  <h5 style="margin: 0; font-size: 1rem; color: #333;">Nombre total des affectations</h5>

  <!-- Count -->
  <h2 id="assignment-count" style="font-size: 2.2rem; font-weight: bold; margin: 10px 0 0; color: #00bcd4;">
      0
  </h2>
    </div>
    </div>
    <div style="
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 12px;
    text-align: center;
    width: 100%;
    max-width: 220px;
    margin: auto;
    transition: all 0.3s ease;
">
    <div style="display: flex; flex-direction: column; align-items: center;">
        <!-- Icon -->
        <div style="
            background-color: #e0f7fa;
            border-radius: 50%;
            padding: 15px;
            margin-bottom: 15px;
        ">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#f44336" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
                <path d="M7.938 2.016a.13.13 0 0 1 .125 0l6.857 11.856c.04.07.04.158 0 .228a.13.13 0 0 1-.125.07H1.205a.13.13 0 0 1-.125-.07.13.13 0 0 1 0-.228L7.938 2.016zm.562-.823a1.13 1.13 0 0 0-1 0L.643 13.05c-.56.967.12 2.18 1.25 2.18h12.214c1.13 0 1.81-1.213 1.25-2.18L8.5 1.193zM7.001 11a1 1 0 1 0 2 0 1 1 0 0 0-2 0zm.93-6.481a.5.5 0 0 0-.858 0l-2.5 4.5a.5.5 0 0 0 .43.75h5a.5.5 0 0 0 .43-.75l-2.5-4.5z"/>
              </svg>
              
        </div>
  <!-- Title -->
  <h5 style="margin: 0; font-size: 1rem; color: #333;">Nombre total des équipements en panne</h5>

  <!-- Count -->
  <h2 id="maintenance-count" style="font-size: 2.2rem; font-weight: bold; margin: 10px 0 0; color: #00bcd4;">
      0
  </h2>
        </div>
    </div>
        <div style="
        background: white;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    padding: 12px;
    text-align: center;
    width: 100%;
    max-width: 220px;
    margin: auto;
    transition: all 0.3s ease;
    ">
        <div style="display: flex; flex-direction: column; align-items: center;">
            <!-- Icon -->
            <div style="
                background-color: #e0f7fa;
                border-radius: 50%;
                padding: 15px;
                margin-bottom: 15px;
            ">
               <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#2196f3" class="bi bi-box" viewBox="0 0 16 16">
                <path d="M8.21.5a1 1 0 0 0-.42 0l-6 1.5A1 1 0 0 0 1 3v10a1 1 0 0 0 .79.97l6 1.5a1 1 0 0 0 .42 0l6-1.5A1 1 0 0 0 15 13V3a1 1 0 0 0-.79-.97l-6-1.5zM2 3.2l6 1.5v9.6l-6-1.5V3.2zM14 3.2v9.6l-6 1.5V4.7l6-1.5z"/>
              </svg>
              
                  
            </div>
      <!-- Title -->
      <h5 style="margin: 0; font-size: 1rem; color: #333;">Nombre total des équipements en stock</h5>
    
      <!-- Count -->
      <h2 id="stock-count" style="font-size: 2.2rem; font-weight: bold; margin: 10px 0 0; color: #00bcd4;">
          0
      </h2>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-start; padding: 30px;">
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <canvas id="equipementDonutChart" width="300" height="300"></canvas>
            </div>
        </div>
        <div style="width: 100%; margin: 20px 0;">
            <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="position: relative; height: 500px;">
                    <canvas id="assignmentsBarChart"></canvas>
                </div>
            </div>
        </div>

        
       

</div>


<script>
    let assignmentsBarChart = null;

    async function renderAssignmentsBarChart() {
        try {
            const response = await fetch('/api/assignments-by-month');

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
                            text: `Affectations d'équipements par mois (${new Date().getFullYear()})`,
                            font: { size: 16 },
                            padding: { top: 10, bottom: 30 }
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Erreur lors de l\'affichage du graphique :', error);
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

    document.addEventListener('DOMContentLoaded', () => {
        renderAssignmentsBarChart();
    });
</script>


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
    
