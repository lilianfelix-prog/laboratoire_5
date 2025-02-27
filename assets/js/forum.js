document.addEventListener('DOMContentLoaded', function() {
    let formulaire = document.getElementById('formulaire');
    

    formulaire.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(formulaire);

        try{
            //utilise formData pour acceder au username et password
            const resultat = await fetch('http://localhost/laboratoire_5/api/api.php/message', {
                method: 'POST',
                body: JSON.stringify({ message: formData.get('message'), user: sessionStorage.getItem("user")}),
                headers: {
                    'Accept': 'application/json', // envoie en format json et retourne en format json
                    'Content-Type': 'application/json'
                  },
                });
            
            const resultatJSON = await resultat.json();
            
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
        console.log(resultatJSON.user); 
        let posts = '';
        resultatJSON.user.forEach(element => {
            posts += `<div class='post'>
            <div class="post-title">${element['username']}</div>
            <div class="post-content">${element['message']}</div>
            <div class="post-meta">${element['date_soumission']}</div>
            </div>`;
        });
        
        $('#messages').append(posts);
        
    }
    setInterval(fetchMessages(),1000);

    let quitter = document.getElementById('quitter');

    quitter.addEventListener('click', () => {
        window.location.href = 'index.html'; 
    });
});