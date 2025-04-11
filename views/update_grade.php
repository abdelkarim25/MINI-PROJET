<?php
// update_grade.php - Interface de gestion des notes
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Notes</title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --text-color: #333;
            --light-gray: #f2f2f2;
            --border-color: #ddd;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        h2 {
            color: var(--secondary-color);
            border-bottom: 2px solid var(--light-gray);
            padding-bottom: 10px;
            margin-top: 30px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
        }
        
        tr:nth-child(even) {
            background-color: var(--light-gray);
        }
        
        tr:hover {
            background-color: rgba(76, 175, 80, 0.1);
        }
        
        input[type="number"] {
            width: 80px;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
        
        .btn {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
            text-decoration: none;
        }
        
        .btn:hover {
            background: var(--secondary-color);
        }
        
        .btn-block {
            display: block;
            width: 100%;
            text-align: center;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Notes des Étudiants</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Les notes ont été mises à jour avec succès!
            </div>
        <?php endif; ?>
        
        <?php
        // Récupération du cours
        if (isset($_GET['course_id'])) {
            $course_id = (int)$_GET['course_id'];
            
            try {
                // Récupérer les informations du cours
                $stmt = $db->prepare("SELECT id, name FROM courses WHERE id = ?");
                $stmt->execute([$course_id]);
                $course = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($course) {
                    // Récupérer les étudiants et leurs notes
                    $stmt = $db->prepare("
                        SELECT s.id, s.first_name, s.last_name, sc.grade
                        FROM students s
                        JOIN student_courses sc ON s.id = sc.student_id
                        WHERE sc.course_id = ?
                        ORDER BY s.last_name, s.first_name
                    ");
                    $stmt->execute([$course_id]);
                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    
                    <h2>Cours: <?= htmlspecialchars($course['name']) ?></h2>
                    
                    <form method="post" action="index.php?action=update_grade">
                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Note actuelle</th>
                                    <th>Nouvelle note</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['last_name'] . ' ' . $student['first_name']) ?></td>
                                    <td><?= $student['grade'] !== null ? $student['grade'] : '--' ?></td>
                                    <td>
                                        <input type="number" 
                                               name="grades[<?= $student['id'] ?>]" 
                                               min="0" max="20" step="0.25"
                                               value="<?= $student['grade'] ?? '' ?>"
                                               placeholder="0-20">
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="comments[<?= $student['id'] ?>]"
                                               placeholder="Commentaire optionnel">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-block">
                                Enregistrer toutes les modifications
                            </button>
                        </div>
                    </form>
                    
                    <?php
                } else {
                    echo '<p class="alert">Cours introuvable.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="alert">Erreur de base de données: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        } else {
            echo '<p class="alert">Aucun cours sélectionné.</p>';
        }
        ?>
        
        <a href="index.php?action=dashboard" class="back-link">
            ← Retour au tableau de bord
        </a>
    </div>
</body>
</html>