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

    //fonction pour ajouter les messages dans le forum
    function ajouterMessages(messages) {
        let fragment = document.createDocumentFragment();
        
        messages.forEach(message => {
            const postDiv = document.createElement('div');
            postDiv.className = 'post';
            postDiv.innerHTML = `
                <div class="post-title">${message.username}</div>
                <div class="post-content">${message.message}</div>
                <div class="post-meta">${message.date_soumission}</div>
            `;
            fragment.appendChild(postDiv);
        });
        document.getElementById('messages').appendChild(fragment);
    }
      

    let displayedMessageIds = new Set();
    //fonction pour fetch les messages du forum 
    async function fetchMessages(){
        const resultat = await fetch('http://localhost/laboratoire_5/api/api.php/messages');
        const resultatJSON = await resultat.json();
        //si le message n'est pas deja affiche on l'ajoute a la liste
        const newMessages = resultatJSON.filter(msg => !displayedMessageIds.has(msg.message_id)); 
        
        if (newMessages.length > 0) {
            
            newMessages.forEach(msg => displayedMessageIds.add(msg.message_id));
            ajouterMessages(newMessages);
        }  
    }
    setInterval(fetchMessages, 5000);

    

    let quitter = document.getElementById('quitter');

    quitter.addEventListener('click', () => {
        window.location.href = 'index.html'; 
    });
});