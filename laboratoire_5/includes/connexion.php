<?php
    session_start();
    //Nom d'utilisateur et mot de passe de l'utilisateur qui vient de s'inscrire déjà dans les champs
    if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
        $nom_utilisateur = $_SESSION["username"];
        $mdp = $_SESSION["password"];
        session_unset();
    }else{
        $nom_utilisateur = "";
        $mdp = "";
    }

    //Cas de tentative de connexion
    if (isset($_POST['username']) && isset($_POST['password'])) {

        // Nettoyer les données
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        // Importer les paramètres de connexion
        $config = require './config.php';

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
                $_SESSION["utilisateur"] = $username;
                header("Location: forum.php");
                exit();
            //Affichage du message d'erreur
            }else{
                echo "<div class='error-container'>
                        <h2 class='error-title'>Erreur</h2>
                        <p class='error-message'>Mot de passe ou nom d'utilisateur incorrect</p>
                    </div>";
            }

            
        } catch (PDOException $e) {
            die("La connexion a échoué: " . $e->getMessage());
        }

    }
    //Cas de demande d'inscription
    if(isset($_POST['inscription'])) {
        header("Location: inscription.php");
        exit();
    }
?>