<?php
// add_student.php - Formulaire d'ajout d'étudiant
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un Étudiant</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .back-link { display: block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un nouvel étudiant</h1>
        
        <form method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="first_name">Prénom:</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            
            <div class="form-group">
                <label for="last_name">Nom:</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
            
            <button type="submit">Ajouter l'étudiant</button>
        </form>
        
        <a href="?action=dashboard" class="back-link">Retour au tableau de bord</a>
    </div>
</body>
</html>