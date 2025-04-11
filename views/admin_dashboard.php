<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Dashboard Administrateur</h1>
    <p><a href="?action=add_student">Ajouter un étudiant</a> | <a href="?action=logout">Déconnexion</a></p>
    
    <h2>Étudiants</h2>
    <table>
        <tr><th>ID</th><th>Nom</th><th>Prénom</th></tr>
        <?php foreach($students as $student): ?>
        <tr>
            <td><?= $student['id'] ?></td>
            <td><?= $student['last_name'] ?></td>
            <td><?= $student['first_name'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>Enseignants</h2>
    <table>
        <tr><th>ID</th><th>Nom</th><th>Prénom</th></tr>
        <?php foreach($teachers as $teacher): ?>
        <tr>
            <td><?= $teacher['id'] ?></td>
            <td><?= $teacher['last_name'] ?></td>
            <td><?= $teacher['first_name'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <h2>Cours</h2>
    <table>
        <tr><th>ID</th><th>Nom</th><th>Description</th></tr>
        <?php foreach($courses as $course): ?>
        <tr>
            <td><?= $course['id'] ?></td>
            <td><?= $course['name'] ?></td>
            <td><?= $course['description'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>