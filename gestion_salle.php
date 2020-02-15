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
// SUPPRESSION D'UN ARTICLE
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_salle'])) {
	$suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
	$suppression->bindParam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
	$suppression->execute();

	// $_GET['action'] = 'affichage'; // pour provoquer l'affichage du tableau

}

//*********************************************************************
//*********************************************************************
// \FIN SUPPRESSION D'UN ARTICLE
//*********************************************************************
//*********************************************************************


$titre = '';
$description = '';
$photo = '';
$capacite = '';
$categorie = '';
$pays = '';
$ville = '';
$adresse = '';
$cp = '';



dump($_SESSION);



// on controle l'existence des champs du formulaire	
if(
	isset($_POST['titre']) && 
	isset($_POST['description']) && 
	isset($_POST['photo']) && 
	isset($_POST['capacite']) && 
	isset($_POST['categorie']) && 
	isset($_POST['pays']) && 
	isset($_POST['ville']) && 
	isset($_POST['adresse']) && 
	isset($_POST['cp'])) {
		
		// echo 'TEST';
		$titre = trim($_POST['titre']);
		$description = trim($_POST['description']);
		$photo = trim($_POST['photo']);
		$capacite = trim($_POST['capacite']);
		$categorie = trim($_POST['categorie']);
		$pays = trim($_POST['pays']);
		$ville = trim($_POST['ville']);
		$adresse = trim($_POST['adresse']);
		$cp = trim($_POST['cp']);
		
		$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $titre);

		if(!$verif_caractere && !empty($titre)) {
			// Message d'erreur
			$msg .= '<div class="alert alert-danger mt-3">Pseudo invalide, caractères autorisés : a-z et de 0-9</div>';			
		}
		
		// Taille pseudo entre 4 et 14
		if(iconv_strlen($titre) < 4 || iconv_strlen($titre) > 14) {
			// Message d'erreur
			$msg .= '<div class="alert alert-danger mt-3">Pseudo invalide, le pseudo doit avoir entre 4 et 14 caractères inclus</div>';	
		}
		
		// Format de l'email
		
		
		// S'il n'y pas eu d'erreur au préalable, on doit vérifier si le pseudo existe déjà dans la BDD
		if(empty($msg)) {
			// si la variable $msg est vide, alors il n'y a pas eu d'erreur dans nos controles.
			
			// on vérifie si le titre est disponible.
			$verif_titre = $pdo->prepare("SELECT * FROM salle WHERE titre = :titre");
			$verif_titre->bindParam(":titre", $titre, PDO::PARAM_STR);
			$verif_titre->execute();
			
			if($verif_titre->rowCount() > 0) {
				// si le nombre de ligne est supérieur à zéro, alors le pseudo est déjà utilisé.
				$msg .= '<div class="alert alert-danger mt-3">Cette salle existe déjà</div>';	
			} else {
				
				
				// On déclenche l'insertion
				$enregistrement = $pdo->prepare("INSERT INTO salle (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (NULL, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
				$enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
				$enregistrement->bindParam(':description', $description, PDO::PARAM_STR);
				$enregistrement->bindParam(':photo', $photo, PDO::PARAM_STR);
				$enregistrement->bindParam(':pays', $pays, PDO::PARAM_STR);
				$enregistrement->bindParam(':ville', $ville, PDO::PARAM_STR);
				$enregistrement->bindParam(':adresse', $adresse, PDO::PARAM_STR);
				$enregistrement->bindParam(':cp', $cp, PDO::PARAM_STR);
				$enregistrement->bindParam(':capacite', $capacite, PDO::PARAM_STR);
				$enregistrement->bindParam(':categorie', $categorie, PDO::PARAM_STR);
				$enregistrement->execute();
			}			
			
		}		
	
} else {
  $msg .= 'Veuillez remplir le formulaire';		
}


