<?php
include '../inc/init.inc.php';
include '../inc/fonction.inc.php';
$debug = 0;
include '../inc/tools.php';

if (!user_is_admin()) {
	header('location:' . URL . 'connexion.php');
	exit(); // bloque l'exécution du code 
}

//*********************************************************************
//*********************************************************************
// SUPPRESSION D'UN ARTICLE
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_article'])) {
	$suppression = $pdo->prepare("DELETE FROM article WHERE id_article = :id_article");
	$suppression->bindParam(":id_article", $_GET['id_article'], PDO::PARAM_STR);
	$suppression->execute();

	$_GET['action'] = 'affichage'; // pour provoquer l'affichage du tableau

}

//*********************************************************************
//*********************************************************************
// \FIN SUPPRESSION D'UN ARTICLE
//*********************************************************************
//*********************************************************************
$id_article = ''; // pour la modification
$reference = "";
$titre = "";
$categorie = "";
$couleur = "";
$taille = "";
$civilite = "";
$photo_bdd = "";
$prix = "";
$stock = "";
$description = "";




//*********************************************************************
//*********************************************************************
// ENREGISTREMENT & MODIFICATION DES ARTICLES
//*********************************************************************
//*********************************************************************
if (
	isset($_POST['id_article']) &&
	isset($_POST['reference']) &&
	isset($_POST['titre']) &&
	isset($_POST['categorie']) &&
	isset($_POST['couleur']) &&
	isset($_POST['taille']) &&
	isset($_POST['civilite']) &&
	isset($_POST['prix']) &&
	isset($_POST['stock']) &&
	isset($_POST['description'])
) {

	$id_article = trim($_POST['id_article']);
	$reference = trim($_POST['reference']);
	$titre = trim($_POST['titre']);
	$categorie = trim($_POST['categorie']);
	$couleur = trim($_POST['couleur']);
	$taille = trim($_POST['taille']);
	$civilite = trim($_POST['civilite']);
	$prix = trim($_POST['prix']);
	$stock = trim($_POST['stock']);
	$description = trim($_POST['description']);

	// récupération de la photo actuelle pour les modifs
	if (!empty($_POST['photo_actuelle'])) {
		$photo_bdd = $_POST['photo_actuelle'];
	}



	if (empty($prix) || !is_numeric($prix)) {
		$msg .= '<div class="alert alert-danger mt-3">Attention, le prix est obligatoire et doit être numérique.</div>';
	}

	if ($stock !== 0 && !is_numeric($stock)) {
		$msg .= '<div class="alert alert-danger mt-3">Attention, le stock est obligatoire et doit être numérique.</div>';
	}

	// controle sur la référence car elle est unique en BDD
	$verif_reference = $pdo->prepare("SELECT * FROM article WHERE reference = :reference");
	$verif_reference->bindParam(':reference', $reference, PDO::PARAM_STR);
	$verif_reference->execute();


	// si on a une ligne, alors la reference existe en bdd
	// on ne vérifie la référence que lors d'un ajout. Si $id_article est vide alors c'est un ajout sinon c'est une modif.
	if ($verif_reference->rowCount() > 0 && empty($id_article)) {
		$msg .= '<div class="alert alert-danger mt-3">Attention, référence indisponible car déjà attribuée.</div>';
	} else {
		// vérification du format de l'image, formats accèptés : jpg, jpeg, png, gif
		// est-ce qu'une image a été posté : 
		if (!empty($_FILES['photo']['name'])) {

			// on vérifie le format de l'image en récupérant son extension
			$extension = strrchr($_FILES['photo']['name'], '.');
			// strrchr() découpe une chaine fournie en premier argument en partant de la fin. On remonte jusqu'au caractère fourni en deuxième argument et on récupère tout depuis ce caractère.
			// exemple strrchr('image.png', '.'); => on récupère .png
			// var_dump($extension);

			// on enlève le point et on passe l'extension en minuscule pour pouvoir la comparer.
			$extension = strtolower(substr($extension, 1));
			// exemple : .PNG => png    .Jpeg => jpeg

			// on déclare un tableau array contenant les extensions autorisées :
			$tab_extension_valide = array('png', 'gif', 'jpg', 'jpeg');

			// in_array(ce_quon_cherche, tableau_ou_on_cherche);
			// in_array() renvoie true si le premier argument correspond à une des valeurs présentes dans le tableau array fourni en deuxième argument. Sinon false
			$verif_extension = in_array($extension, $tab_extension_valide);

			if ($verif_extension) {

				// pour ne pasd écraser une image du même nom, on renomme l'image en rajoutant la référence qui est une information unique
				$nom_photo = $reference . '-' .  $_FILES['photo']['name'];

				$photo_bdd = $nom_photo; // représente l'insertion en BDD

				// on prépare le chemin où on va enregistrer l'image
				$photo_dossier = SERVER_ROOT . SITE_ROOT . 'img/' . $nom_photo;
				// var_dump($photo_dossier);

				// copy(); permet de copier un fichier depuis un emplacement fourni en premier argument vers un emplacement fourni en deuxième
				copy($_FILES['photo']['tmp_name'], $photo_dossier);
			} else {
				$msg .= '<div class="alert alert-danger mt-3">Attention, le format de la photo est invalide, extensions autorisées : jpg, jpeg, png, gif.</div>';
			}
		}
	}

	// on peut déclencher l'enregistrement s'il n'y a pas eu d'erreur dans les traitements précédents
	if (empty($msg)) {

		if (!empty($id_article)) {
			// si $id_article n'est pas vide c'est un UPDATE
			$enregistrement = $pdo->prepare("UPDATE article SET reference = :reference, titre = :titre, categorie = :categorie, couleur = :couleur, taille = :taille, civilite = :civilite, prix = :prix, stock = :stock, description = :description, photo = :photo WHERE id_article = :id_article");
			// on rajoute le bindParam pour l'id_article car => modification
			$enregistrement->bindParam(":id_article", $id_article, PDO::PARAM_STR);
		} else {
			// sinon un INSERT
			$enregistrement = $pdo->prepare("INSERT INTO article (reference, titre, categorie, couleur, taille, civilite, prix, stock, description, photo) VALUES (:reference, :titre, :categorie, :couleur, :taille, :civilite, :prix, :stock, :description, :photo)");
		}



		$enregistrement->bindParam(":reference", $reference, PDO::PARAM_STR);
		$enregistrement->bindParam(":titre", $titre, PDO::PARAM_STR);
		$enregistrement->bindParam(":categorie", $categorie, PDO::PARAM_STR);
		$enregistrement->bindParam(":couleur", $couleur, PDO::PARAM_STR);
		$enregistrement->bindParam(":taille", $taille, PDO::PARAM_STR);
		$enregistrement->bindParam(":civilite", $civilite, PDO::PARAM_STR);
		$enregistrement->bindParam(":prix", $prix, PDO::PARAM_STR);
		$enregistrement->bindParam(":stock", $stock, PDO::PARAM_STR);
		$enregistrement->bindParam(":description", $description, PDO::PARAM_STR);
		$enregistrement->bindParam(":photo", $photo_bdd, PDO::PARAM_STR);
		$enregistrement->execute();
	}
}
//*********************************************************************
//*********************************************************************
// \FIN ENREGISTREMENT DES ARTICLES
//*********************************************************************
//*********************************************************************


