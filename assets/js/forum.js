document.addEventListener('DOMContentLoaded', function() {
    let formulaire = document.getElementById('formulaire');

    formulaire.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = $('#message').val();

        formulaire.reset();

        try{
            //utilise formData pour acceder au username et password
            const resultat = await fetch('http://localhost/laboratoire_5/api/api.php/message', {
                method: 'POST',
                body: JSON.stringify({ message: message, user: sessionStorage.getItem("user")}),
                headers: {
                    'Accept': 'application/json', // envoie en format json et retourne en format json
                    'Content-Type': 'application/json'
                  },
                });
            
            const resultatJSON = await resultat.json();
            console.log(resultatJSON.success);
            //gerer la reponse, l'api retourne en json une reponse 'success' ou 'failure'
            if(!resultatJSON.success){
                $('body').append(" <div class='error-container'> <h2 class='error-title'>Erreur</h2> <p class='error-message'>Mot de passe ou nom d'utilisateur incorrect</p> </div> ");
            }
            
        }catch(error){
            console.error(error)
        }
    });


    async function fetchMessages(){
        const resultat = await fetch('http://localhost/laboratoire_5/api/api.php/messages');
        const resultatJSON = await resultat.json();
        //console.log(resultatJSON); 
        let posts = '';
        resultatJSON.forEach(element => {
            posts += `<div class='post'>
            <div class="post-title">${element['username']}</div>
            <div class="post-content">${element['message']}</div>
            <div class="post-meta">${element['date_soumission']}</div>
            </div>`;
        });
        $('#messages').html(posts);  //remplace le contenu de la div messages par les messages recus
    }

    //appel la fonction fetchMessages() a chaque 5 secondes
    let interval = setInterval(async () => {
        await fetchMessages();
    }, 5000);

    let quitter = document.getElementById('quitter');

    quitter.addEventListener('click', () => {
        window.location.href = 'index.html'; 
    });
});