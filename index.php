<?php
// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'gestion_cours';
$db_user = 'root';
$db_pass = '';

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

// Initialisation de la session
session_start();

// Fonctions utilitaires
function redirect($url) {
    header("Location: $url");
    exit;
}

function loadView($viewName, $data = []) {
    $viewPath = __DIR__ . '/views/' . $viewName . '.php';
    if (file_exists($viewPath)) {
        extract($data);
        include $viewPath;
    } else {
        die("Fichier de vue introuvable: $viewPath");
    }
}

// Vérification de l'authentification
function checkAuth() {
    if(!isset($_SESSION['user_id'])) {
        redirect('?action=login');
    }
}

// Traitement des actions
$action = $_GET['action'] ?? 'home';

switch($action) {
    case 'login':
        // Connexion
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = sha1($_POST['password']);
            
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
            $stmt->execute([$email, $password]);
            $user = $stmt->fetch();
            
            if($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                redirect('?action=dashboard');
            } else {
                $error = "Identifiants incorrects";
            }
        }
        loadView('login', ['error' => $error ?? null]);
        break;
        
    case 'logout':
        session_destroy();
        redirect('?action=login');
        break;
        
    case 'dashboard':
        checkAuth();
        $role = $_SESSION['user_role'];
        
        if($role === 'admin') {
            $students = $db->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $teachers = $db->query("SELECT * FROM teachers")->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $courses = $db->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC) ?: [];
            
            loadView('admin_dashboard', [
                'students' => $students,
                'teachers' => $teachers,
                'courses' => $courses
            ]);
            
        } elseif($role === 'teacher') {
            $teacher_id = $_SESSION['user_id'];
            $stmt = $db->prepare("
                SELECT c.* FROM courses c
                JOIN teacher_courses tc ON c.id = tc.course_id
                WHERE tc.teacher_id = ?
            ");
            $stmt->execute([$teacher_id]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            
            loadView('teacher_dashboard', ['courses' => $courses]);
            
        } elseif($role === 'student') {
            $student_id = $_SESSION['user_id'];
            $stmt = $db->prepare("SELECT * FROM students WHERE user_id = ?");
            $stmt->execute([$student_id]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
            
            $stmt = $db->prepare("
                SELECT c.name, sc.grade FROM student_courses sc
                JOIN courses c ON sc.course_id = c.id
                WHERE sc.student_id = ?
            ");
            $stmt->execute([$student['id'] ?? 0]);
            $grades = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            
            loadView('student_dashboard', [
                'student' => $student,
                'grades' => $grades
            ]);
        }
        break;
        
    case 'add_student':
        checkAuth();
        if($_SESSION['user_role'] !== 'admin') {
            die("Accès non autorisé");
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = sha1('password123');
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            
            $stmt = $db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'student')");
            $stmt->execute([$email, $password]);
            $user_id = $db->lastInsertId();
            
            $stmt = $db->prepare("INSERT INTO students (user_id, first_name, last_name) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $first_name, $last_name]);
            
            redirect('?action=dashboard');
        }
        
        loadView('add_student');
        break;
        
    case 'update_grade':
        checkAuth();
        if($_SESSION['user_role'] !== 'teacher') {
            die("Accès non autorisé");
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student_id = $_POST['student_id'];
            $course_id = $_POST['course_id'];
            $grade = $_POST['grade'];
            
            $stmt = $db->prepare("
                UPDATE student_courses SET grade = ? 
                WHERE student_id = ? AND course_id = ?
            ");
            $stmt->execute([$grade, $student_id, $course_id]);
            
            redirect('?action=dashboard');
        }
        
        loadView('update_grade');
        break;
        
    default:
        if(isset($_SESSION['user_id'])) {
            redirect('?action=dashboard');
        } else {
            redirect('?action=login');
        }
}
?>