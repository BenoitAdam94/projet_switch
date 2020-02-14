<?php
// fonction pour savoir si l'utilisateur est connecté
function user_is_connect() {
	if(!empty($_SESSION['membre'])) {
		return true; // utilisateur est connecté
	}
	return false; // utilisateur n'est pas connecté
}


// fonction pour savoir si l'utilisateur a le statut d'admin
function user_is_admin() {
	if(user_is_connect() && $_SESSION['membre']['statut'] == 2) {
		// si l'utilisateur est connecté et que son statut est égal à 2 alors il est admin
		return true;
	} else {
		return false;
	}
}

// Fonction pour créer le panier
function creation_panier() {
	if(!isset($_SESSION['panier'])) {
		// si l'indice panier n'existe pas dans la session, on le crée, sinon rien.
		$_SESSION['panier'] = array();
		$_SESSION['panier']['id_article'] = array();
		$_SESSION['panier']['titre'] = array();
		$_SESSION['panier']['prix'] = array();
		$_SESSION['panier']['quantite'] = array();
	}
}

// Fonction pour ajouter un article au panier
function ajout_panier($id_article, $quantite, $prix, $titre) {
	// si un article existe déjà dans le panier, on ne change que sa quantité sinon on le rajoute.
	
	// on vérifie si l'id_article est déjà présent dans le sous tableau $_SESSION['panier']['id_article']
	// array_search() cherche une informations dans les valeurs d'un tableau ARRAY et nous renvoie son indice ou false. Ensuite grace à l'indice on modifira la quantité
	$position_article = array_search($id_article, $_SESSION['panier']['id_article']);
	
	if($position_article !== false) {
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
function retirer_article($id_article) {
	$position_article = array_search($id_article, $_SESSION['panier']['id_article']);
	
	if($position_article !== false) {
		// array_splice() permet d'enlever un élément d'un tableau array mais aussi de réordonner les indices du tableau pour ne pas avoir de trou.
		array_splice($_SESSION['panier']['id_article'], $position_article, 1);
		array_splice($_SESSION['panier']['titre'], $position_article, 1);
		array_splice($_SESSION['panier']['prix'], $position_article, 1);
		array_splice($_SESSION['panier']['quantite'], $position_article, 1);
	}
}

// fonction pour calculer le montant total du panier
function total_panier() {
	$total = 0;
	for($i = 0; $i < count($_SESSION['panier']['id_article']); $i++) {
		$total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
	}
	// $total = $total * 1.2; // pour appliquer la tva à 20%
	return round($total, 2);
}

js('fonction_ok');






