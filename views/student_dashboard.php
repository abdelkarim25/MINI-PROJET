<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Étudiant</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Dashboard Étudiant</h1>
    <p><a href="?action=logout">Déconnexion</a></p>
    
    <h2>Mes Informations</h2>
    <p><strong>Nom:</strong> <?= $student['last_name'] ?></p>
    <p><strong>Prénom:</strong> <?= $student['first_name'] ?></p>
    
    <h2>Mes Notes</h2>
    <table>
        <tr><th>Cours</th><th>Note</th></tr>
        <?php foreach($grades as $grade): ?>
        <tr>
            <td><?= $grade['name'] ?></td>
            <td><?= $grade['grade'] ?? 'Non noté' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>