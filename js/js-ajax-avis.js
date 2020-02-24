console.log('log');

document.getElementById("form_avis").addEventListener('submit', function (e) {
    e.preventDefault(); // On bloque l'evennement

    var cible = "ajax_avis.php";
    console.log(cible);
    
    var note = document.getElementById('note').value;
    var commentaire = document.getElementById('commentaire').value;

    var param = 'note=' + note + '&commentaire=' + commentaire;
    console.log(param);

    // instanciation de l'objet ajax
    if(window.XMLHttpRequest) {
        var xhttp = new XMLHttpRequest(); // pour tous les navigateurs
    } else {
        var xhttp = new ActiveXObject("Microsoft.XMLHTTP"); // pour IE <9
    }

    xhttp.open('POST', cible, true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    // événement
    xhttp.onreadystatechange = function() {
        if(xhttp.status == 200 && xhttp.readyState ==4) {
            console.log(xhttp.responseText);

            var retour = JSON.parse(xhttp.responseText);
            console.log(retour);

            document.getElementById('resultat_avis').innerHTML = retour.message;
            
            
            
            if(retour.connexion == 'ok') {
                setTimeout(function(){
                    $('form_avis').modal('hide');
                   window.location.reload();
                }, 1000);
                // window.location.reload(); 
                // window.location.refresh(); /!\ renvoie le POST
                }
            }
        }
    
    xhttp.send(param);

});