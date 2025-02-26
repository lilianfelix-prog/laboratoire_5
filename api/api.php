<?php

//server doit autoriser les cross-origine request, mettre autorisation dans .htaccess
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'router.php';

$router = new Router();

$router->post('/api.php/connexion', function(){
    
    // accede au donne json du form
    $data = file_get_contents('php://input');
    $userdata = json_decode($data, true);

        // Nettoyer les données
        $username = htmlspecialchars($userdata['username']);
        $password = htmlspecialchars($userdata['password']);


        //Cas de tentative de connexion
        if (isset($username) && isset($password)) {

            // Importer les paramètres de connexion
            $config = require '../config.php';

            try {
                
                // Options de connexion
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,     
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC             
                ];

                // Instancier la connexion
                $pdo = new PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
                    $config['dbusername'],
                    $config['dbpassword'],
                    $options
                );
                
                //Prendre l'utilisateur dans la base de donnée qui correspons avec le nom d'utilisateur mis dans le champs 
                $requete = $pdo->prepare("SELECT * FROM usagers WHERE username = :username");
                $requete->execute([':username' => $username]);
                $user = $requete->fetch();

                //Cas si le mot de passe et le nom d'utilisateur corresponde (accès au forum)
                if($user && password_verify($password, $user['password'])){
                    //retourn en format json
                    header('Content-Type: application/json'); 
                    echo json_encode(["message" => "success", "redirect" => "forum.html"]);
                    
                //Affichage du message d'erreur
                }else{
                    header('Content-Type: application/json');
                    echo json_encode(["message" => "failure"]);
                }

                
            } catch (PDOException $e) {
                echo json_encode(["error" => "Database error", "details" => $e->getMessage()]);
            }

        }
});

$router->post('/api.php/inscription', function(){

    // accede au donne json du form
    $data = file_get_contents('php://input');
    $userdata = json_decode($data, true);

        // Nettoyer les données
        $username = htmlspecialchars($userdata['username']);
        $password = htmlspecialchars($userdata['password']);


    //Cas de tentative de connexion
    if (isset($username) && isset($password)) {
        // Importer les paramètres de connexion
        $config = require '../config.php';

        try {
            
            // Options de connexion
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,     
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC             
            ];

            // Instancier la connexion
            $pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
                $config['dbusername'],
                $config['dbpassword'],
                $options
            );
            
            //Prend le nom d'utilisateur correspondant à celui mis par l'utilisateur de la base de donnée (s'il y en a un)
            $requete = $pdo->prepare("SELECT * FROM usagers WHERE username = :username");
            $requete->execute([':username' => $username]);
            $user = $requete->fetch();

            $erreur = false;
            //initialise une liste d'erreur possible pour le mot de passe
            $erreurList = [];
            //Détection d'erreurs
            if (strlen($password) < 8 || strlen($password) > 32) {
                $erreur = true;
                $erreurList[] = "Le mot de passe doit contenir entre 8 et 32 caractères";
            }

            if (!preg_match('/[A-Z]/', $password)) {
                $erreur = true;
                $erreurList[] = "Le mot de passe doit contenir au moins une majuscule";
            }

            if (!preg_match('/[a-z]/', $password)) {
                $erreur = true;
                $erreurList[] = "Le mot de passe doit contenir au moins une minuscule";
            }

            if (!preg_match('/\d/', $password)) {
                $erreur = true;
                $erreurList[] = "Le mot de passe doit contenir au moins un chiffre";
            }

            if($user) {
                $erreur = true;
                $erreurList[] = "Le nom d'utilisateur existe déjà";
            }
            //Ajout de l'utilisateur dans la base de donnée s'il n'y a pas d'erreurs trouvées
            if (!$erreur) {
                $password_hash = password_hash($password,PASSWORD_DEFAULT,['cost' => 4]);
                $insert_query = $pdo->prepare("INSERT INTO usagers (username, password) VALUES (:username, :password)");
                $insert_query->execute([
                    ':username' => $username,
                    ':password' => $password_hash
                ]); 
            
                header('Content-Type: application/json');
                echo json_encode(['message' => 'success', "redirect" => "index.html"]);
                
            //Affichage des erreurs si il y en a trouvées
            //retourne la list d'erreur en json
            } else {
                header('Content-Type: application/json');
                echo json_encode(['erreur' => $erreurList]);
            }
            
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(["erreur_db" => "Database error", "details" => $e->getMessage()]);
        }
    }
});

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

