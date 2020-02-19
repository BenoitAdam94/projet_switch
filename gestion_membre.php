<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if (!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}

//*********************************************************************
//*********************************************************************
// SUPPRESSION D'UN MEMBRE
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_membre'])) {
	$suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
	$suppression->bindParam(":id_membre", $_GET['id_membre'], PDO::PARAM_STR);
	$suppression->execute();

	$_GET['action'] = 'affichage'; // pour provoquer l'affichage du tableau

}

//*********************************************************************
//*********************************************************************
// \FIN SUPPRESSION D'UN MEMBRE
//*********************************************************************
//*********************************************************************


$id_membre = ''; // pour la modification
$pseudo = "";
$mdp = "";
$nom = "";
$prenom = "";
$email = "";
$civilite = "";
$statut = "";
$date = new DateTime();
$date_enregistrement = $date->format('Y-m-d H:i:s');

$msg = '';







//*********************************************************************
//*********************************************************************
// ENREGISTREMENT & MODIFICATION DES MEMBRES
//*********************************************************************
//*********************************************************************
if (
	isset($_POST['pseudo']) &&
	isset($_POST['nom']) &&
	isset($_POST['prenom']) &&
	isset($_POST['email']) &&
	isset($_POST['civilite']) &&
	isset($_POST['statut'])
) {

  

  $id_membre = trim($_POST['id_membre']);
	$pseudo = trim($_POST['pseudo']);
	$nom = trim($_POST['nom']);
	$prenom = trim($_POST['prenom']);
	$email = trim($_POST['email']);
	$civilite = trim($_POST['civilite']);
	$statut = trim($_POST['statut']);

  dump($_POST);


  if(empty($pseudo) || empty($nom) || empty($prenom) || empty($prenom) || empty($email)) {
    $msg .= 'Un champ est vide';
  }
	

  if (empty($msg)) {
    js('if empty msg');

		if (!empty($id_membre)) {
      // si id_membre existe un UPDATE
      js('if id_membre ELSE update');
			
			$enregistrement = $pdo->prepare("UPDATE membre SET pseudo = :pseudo, mdp = :mdp, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut, date_enregistrement = :date_enregistrement WHERE id_membre = :id_membre");
			$enregistrement->bindParam(":id_membre", $id_membre, PDO::PARAM_STR);
      
		} else {
      // sinon un INSERT
      js('if empty msg ELSE insert');
			$mdp = trim($_POST['mdp']);
      $mdp = password_hash($mdp, PASSWORD_DEFAULT);
			$enregistrement = $pdo->prepare("INSERT INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (NULL, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, :date_enregistrement)");
      $enregistrement->bindParam(":mdp", $mdp, PDO::PARAM_STR);
		}



		$enregistrement->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
		$enregistrement->bindParam(":nom", $nom, PDO::PARAM_STR);
		$enregistrement->bindParam(":prenom", $prenom, PDO::PARAM_STR);
		$enregistrement->bindParam(":email", $email, PDO::PARAM_STR);
		$enregistrement->bindParam(":civilite", $civilite, PDO::PARAM_STR);
		$enregistrement->bindParam(":statut", $statut, PDO::PARAM_STR);
		$enregistrement->bindParam(":date_enregistrement", $date_enregistrement, PDO::PARAM_STR);
		$enregistrement->execute();
	}

}



//*********************************************************************
//*********************************************************************
// MODIFICATION : RECUPERATION DES INFOS DE L'ARTICLE EN BDD
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_membre'])) {

	$infos_membre = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
	$infos_membre->bindparam(":id_membre", $_GET['id_membre'], PDO::PARAM_STR);
	$infos_membre->execute();

	if ($infos_membre->rowCount() > 0) {
		$membre_actuel = $infos_membre->fetch(PDO::FETCH_ASSOC);

		$id_membre = $membre_actuel['id_membre'];
		$pseudo = $membre_actuel['pseudo'];
		$mdp = $membre_actuel['mdp'];
		$nom = $membre_actuel['nom'];
		$prenom = $membre_actuel['prenom'];
		$email = $membre_actuel['email'];
		$civilite = $membre_actuel['civilite'];
		$statut = $membre_actuel['statut'];
	}
}


