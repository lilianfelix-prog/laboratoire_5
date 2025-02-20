<?php
    $erreur = '';
    //Cas si l'utilisateur fait une demande d'inscription 
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
            
            //Prend le nom d'utilisateur correspondant à celui mis par l'utilisateur de la base de donnée (s'il y en a un)
            $requete = $pdo->prepare("SELECT * FROM usagers WHERE username = :username");
            $requete->execute([':username' => $username]);
            $user = $requete->fetch();

            $erreur = false;

            //Détection d'erreurs

            if (strlen($password) < 8 || strlen($password) > 32) {
                $erreur = true;
                $erreur1 = "Le mot de passe doit contenir entre 8 et 32 caractères";
            }else{
                $erreur1 = "";
            }

            if (!preg_match('/[A-Z]/', $password)) {
                $erreur = true;
                $erreur2 = "Le mot de passe doit contenir au moins une majuscule";
            }else{
                $erreur2 = "";
            }

            if (!preg_match('/[a-z]/', $password)) {
                $erreur = true;
                $erreur3 = "Le mot de passe doit contenir au moins une minuscule";
            }else{
                $erreur3 = "";
            }

            if (!preg_match('/\d/', $password)) {
                $erreur = true;
                $erreur4 = "Le mot de passe doit contenir au moins un chiffre";
            }else {
                $erreur4 = "";
            } 

            if($user) {
                $erreur = true;
                $erreur5 = "Le nom d'utilisateur existe déjà";
            } else{
                $erreur5 = "";
            }
            //Ajout de l'utilisateur dans la base de donnée s'il n'y a pas d'erreurs trouvées
            if (!$erreur) {
                $password_hash = password_hash($password,PASSWORD_DEFAULT,['cost' => 4]);
                $insert_query = $pdo->prepare("INSERT INTO usagers (username, password) VALUES (:username, :password)");
                $insert_query->execute([
                    ':username' => $username,
                    ':password' => $password_hash
                ]); 
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;
            header("Location: index.php");
            exit();
            //Affichage des erreurs si il y en a trouvées
            } else {
                echo '<div class="error-container">
                        <h2 class="error-title">Erreur</h2>
                        <p class="error-message">'.$erreur5."<br>".$erreur1."<br>".$erreur2."<br>".$erreur3."<br>".$erreur4.'</p>
                    </div>';
            }
            
        } catch (PDOException $e) {
            die("La connexion a échoué: " . $e->getMessage());
        }

    } 

    //Cas de déconnexion (retour à l'index)
    if(isset($_POST['deconnexion'])) {
        header("Location: index.php");
        exit();
    }
?>