include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">
    <div class="col-12 text-center">
      <h2>Gestion des salles</h2>

      <table>
        <tr>
          <th>id_salle</th>
          <th>titre</th>
          <th>description</th>
          <th>photo</th>
          <th>pays</th>
          <th>ville</th>
          <th>adresse</th>
          <th>CP</th>
          <th>Capacite</th>
          <th>Categorie</th>
          <th>Actions</th>
        </tr>

        <?php
        $liste_salle = $pdo->query("SELECT * FROM salle");

        while ($salle = $liste_salle->fetch(PDO::FETCH_ASSOC)) {
          // on récupère les articles en bdd




          echo '<tr>';
          echo '<td>' . $salle['id_salle'] . '</td>';
          echo '<td>' . $salle['titre'] . '</td>';
          echo '<td>' . $salle['description'] . '</td>';
          echo '<td><img src="img/' . $salle['photo'] . '" width="100px";> </td>';
          echo '<td>' . $salle['pays'] . '</td>';
          echo '<td>' . $salle['ville'] . '</td>';
          echo '<td>' . $salle['adresse'] . '</td>';
          echo '<td>' . $salle['cp'] . '</td>';
          echo '<td>' . $salle['capacite'] . '</td>';
          echo '<td>' . $salle['categorie'] . '</td>';
          echo '<td>';
          echo '<a href="gestion_salle.php?action=modifier&id_salle=' . $salle['id_salle'] . '">';
          echo '<i class="fas fa-exchange-alt"></i></a> ';
          echo '<a href="gestion_salle.php?action=supprimer&id_salle=' . $salle['id_salle'] . '">';
          echo '<i class="fas fa-trash-alt"></i></a>';
          echo '</td>';
          echo '</td>';
        }
        ?>
      </table>
    </div>

    <div class="col-12 text-center">
      <h2>Ajouter une salle</h3>
      <p class="lead"><?php echo $msg; ?></p>
    </div>
  </div>

  

  <!-- enctype="multipart/form-data" -->

  <!-- récupération de l'id_article pour la modification -->

  <form method="post" action="">
    <div class="row">


      <div class="col-6">
        <input name="id_article" value="<?= '$id_article' ?>">
        <div class="form-group">
          <label for="titre">Titre</label>
          <input type="text" name="titre" id="titre" value="<?= $titre; ?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea name="description" id="description" rows="2" class="form-control"><?= $description; ?></textarea>
        </div>
        <div class="form-group">
          <label for="photo">Photo</label>
          <input type="file" name="photo" id="photo" class="form-control">
        </div>
        <div class="from-group">
          <label for="capacite">Capacité</label>
          <select name="capacite" id="capacite" class="form-control">
          <script>
              for(i=1;i<51;i++) {
                document.write('<option value="' + i + '">' + i + '</option>');
              }
            </script>
          </select>
        </div>
        <div class="from-group">
          <label for="categorie">Categorie</label>
          <select name="categorie" id="categorie" class="form-control">
            <option>bureau</option>
            <option>reunion</option>
            <option>formation</option>
          </select>
        </div>
      </div>
      <div class="col-6">
        <div class="from-group">
          <label for="pays">Pays</label>
          <select name="pays" id="pays" class="form-control">
            <option value="france">France</option>
            <option value="corse">Corse</option>
            <option value="dom">DOM</option>
          </select>
        </div>
        <div class="from-group">
          <label for="ville">Ville</label>
          <select name="ville" id="ville" class="form-control">
            <option value="paris">Paris</option>
            <option value="lyon">Lyon</option>
            <option value="marseille">Marseille</option>
          </select>
        </div>
        <div class="from-group">
          <label for="adresse">Adresse</label>
          <textarea name="adresse" id="adresse" rows="2" class="form-control"><?= $adresse; ?></textarea>
        </div>
        <div class="form-group">
          <label for="cp">Code Postal</label>
          <input type="cp" name="cp" id="cp" value="<?= $cp; ?>" class="form-control">
        </div>
        <div class="form-group">
          <button type="submit" name="enregistrement" id="enregistrement" class="form-control btn btn-outline-dark">Enregistrer </button>
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

include "inc/footer.php";
?>