//*********************************************************************
//*********************************************************************
// MODIFICATION : RECUPERATION DES INFOS DE L'ARTICLE EN BDD
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_article'])) {

	$infos_article = $pdo->prepare("SELECT * FROM article WHERE id_article = :id_article");
	$infos_article->bindparam(":id_article", $_GET['id_article'], PDO::PARAM_STR);
	$infos_article->execute();

	if ($infos_article->rowCount() > 0) {
		$article_actuel = $infos_article->fetch(PDO::FETCH_ASSOC);

		$id_article = $article_actuel['id_article'];
		$reference = $article_actuel['reference'];
		$titre = $article_actuel['titre'];
		$categorie = $article_actuel['categorie'];
		$couleur = $article_actuel['couleur'];
		$taille = $article_actuel['taille'];
		$civilite = $article_actuel['civilite'];
		$photo_actuelle = $article_actuel['photo'];
		$prix = $article_actuel['prix'];
		$stock = $article_actuel['stock'];
		$description = $article_actuel['description'];
	}
}

//*********************************************************************
//*********************************************************************
// \FIN MODIFICATION : RECUPERATION DES INFOS DE L'ARTICLE EN BDD
//*********************************************************************
//*********************************************************************



include '../inc/header.inc.php';
include '../inc/nav.inc.php';
// echo '<pre>'; var_dump($_POST); echo '</pre>';
// echo '<pre>'; var_dump($_SERVER); echo '</pre>';
// echo '<pre>'; var_dump($_FILES); echo '</pre>';

