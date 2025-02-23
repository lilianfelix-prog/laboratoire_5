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

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

