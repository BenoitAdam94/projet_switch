<?php


$pseudo = '';

// est ce que le formulaire a été validé
if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {
  $pseudo = trim($_POST['pseudo']);
  $mdp = trim($_POST['mdp']);

  // on récupère les informations en bdd de l'utilisateur sur la base du pseudo (unique en bdd)
  $verif_connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
  $verif_connexion->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
  $verif_connexion->execute();

  if ($verif_connexion->rowCount() > 0) {
    // s'il y a une ligne dans $verif_connexion alors le pseudo est bon
    $infos = $verif_connexion->fetch(PDO::FETCH_ASSOC);
    // echo '<pre>'; var_dump($infos); echo '</pre>';

    // on compare le mot de passe qui a été crypté avec password_hash() via la fonction prédéfinie pasword_verify()
    if (password_verify($mdp, $infos['mdp'])) {
      // le pseudo et le mot de passe sont corrects, on enregistre les informations du membre dans la session 

      $_SESSION['membre'] = array();

      $_SESSION['membre']['id_membre'] = $infos['id_membre'];
      $_SESSION['membre']['pseudo'] = $infos['pseudo'];
      $_SESSION['membre']['nom'] = $infos['nom'];
      $_SESSION['membre']['prenom'] = $infos['prenom'];
      $_SESSION['membre']['civilite'] = $infos['civilite'];
      $_SESSION['membre']['email'] = $infos['email'];
      $_SESSION['membre']['statut'] = $infos['statut'];

      // maintenant que l'utilisateur est connecté, on le redirige vers profil.php
      header('location:profil.php');
      // header('location:...) doit être exécuté AVANT le moindre affichage dans la page sinon => bug


    } else {
      $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';
    }
  } else {
    $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';
  }
}

?>





<div class="modal fade" id="connexion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Connexion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="form_connexion">
          <div class="form-group">
            <label>Pseudo</label>
            <input type="text" name="pseudo" value="" id="pseudo" class="form-control">
          </div>
          <div class="form-group">
            <label>Mot de passe</label>
            <input type="text" name="mdp" value="" id="mdp" class="form-control">
          </div>
          <div class="form-group">

            <input type="submit" name="connexion" value="Connexion" id="connexion" class="form-control btn btn-primary">
          </div>

          <hr>
          <div id="resultat"></div>
        </form>
        <p><?= $msg; ?></p>
      </div>
    </div>
  </div>
</div>