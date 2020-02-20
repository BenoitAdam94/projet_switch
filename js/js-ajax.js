/*document.getElementById("form_connexion").addEventListener('submit', function (e) {
    e.preventDefault(); // On bloque l'evennement

    setTimeout(function(){
                    $('#connexion').modal('hide');
                   window.location.reload();
                }, 1500);
});*/



document.getElementById("form_connexion").addEventListener('submit', function (e) {
    e.preventDefault(); // On bloque l'evennement

    var cible = "ajax_connexion_ajax.php";
    console.log(cible);
    
    var pseudo = document.getElementById('pseudo').value;
    var mdp = document.getElementById('mdp').value;

    var param = 'pseudo=' + pseudo + '&mdp=' + mdp;
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

            document.getElementById('resultat').innerHTML = retour.message;
            
            
            
            if(retour.connexion == 'ok') {
                setTimeout(function(){
                    $('#connexion').modal('hide');
                   window.location.reload();
                }, 1000);
                // window.location.reload(); 
                // window.location.refresh(); /!\ renvoie le POST
                }
            }
        }
    
    xhttp.send(param);

});