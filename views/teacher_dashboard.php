<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Enseignant</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Dashboard Enseignant</h1>
    <p><a href="?action=logout">Déconnexion</a></p>
    
    <h2>Mes Cours</h2>
    <table>
        <tr><th>ID</th><th>Nom</th><th>Description</th><th>Action</th></tr>
        <?php foreach($courses as $course): ?>
        <tr>
            <td><?= $course['id'] ?></td>
            <td><?= $course['name'] ?></td>
            <td><?= $course['description'] ?></td>
            <td><a href="?action=update_grade&course_id=<?= $course['id'] ?>">Mettre à jour les notes</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>