<?php
// view_grades.php - Consultation des notes
if (!isset($_GET['course_id']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: ?action=dashboard");
    exit;
}

$course_id = (int)$_GET['course_id'];
$teacher_id = $_SESSION['user_id'];

// Vérification des droits
$stmt = $db->prepare("SELECT 1 FROM teacher_courses WHERE teacher_id = ? AND course_id = ?");
$stmt->execute([$teacher_id, $course_id]);
if (!$stmt->fetch()) {
    die("Accès non autorisé à ce cours");
}

// Récupération des données
$course = $db->prepare("SELECT name FROM courses WHERE id = ?")->execute([$course_id])->fetch(PDO::FETCH_ASSOC);
$students = $db->prepare("
    SELECT s.first_name, s.last_name, sc.grade
    FROM students s
    JOIN student_courses sc ON s.id = sc.student_id
    WHERE sc.course_id = ?
    ORDER BY s.last_name
")->execute([$course_id])->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notes du cours</title>
    <!-- Styles... -->
</head>
<body>
    <div class="container">
        <h1>Notes du cours: <?= htmlspecialchars($course['name']) ?></h1>
        
        <table>
            <tr>
                <th>Étudiant</th>
                <th>Note</th>
            </tr>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['last_name'] . ' ' . $student['first_name']) ?></td>
                <td><?= $student['grade'] ?? 'Non noté' ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <a href="?action=dashboard" class="back-link">Retour</a>
    </div>
</body>
</html>