<?php require_once 'includes/forum_content.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/styles/style.css" >
    <style>
        <?php require_once "./assets/styles/style.css"; ?>
    </style>
    <title>Forum</title>
</head>
<body>

    <div class="forum-container">
        <h1>Bienvenue  <?php echo $_SESSION["utilisateur"]?>!</h1>
        <form class="new-post-form" method="POST">
            <textarea placeholder="Message" name="message" rows="4" required></textarea>
            <input type="submit" class="button" value="Publier">
        </form>

        <form method="POST">
            <input id="quitter" name ="deconnexion" type="submit" class="button" value="Quitter">
        </form>
    </div>
    <div class="forum-container" id="messages">
        <?php echo $messages; ?>
        <h1>Messages:</h1>
    </div>
</body>
</html>