?>

<div class="starter-template">
	<h1><i class="fas fa-ghost" style="color: #4c6ef5;"></i> Gestion Salles <i class="fas fa-ghost" style="color: #4c6ef5;"></i></h1>
	<p class="lead"><?php echo $msg; ?></p>

	<p class="text-center">
		<a href="?action=ajouter" class="btn btn-outline-danger">Ajout article</a>
		<a href="?action=affichage" class="btn btn-outline-primary">Affichage article</a>
	</p>

</div>

<div class="row">
	<div class="col-12">

		<?php
		//***************************
		// AFFICHAGE DES ARTICLES
		//***************************
		if (isset($_GET['action']) && $_GET['action'] == 'affichage') {
			// on récupère les articles en bdd
			$liste_article = $pdo->query("SELECT * FROM article");

			echo '<p>Nombre d\'article : <b>' . $liste_article->rowCount() . '</b></p>';

			echo '<div class="table-responsive">';
			echo '<table class="table table-bordered">';
			echo '<tr>';
			echo '<th>Id article</th>';
			echo '<th>Référence</th>';
			echo '<th>Catégorie</th>';
			echo '<th>Titre</th>';
			echo '<th>Description</th>';
			echo '<th>Couleur</th>';
			echo '<th>Taille</th>';
			echo '<th>civilite</th>';
			echo '<th>Photo</th>';
			echo '<th>Prix</th>';
			echo '<th>Stock</th>';
			echo '<th>Modif</th>';
			echo '<th>Suppr</th>';
			echo '</tr>';

			while ($article = $liste_article->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr>';
				echo '<td>' . $article['id_article'] . '</td>';
				echo '<td>' . $article['reference'] . '</td>';
				echo '<td>' . $article['categorie'] . '</td>';
				echo '<td>' . $article['titre'] . '</td>';
				echo '<td>' . substr($article['description'], 0, 14) . ' ...</td>';
				echo '<td>' . $article['couleur'] . '</td>';
				echo '<td>' . $article['taille'] . '</td>';
				echo '<td>' . $article['civilite'] . '</td>';
				echo '<td><img src="' . URL . 'img/' . $article['photo'] . '" class="img-thumbnail" width="140"></td>';
				echo '<td>' . $article['prix'] . '</td>';
				echo '<td>' . $article['stock'] . '</td>';

				echo '<td><a href="?action=modifier&id_article=' . $article['id_article'] . '" class="btn btn-warning"><i class="fas fa-edit"></i></a></td>';

				echo '<td><a href="?action=supprimer&id_article=' . $article['id_article'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes-vous sûr ?\'))"><i class="fas fa-trash-alt"></i></a></td>';

				echo '</tr>';
			}

			echo '</table>';
			echo '</div>';
		}

		//***************************
		// FIN AFFICHAGE DES ARTICLES
		//***************************




		//***************************
		// FORMULAIRE D'AJOUT ARTICLE
		//***************************
		// on affiche le form si l'utilisateur a cliqué sur le bouton "Ajout article"
		if (isset($_GET['action']) && ($_GET['action'] == 'ajouter' || $_GET['action'] == 'modifier')) {

		?>

			<form method="post" action="" enctype="multipart/form-data">
				<!-- récupération de l'id_article pour la modification -->
				<input type="hidden" name="id_article" value="<?php echo $id_article; ?>">

				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="reference">Référence</label>
							<input type="text" name="reference" id="reference" value="<?php echo $reference; ?>" class="form-control">
						</div>
						<div class="form-group">
							<label for="titre">Titre</label>
							<input type="text" name="titre" id="titre" value="<?php echo $titre; ?>" class="form-control">
						</div>
						<div class="form-group">
							<label for="description">Description</label>
							<textarea name="description" id="description" rows="2" class="form-control"><?php echo $description; ?></textarea>
						</div>
						<div class="form-group">
							<label for="categorie">Catégorie</label>
							<select name="categorie" id="categorie" class="form-control">
								<option>Chemise</option>
								<option <?php if ($categorie == 'Tshirt') {
											echo 'selected';
										} ?>>Tshirt</option>
								<option <?php if ($categorie == 'Pantalon') {
											echo 'selected';
										} ?>>Pantalon</option>
								<option <?php if ($categorie == 'Caleçon') {
											echo 'selected';
										} ?>>Caleçon</option>
								<option <?php if ($categorie == 'Echarpe') {
											echo 'selected';
										} ?>>Echarpe</option>
								<option <?php if ($categorie == 'Chaussettes') {
											echo 'selected';
										} ?>>Chaussettes</option>
								<option <?php if ($categorie == 'Polo') {
											echo 'selected';
										} ?>>Polo</option>
							</select>
						</div>
						<div class="form-group">
							<label for="couleur">Couleur</label>
							<select name="couleur" id="couleur" class="form-control">
								<option>Bleu</option>
								<option <?php if ($couleur == 'Blanc') {
											echo 'selected';
										} ?>>Blanc</option>
								<option <?php if ($couleur == 'Noir') {
											echo 'selected';
										} ?>>Noir</option>
								<option <?php if ($couleur == 'Vert') {
											echo 'selected';
										} ?>>Vert</option>
								<option <?php if ($couleur == 'Rouge') {
											echo 'selected';
										} ?>>Rouge</option>
								<option <?php if ($couleur == 'Gris') {
											echo 'selected';
										} ?>>Gris</option>
								<option <?php if ($couleur == 'Rose') {
											echo 'selected';
										} ?>>Rose</option>
								<option <?php if ($couleur == 'Beige') {
											echo 'selected';
										} ?>>Beige</option>
								<option <?php if ($couleur == 'Marron') {
											echo 'selected';
										} ?>>Marron</option>
								<option <?php if ($couleur == 'Jaune') {
											echo 'selected';
										} ?>>Jaune</option>
							</select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="taille">Taille</label>
							<select name="taille" id="taille" class="form-control">
								<option>XS</option>
								<option <?php if ($taille == 'S') {
											echo 'selected';
										} ?>>S</option>
								<option <?php if ($taille == 'M') {
											echo 'selected';
										} ?>>M</option>
								<option <?php if ($taille == 'L') {
											echo 'selected';
										} ?>>L</option>
								<option <?php if ($taille == 'XL') {
											echo 'selected';
										} ?>>XL</option>
							</select>
						</div>
						<div class="form-group">
							<label for="civilite">civilite</label>
							<select name="civilite" id="civilite" class="form-control">
								<option value="m">Homme</option>
								<option value="f" <?php if ($civilite == 'f') {
														echo 'selected';
													} ?>>Femme</option>
							</select>
						</div>
						<?php
						// récupération de la photo de l'article en cas de modification. Pour la consever si l'utilisateur n'en charge pas une nouvelle
						if (!empty($photo_actuelle)) {
							echo '<div class="form-group text-center">';
							echo '<label>Photo actuelle</label><hr>';
							echo '<img src="' . URL . 'img/' . $photo_actuelle . '" class="w-25 img-thumbnail" alt="image de l\'article">';
							echo '<input type="hidden" name="photo_actuelle" value="' . $photo_actuelle . '">';
							echo '</div>';
						}
						?>


						<div class="form-group">
							<label for="photo">Photo</label>
							<input type="file" name="photo" id="photo" class="form-control">
						</div>
						<div class="form-group">
							<label for="prix">Prix</label>
							<input type="text" name="prix" id="prix" value="<?php echo $prix; ?>" class="form-control">
						</div>
						<div class="form-group">
							<label for="stock">Stock</label>
							<input type="text" name="stock" id="stock" value="<?php echo $stock; ?>" class="form-control">
						</div>
						<div class="form-group">
							<button type="submit" name="enregistrement" id="enregistrement" class="form-control btn btn-outline-dark"> Enregistrement </button>
						</div>
					</div>
				</div>
			</form>

		<?php
		} // fin du if(isset($_GET['action']) && $_GET['action'] == 'ajouter')
		?>


	</div>
</div>


<?php
include '../inc/footer.inc.php';
