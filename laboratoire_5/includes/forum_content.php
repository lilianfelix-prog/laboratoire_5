<?php
    session_start();
    $messages = '';
    //Protection contre les non-utilisateurs
    if(isset($_SESSION["utilisateur"])){
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

           //Ajout du message qui vient d'être envoyé à la base de données
           if (isset($_POST['message'])) { 
            $requete = $pdo->prepare("SELECT * FROM usagers WHERE username = :username");
            $requete->execute([':username' => $_SESSION["utilisateur"]]);
            $user = $requete->fetch();
               $insert_query = $pdo->prepare("INSERT INTO messages (date_soumission, message, user_id) VALUES (:date_soumission, :message, :user_id)");
                $insert_query->execute([
                    ':date_soumission' => date("Y/m/d"),
                    ':message' => $_POST['message'],
                    ':user_id' => $user["user_id"]
                ]);
            }

            //Requête avec PDO pour prendre tous les messages
            $requete = $pdo->query("
                SELECT messages.message, messages.user_id, usagers.username, messages.date_soumission 
                FROM messages 
                INNER JOIN usagers ON messages.user_id = usagers.user_id
            ");
            $user = $requete->fetchAll();

        
            //Affichage des messages
           foreach($user as $mess){
            $messages .= '
            <div class="post">
                <div class="post-title">' . $mess['username'] . '</div>
                <div class="post-content">' . $mess['message'] . '</div>
                <div class="post-meta">'. $mess['date_soumission'] .'</div>
            </div>
            ';
           }
           
        

        } catch (PDOException $e) {
            die("La connexion a échoué: " . $e->getMessage());
        }

        //Protection contre les non-utilisateurs
    }else{
        header("Location: index.php");
    }

    //Cas de déconnexion
    if(isset($_POST['deconnexion'])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
?>
