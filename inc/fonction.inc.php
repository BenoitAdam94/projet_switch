<?php
// fonction pour savoir si l'utilisateur est connecté
function user_is_connect()
{
	if (!empty($_SESSION['membre'])) {
		return true; // utilisateur est connecté
	}
	return false; // utilisateur n'est pas connecté
}


// fonction pour savoir si l'utilisateur a le statut d'admin
function user_is_admin()
{
	if (user_is_connect() && $_SESSION['membre']['statut'] == 2) {
		// si l'utilisateur est connecté et que son statut est égal à 2 alors il est admin
		return true;
	} else {
		return false;
	}
}

// Fonction pour créer le panier
function creation_panier()
{
	if (!isset($_SESSION['panier'])) {
		// si l'indice panier n'existe pas dans la session, on le crée, sinon rien.
		$_SESSION['panier'] = array();
		$_SESSION['panier']['id_article'] = array();
		$_SESSION['panier']['titre'] = array();
		$_SESSION['panier']['prix'] = array();
		$_SESSION['panier']['quantite'] = array();
	}
}

// Fonction pour ajouter un article au panier
function ajout_panier($id_article, $quantite, $prix, $titre)
{
	// si un article existe déjà dans le panier, on ne change que sa quantité sinon on le rajoute.

	// on vérifie si l'id_article est déjà présent dans le sous tableau $_SESSION['panier']['id_article']
	// array_search() cherche une informations dans les valeurs d'un tableau ARRAY et nous renvoie son indice ou false. Ensuite grace à l'indice on modifira la quantité
	$position_article = array_search($id_article, $_SESSION['panier']['id_article']);

	if ($position_article !== false) {
		// !== strictement différent car on peut récupérer l'indice 0
		$_SESSION['panier']['quantite'][$position_article] += $quantite;
	} else {
		$_SESSION['panier']['id_article'][] = $id_article;
		$_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['prix'][] = $prix;
		$_SESSION['panier']['titre'][] = $titre;
	}
}

// fonction pour retirer un article du panier
function retirer_article($id_article)
{
	$position_article = array_search($id_article, $_SESSION['panier']['id_article']);

	if ($position_article !== false) {
		// array_splice() permet d'enlever un élément d'un tableau array mais aussi de réordonner les indices du tableau pour ne pas avoir de trou.
		array_splice($_SESSION['panier']['id_article'], $position_article, 1);
		array_splice($_SESSION['panier']['titre'], $position_article, 1);
		array_splice($_SESSION['panier']['prix'], $position_article, 1);
		array_splice($_SESSION['panier']['quantite'], $position_article, 1);
	}
}

// fonction pour calculer le montant total du panier
function total_panier()
{
	$total = 0;
	for ($i = 0; $i < count($_SESSION['panier']['id_article']); $i++) {
		$total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
	}
	// $total = $total * 1.2; // pour appliquer la tva à 20%
	return round($total, 2);
}

function verif_photo_pj()
{



	// on vérifie le format de l'image en récupérant son extension
	$extension = strrchr($_FILES['photo']['name'], '.');
	// strrchr() découpe une chaine fournie en premier argument en partant de la fin. On remonte jusqu'au caractère fourni en deuxième argument et on récupère tout depuis ce caractère.
	// exemple strrchr('image.png', '.'); => on récupère .png
	// dump($extension);

	// on enlève le point et on passe l'extension en minuscule pour pouvoir la comparer.
	$extension = strtolower(substr($extension, 1));
	// exemple : .PNG => png    .Jpeg => jpeg

	// on déclare un tableau array contenant les extensions autorisées :
	$tab_extension_valide = array('png', 'gif', 'jpg', 'jpeg');

	// in_array(ce_quon_cherche, tableau_ou_on_cherche);
	// in_array() renvoie true si le premier argument correspond à une des valeurs présentes dans le tableau array fourni en deuxième argument. Sinon false
	$verif_extension = in_array($extension, $tab_extension_valide);

	if ($verif_extension) {
		$reference = rand(0, 9999);
		// pour ne pasd écraser une image du même nom, on renomme l'image en rajoutant la référence qui est une information unique
		$nom_photo = $reference . '-' . $_FILES['photo']['name'];

		$photo_bdd = $nom_photo; // représente l'insertion en BDD

		// on prépare le chemin où on va enregistrer l'image
		$photo_dossier = 'img/' . $nom_photo;
		// dump($photo_dossier);

		// copy(); permet de copier un fichier depuis un emplacement fourni en premier argument vers un emplacement fourni en deuxième
		copy($_FILES['photo']['tmp_name'], $photo_dossier);
		info('le fichier a ete copié dans le dossier');
		
		return $nom_photo;
	} else {
		return false;
	}
}


js('fonction_ok');
