<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';



// si l'utilisateur est connecté, on le renvoie sur la page profil
if(user_is_connect()) {
	header('location:index.php');
}


$pseudo = '';
$mdp = '';
$prenom = '';
$nom = '';
$email = '';
$civilite = '';
$date = new DateTime();
$date_enregistrement = $date->format('Y-m-d H:i:s');




// on controle l'existence des champs du formulaire	
if(
	isset($_POST['pseudo']) && 
	isset($_POST['mdp']) && 
	isset($_POST['prenom']) && 
	isset($_POST['nom']) && 
	isset($_POST['email']) && 
	isset($_POST['civilite'])) {
		
		// echo 'TEST';
		$pseudo = trim($_POST['pseudo']);
		$mdp = trim($_POST['mdp']);
		$prenom = trim($_POST['prenom']);
		$nom = trim($_POST['nom']);
		$email = trim($_POST['email']);
		$civilite = trim($_POST['civilite']);
		
		$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);

		if(!$verif_caractere && !empty($pseudo)) {
			// Message d'erreur
			$msg .= '<div class="alert alert-danger mt-3">Pseudo invalide, caractères autorisés : a-z et de 0-9</div>';			
		}
		
		// Taille pseudo entre 4 et 14
		if(iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 14) {
			// Message d'erreur
			$msg .= '<div class="alert alert-danger mt-3">Pseudo invalide, le pseudo doit avoir entre 4 et 14 caractères inclus</div>';	
		}
		
		// Format de l'email
		
		
		// S'il n'y pas eu d'erreur au préalable, on doit vérifier si le pseudo existe déjà dans la BDD
		if(empty($msg)) {
			// si la variable $msg est vide, alors il n'y a pas eu d'erreur dans nos controles.
			
			// on vérifie si le pseudo est disponible.
			$verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
			$verif_pseudo->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
			$verif_pseudo->execute();
			
			if($verif_pseudo->rowCount() > 0) {
				// si le nombre de ligne est supérieur à zéro, alors le pseudo est déjà utilisé.
				$msg .= '<div class="alert alert-danger mt-3">Pseudo indisponible !</div>';	
			} else {
				// insert into
				// cryptage du mot de passe pour l'insertion en BDD
				$mdp = password_hash($mdp, PASSWORD_DEFAULT);
				
				// On déclenche l'insertion
				$enregistrement = $pdo->prepare("INSERT INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (NULL, :pseudo, :mdp, :nom, :prenom, :email, :civilite, 1, :date_enregistrement)");
				$enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
				$enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
				$enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
				$enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
				$enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
				$enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
				$enregistrement->bindParam(':date_enregistrement', $date_enregistrement, PDO::PARAM_STR);
				$enregistrement->execute();
			}			
			
		}		
	
}

include 'inc/header.php';
include 'inc/navbar.php';
?>


  <!-- Page Content -->
  <div class="container">
    <!-- 1 rst row -->
    <div class="column">
      <form method="post" action="">
      <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="mdp">Mot de passe</label>
        <input type="text" name="mdp" id="mdp" value="" class="form-control">
      </div>
      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" value="<?php echo $nom; ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="form-control">
      </div>					
      <div class="form-group">
        <label for="civilite">civilite</label>
        <select name="civilite" id="civilite" class="form-control">
          <option value="m">Homme</option>
          <option value="f" <?php if($civilite == 'f') { echo 'selected'; } ?> >Femme</option>
        </select>
      </div>		
      <div class="form-group">
        <button type="submit" name="inscription" id="inscription" class="form-control btn btn-outline-primary"> Inscription </button>
      </div>
      <!-- /.form -->
      </form>
      <p class="lead"><?php echo $msg; ?></p>

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

<?php

include "inc/footer.php";
?>