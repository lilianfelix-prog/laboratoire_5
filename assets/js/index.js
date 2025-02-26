document.addEventListener('DOMContentLoaded', function() {
    let formulaire = document.getElementById('formulaire');
    

    formulaire.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(formulaire);

        //console.log(formData.get('username'));
        //console.log(formData.get('password'));

        try{
            //utilise formData pour acceder au username et password
            const resultat = await fetch('http://localhost/laboratoire_5/api/api.php/connexion', {
                method: 'POST',
                body: JSON.stringify({ username: formData.get('username'), password: formData.get('password') }),
                headers: {
                    'Accept': 'application/json', // envoie en format json et retourne en format json
                    'Content-Type': 'application/json'
                  },
                });
            
            const resultatJSON = await resultat.json();
            // console.log(resultatJSON.message);

            //gerer la reponse, l'api retourne en json une reponse 'success' ou 'failure'
            if(resultatJSON.message === 'success'){
                window.location.href = resultatJSON.redirect; // redirige vers forum.html
            }
            if (resultatJSON.message === 'failure') {
                $('body').append(" <div class='error-container'> <h2 class='error-title'>Erreur</h2> <p class='error-message'>Mot de passe ou nom d'utilisateur incorrect</p> </div> ");
            } 
            if (resultatJSON.error) {
                console.log(resultatJSON.error + resultatJSON.details);
            }

        }catch(error){
            console.error(error);
        }
        
    });

    let inscription = document.getElementById('inscription');

    inscription.addEventListener('click', () => {

        window.location.href = 'inscription.html';
        
    });
});