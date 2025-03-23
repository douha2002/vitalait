<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Planifiée</title>
</head>
<body>
    <h1>Maintenance Planifiée</h1>
    <p>Un équipement nécessite une maintenance:</p>
    <ul>
        <li>Equipement: {{ $maintenance->equipement->numero_serie }}</li>
        <li>Date de début: {{ $maintenance->date_debut }}</li>
        <li>Commentaires: {{ $maintenance->commentaires }}</li>
    </ul>
</body>
</html>