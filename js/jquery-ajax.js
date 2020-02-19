$(document).ready(function() {
    $('form_connexion').on('submit', function(e) {
        e.preventDefault();

        var cible = 'ajax_connexion_ajax.php';

        // récupération des paramètres
        var params = $(this).serialize();
        console.log(params);
        // $.post
        $.poste(cible, params, function(data) {
            // data respérésente la réponse
            $('resultat').html(data.message);
        }, 'json');
        
    });
});