<?php
include 'inc/init.inc.php';
$debug = 0;
include 'inc/tools.php';


$tab = array();
$tab['message'] = '';
$tab['connexion'] = '';

if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {

  $pseudo = trim($_POST['pseudo']);
  $mdp = trim($_POST['mdp']);

  $verif_connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
  $verif_connexion->bindparam(':pseudo', $pseudo, PDO::PARAM_STR);
  $verif_connexion->execute();


  if ($verif_connexion->rowCount() > 0) {
    // s'il y a une ligne dans $verif_connexion alors le pseudo est bon
    $infos = $verif_connexion->fetch(PDO::FETCH_ASSOC);

    if (password_verify($mdp, $infos['mdp'])) {

      $_SESSION['membre'] = array();

      $_SESSION['membre']['id_membre'] = $infos['id_membre'];
      $_SESSION['membre']['pseudo'] = $infos['pseudo'];
      $_SESSION['membre']['nom'] = $infos['nom'];
      $_SESSION['membre']['prenom'] = $infos['prenom'];
      $_SESSION['membre']['civilite'] = $infos['civilite'];
      $_SESSION['membre']['email'] = $infos['email'];
      $_SESSION['membre']['statut'] = $infos['statut'];

      $tab['connexion'] = 'ok';
      $tab['message'] .= 'Connexion ok !';
      
    } else {
      $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';
      $tab['message'] .= 'Mot de passe incorrect';
    }
  } else {
    $msg .= '<div class="alert alert-danger mt-3">Erreur sur le pseudo et / ou le mot de passe !</div>';
    $tab['message'] .= 'Pseudo Incorrect';
  }
}

echo json_encode($tab);
