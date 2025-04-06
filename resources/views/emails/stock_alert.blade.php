<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alerte Stock - Demande d'Achat Générée</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            max-width: 700px;
            margin: auto;
        }

        .title {
            color: #d32f2f;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 16px;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background-color: #d32f2f;
            color: white;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .footer {
            font-size: 14px;
            color: #777;
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            background-color: #388e3c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #2e7d32;
        }
        
        .prediction {
            font-size: 0.9em;
            color: #666;
        }
        
        .urgent {
            color: #d32f2f;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">⚠️ Alerte de Stock Faible</h1>
        <p class="subtitle">
            Le système a détecté que certains équipements ont atteint un seuil critique (≤ 2 unités).
            Une demande d'achat a été générée automatiquement.
        </p>

        <table>
            <thead>
                <tr>
                    <th>Nom de l'Équipement</th>
                    <th>Numéro de Série</th>
                    <th>Quantité Restante</th>
                    <th>Prédiction d'Épuisement</th>
                    <th>Priorité</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->nom }}</td>
                        <td>{{ $item->numero_de_serie }}</td>
                        <td>{{ $item->quantite }}</td>
                        <td>
                            @if(isset($item->prediction))
                                @if($item->prediction['days_until_empty'] <= 7)
                                    <span class="urgent">Danger critique: {{ $item->prediction['days_until_empty'] }} jours</span>
                                @elseif($item->prediction['days_until_empty'] <= 14)
                                    <span class="warning">Urgent: {{ $item->prediction['days_until_empty'] }} jours</span>
                                @else
                                    {{ $item->prediction['days_until_empty'] }} jours
                                @endif
                                <div class="prediction">(Confiance: {{ number_format($item->prediction['confidence'] * 100, 0) }}%)</div>
                            @else
                                <span class="prediction">Analyse en cours...</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($item->prediction))
                                @if($item->prediction['days_until_empty'] <= 7)
                                    <span class="urgent">🔴 Haute</span>
                                @elseif($item->prediction['days_until_empty'] <= 14)
                                    <span class="warning">🟠 Moyenne</span>
                                @else
                                    <span>🟢 Normale</span>
                                @endif
                            @else
                                <span>⚪ Inconnue</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="background-color: #fff8e1; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="margin-top: 0; color: #ff8f00;">Recommandation du Système AI</h3>
            <p>
                @php
                    $mostUrgent = collect($items)->sortBy('prediction.days_until_empty')->first();
                @endphp
                
                @if(isset($mostUrgent->prediction))
                    L'équipement le plus critique est <strong>{{ $mostUrgent->nom }}</strong> 
                    qui pourrait être épuisé dans <strong>{{ $mostUrgent->prediction['days_until_empty'] }} jours</strong>.
                    Nous recommandons de traiter cette commande en priorité.
                @else
                    Le système AI analyse actuellement les tendances de consommation pour fournir des recommandations plus précises.
                @endif
            </p>
        </div>

        <p>
            Merci de prendre les mesures nécessaires ou de vérifier les demandes d'achat générées dans le système.
        </p>

        <a href="{{ url('/stock') }}" class="btn">Voir le stock complet</a>
        <a href="{{ url('/demandes-achat') }}" class="btn" style="margin-left: 10px;">Voir les demandes</a>

        <p class="footer">
            Cet email a été envoyé automatiquement par le système IT Park Management.<br>
            Merci de ne pas y répondre directement.
        </p>
    </div>
</body>
</html>