include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">
    <div class="col-12 text-center">
      <h2>Gestion des membres</h2>

      <table>
        <tr>
          <th>id_membre</th>
          <th>pseudo</th>
          <th>nom</th>
          <th>prenom</th>
          <th>e-mail</th>
          <th>civilite</th>
          <th>statut</th>
          <th>date_enregistrement</th>
          <th>Actions</th>
        </tr>

        <?php
        $liste_membre = $pdo->query("SELECT * FROM membre");

        while ($membre = $liste_membre->fetch(PDO::FETCH_ASSOC)) {
          // on récupère les membres en bdd




          echo '<tr>';
          echo '<td>' . $membre['id_membre'] . '</td>';
          echo '<td>' . $membre['pseudo'] . '</td>';
          echo '<td>' . $membre['nom'] . '</td>';
          echo '<td>' . $membre['prenom'] . '</td>';
          echo '<td>' . $membre['email'] . '</td>';
          echo '<td>' . $membre['civilite'] . '</td>';
          echo '<td>' . $membre['statut'] . '</td>';
          echo '<td>' . $membre['date_enregistrement'] . '</td>';
          echo '<td>';
          echo '<a title="Modifier" href="gestion_membre.php?action=modifier&id_membre=' . $membre['id_membre'] . '">';
          echo '<i class="fas fa-exchange-alt fa-2x"></i></a> ';

          echo '<a title="Génerer Mot de passe" ';
          echo 'href="gestion_membre.php?action=newpassword&id_membre=' . $membre['id_membre'] . '&pseudo=' . $membre['pseudo'] . '">';
          echo '<i class="fas fa-key fa-2x"></i></a> ';

          echo '<a title="Supprimer" href="gestion_membre.php?action=supprimer&id_membre=' . $membre['id_membre'] . '">';
          echo '<i class="fas fa-trash-alt fa-2x"></i></a>';
          echo '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </div>

    <div class="col-12 text-center">
      <h2>Ajouter un membre</h3>
        <p class="lead red"><?php echo $msg; ?></p>
    </div>
  </div>


  <!-- récupération de l'id_membre pour la modification -->

  <form method="post" action="gestion_membre.php" enctype="multipart/form-data">
    <div class="row">

      <div class="col-6">
        <input type="hidden" name="id_membre" value="<?= $id_membre; ?>">



        <!-- Pseudo -->
        <div class="form-group">
          <label for="pseudo">Pseudo</label>
          <input type="text" name="pseudo" id="pseudo" value="<?= $pseudo; ?>" class="form-control">
        </div>
        <!-- Mot de passe -->
        <?php if(empty($mdp)) { ?>
        <div class="form-group">
          <label for="motdepasse">Mot de passe</label>
          <input type="text" name="mdp" id="mdp" value="<?= $mdp; ?>" class="form-control">
        </div>
        <?php } ?>

        <!-- Nom -->
        <div class="form-group">
          <label for="nom">Nom</label>
          <input type="text" name="nom" id="nom" value="<?= $nom; ?>" class="form-control">
        </div>
        <!-- Prenom -->
        <div class="form-group">
          <label for="prenom">Prenom</label>
          <input type="text" name="prenom" id="prenom" value="<?= $prenom ?>" class="form-control">
        </div>
      </div>
      <div class="col-6">
        <!-- Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" value="<?= $email ?>" class="form-control">
        </div>
        <!-- Civilite -->
        <div class="from-group">
          <label for="civilite">Civilite</label>
          <select name="civilite" id="civilite" class="form-control">
            <option value="m">Homme</option>
            <option value="f">Femme</option>
          </select>
        </div>
        <!-- Statut -->
        <div class="from-group">
          <label for="statut">Statut</label>
          <select name="statut" id="statut" class="form-control">
            <option value="1">Membre</option>
            <option value="2">Admin</option>
          </select>          
        </div>
        <br>
        <!-- Submit -->
        <div class="form-group">
          <button type="submit" name="enregistrement" id="enregistrement" class="form-control btn btn-outline-dark">Enregistrer</button>
        </div>

      </div>


    </div>
  </form>

</div>





</div>
<!-- /.row -->

</div>
<!-- /.container -->

<?php


//*********************************************************************
//*********************************************************************
// Generation d'un nouveau mot de passe
//*********************************************************************
//*********************************************************************


if (isset($_GET['action']) && $_GET['action'] == 'newpassword' && !empty($_GET['id_membre'])) {

  $newpassword = genererChaineAleatoire(8);
  
  // dump($newpassword);  
  
  alertnewmdp($_GET['pseudo'], $newpassword);
  // alert($newpassword);
  $mdp = password_hash($newpassword, PASSWORD_DEFAULT);
  $update_password = $pdo->prepare("UPDATE membre SET mdp = :mdp WHERE id_membre = :id_membre");
  $update_password->bindParam(":id_membre", $_GET['id_membre'], PDO::PARAM_STR);
  $update_password->bindParam(":mdp", $mdp, PDO::PARAM_STR);
  $update_password->execute();
  $_GET['action'] = 'affichage';

}


include "inc/footer_script.php";
include "inc/footer.php";
?>