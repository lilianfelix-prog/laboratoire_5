document.addEventListener('DOMContentLoaded', function() {
    let formulaire = document.getElementById('formulaire');
    

    formulaire.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(formulaire);
        

        try{
            //utilise formData pour acceder au username et password
            const resultat = await fetch('http://localhost/laboratoire_5/api/api.php/inscription', {
                method: 'POST',
                body: JSON.stringify({ username: formData.get('username'), password: formData.get('password') }),
                headers: {
                    'Accept': 'application/json', // envoie en format json et retourne en format json
                    'Content-Type': 'application/json'
                  },
                });
            
            const resultatJSON = await resultat.json();
            
            console.log(resultatJSON.erreur);

            //gerer la reponse, l'api retourne en json une reponse 'success' ou 'failure'
            if(resultatJSON.message === 'success'){
                window.location.href = resultatJSON.redirect; //redirige vers index.html
            }
            
            if (resultatJSON.erreur) {
                //message d'erreur avec la list des erreurs pour le nouveau mot de passe
            let erreurMsg = "<div class='error-container'> <h2 class='error-title'>Erreur</h2>";
                resultatJSON.erreur.forEach(element => {
                    erreurMsg += `<p class='error-message'>${element}</p>`;
                });
                
                $('body').append(`${erreurMsg} </div>`);
            } 
            if (resultatJSON.erreur_db) {
                console.log(resultatJSON.error + resultatJSON.details);
            }

            }catch(error){
                console.error(error)
            }
    });

    let quitter = document.getElementById('quitter');

    quitter.addEventListener('click', () => {
        window.location.href = 'index.html'; 
    });
});