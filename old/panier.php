<?php 
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';
$debug = 0;
include 'inc/tools.php';

// vider le panier
if(isset($_GET['action']) && $_GET['action'] == 'vider') {
	unset($_SESSION['panier']);
}
//*****************
// Payer le panier
//*****************
if(isset($_GET['action']) && $_GET['action'] == 'payer' && !empty($_SESSION['panier']['titre'])) {
	
	// variable de controle des erreurs:
	$erreur = 0;
	
	// il faut vérifier le stock restant en BDD avec la quantité demandée pour chaque article du panier.
	for($i = 0; $i < count($_SESSION['panier']['titre']); $i++) {
		// récupération des informations de l'article dans la BDD pour vérifier le stock
		$verif_stock = $pdo->prepare("SELECT * FROM article WHERE id_article = :id_article");
		$verif_stock->bindParam(":id_article", $_SESSION['panier']['id_article'][$i], PDO::PARAM_STR);
		$verif_stock->execute();
		
		$infos_stock = $verif_stock->fetch(PDO::FETCH_ASSOC);
		
		if($infos_stock['stock'] < $_SESSION['panier']['quantite'][$i]) {
			// cas d'erreur, soit le stock est à zéro, soit il en reste mais moins que la quantité demandée.
			$erreur = 1;
			if($infos_stock['stock'] > 0) {
				// il y a du stock mais moins que la quantité demandée
				$_SESSION['panier']['quantite'][$i] = $infos_stock['stock'];
				$msg .= '<div class="alert alert-danger mt-3">Le stock de l\'article n° ' .$_SESSION['panier']['id_article'][$i] . ' est insuffisant, nous avons changé la quantité de votre panier. <br><b>Veuillez vérifier votre panier</b></div>';
			} else {
				// stock à zéro
				$msg .= '<div class="alert alert-danger mt-3">Rupture de stock pour l\'article n° ' .$_SESSION['panier']['id_article'][$i] . ', nous avons retiré l\'article de votre panier. <br><b>Veuillez vérifier votre panier</b></div>';					
				retirer_article($_SESSION['panier']['id_article'][$i]);
				
				// on enlève 1 à la valeur de $i car cette variable représente l'indice du tableau actuellement testé. La fonction retirer_article() enlève un élément du tableau et réordonne le tableau pour combler le trou.
				// Donc si on enlève l'élément qui a l'indice 2 dans le tableau, l'élément suivant qui avait l'indice 3 aura désormais l'indice 2
				// on st donc obligé d'enlever 1 à $i pour bien controler tous les éléments.
				$i--;
				 
			}
			
		}
	}// fin de la boucle for pour controler les stocks
	
	if($erreur != 1) {
		// s'il n'y a pas eu d'erreur sur les controles des stocks
		
		// enregistrement de la commande en BDD
		$pdo->query("
				INSERT INTO commande (id_membre, montant, date) 
				VALUES (
					" . $_SESSION['membre']['id_membre'] . ", 
					" . total_panier() . ", 
					NOW())");
		
		$id_commande = $pdo->lastInsertId(); // on récupère l'id de la commande qui vient d'être créée.
		
for($i = 0; $i < count($_SESSION['panier']['prix']); $i++) {
	
	$id_en_cours = $_SESSION['panier']['id_article'][$i];
	$quantite_en_cours = $_SESSION['panier']['quantite'][$i];
	$prix_en_cours = $_SESSION['panier']['prix'][$i];
	
	$pdo->query("INSERT INTO details_commande (id_commande, id_article, quantite, prix) VALUES ($id_commande, $id_en_cours, $quantite_en_cours, $prix_en_cours)");
	
	// mise à jour des stocks
	$pdo->query("UPDATE article SET stock = stock - $quantite_en_cours WHERE id_article = $id_en_cours");
	
}
// on vide le panier.
unset($_SESSION['panier']);


	}	
	
} // fin du if $_GET['action'] == 'payer'


//*****************
// FIN Payer le panier
//*****************


// creation du panier
creation_panier();

// récupération des informations de l'article (prix) dans la BDD
if(!empty($_POST['id_article']) && is_numeric($_POST['id_article']) && !empty($_POST['quantite']) && $_POST['quantite'] > 0) {
	$recup_infos = $pdo->prepare("SELECT * FROM article WHERE id_article = :id_article");
	$recup_infos->bindParam(":id_article", $_POST['id_article'], PDO::PARAM_STR);
	$recup_infos->execute();
	
	if($recup_infos->rowCount() > 0) {
		// ajout de l'article dans le panier
		$infos = $recup_infos->fetch(PDO::FETCH_ASSOC);
		
		ajout_panier($_POST['id_article'], $_POST['quantite'], $infos['prix'], $infos['titre']);
	}	
}


include 'inc/header.inc.php';
include 'inc/nav.inc.php';
// echo '<pre>'; var_dump($_POST); echo '</pre>';
echo '<pre>'; var_dump($_SESSION['panier']); echo '</pre>';
?>

	<div class="starter-template">
		<h1><i class="fas fa-briefcase" style="color: #4c6ef5;"></i> Reservation <i class="fas fa-briefcase" style="color: #4c6ef5;"></i></h1>
		<p class="lead"><?php echo $msg; ?></p>
	</div>

	<div class="row">
		<div class="col-12">
			<p>Nombre d'article : <?php echo count($_SESSION['panier']['id_article']); ?></p>
			<hr>
			<table class="table table-bordered w-75 mx-auto">
				<tr>
					<th>N° article</th>
					<th>Titre</th>
					<th>Quantité</th>
					<th>Prix unitaire</th>
				</tr>
<?php 
	if(empty($_SESSION['panier']['prix'])) {
		// le panier est vide
		echo '<tr><td colspan="4" class="text-center"><b>Votre panier est vide</b></td></tr>';
	} else {
		// on affiche les articles
		for($i = 0; $i < count($_SESSION['panier']['titre']); $i++) {
			echo '<tr>';
			
			echo '<td>' . $_SESSION['panier']['id_article'][$i] . '</td>';
			echo '<td>' . $_SESSION['panier']['titre'][$i] . '</td>';
			echo '<td>' . $_SESSION['panier']['quantite'][$i] . '</td>';
			echo '<td>' . $_SESSION['panier']['prix'][$i] . ' €</td>';
			
			echo '</tr>';
		}
		echo '<tr><td colspan="4">Montant total du panier : ' . total_panier() . ' €</td></tr>';
		// bouton vider le panier
		echo '<tr><td colspan="4"><a href="?action=vider" class="w-100 btn btn-danger">Effacer les reservations</a></td></tr>';
		
		// rajouter une ligne dans le tableau : 
		// si l'utilisateur est connecté : un bouton "Payer le panier" (?action=payer)
		// Sinon afficher du texte : "Veuillez vous connecter ou vous inscrire pour payer le panier" // les mots connecter et inscrire doivent être des liens vers les pages concernées.
		
		if(user_is_connect()) {
			echo '<tr><td colspan="4"><a href="?action=payer" class="w-100 btn btn-success">Confirmer les reservations</a></td></tr>';
		} else {
			echo '<tr><td colspan="4">Veuillez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php">connecter</a> pour confirmer votre reservation.</td></tr>';
		}		
	}
?>
			</table>
			
		</div>
	</div>


<?php 
include 'inc/footer.inc.php';