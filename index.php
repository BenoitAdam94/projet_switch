<?php 
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';
$debug = 0;
include 'inc/tools.php';

// récupération des catégories en BDD
$liste_categorie = $pdo->query("SELECT DISTINCT categorie FROM article ORDER BY categorie");

$liste_couleur = $pdo->query("SELECT DISTINCT couleur FROM article ORDER BY couleur");

// Récupération des articles en BDD
if(isset($_GET['categorie'])) {
	$choix_categorie = $_GET['categorie'];
	$liste_article = $pdo->prepare("SELECT * FROM article WHERE categorie = :categorie ORDER BY titre");
	$liste_article->bindParam(':categorie', $choix_categorie, PDO::PARAM_STR);
	$liste_article->execute();	
	
} elseif(isset($_GET['couleur'])) {
	$choix_couleur = $_GET['couleur'];
	$liste_article = $pdo->prepare("SELECT * FROM article WHERE couleur = :couleur ORDER BY titre");
	$liste_article->bindParam(':couleur', $choix_couleur, PDO::PARAM_STR);
	$liste_article->execute();	
} else {
	$liste_article = $pdo->query("SELECT * FROM article ORDER BY titre");
}


include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

	<div class="starter-template">
		<h1><i class="fas fa-couch" style="color: #4c6ef5;"></i> Salles <i class="fas fa-couch" style="color: #4c6ef5;"></i></h1>
		<p class="lead"><?php echo $msg; ?></p>
	</div>

	<div class="row">
		<div class="col-3">
			<!-- Récupérer la liste des catégories article en BDD pour les afficher dans des liens a href="" dans une liste ul li -->
			<?php 
								
				echo '<ul class="list-group">
						<li class="list-group-item active">Catégories</li>';
						
				echo '<li class="list-group-item"><a href="' . URL . '">Tous les produits</a></li>';		
						
				while($categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC)) {
					// echo '<pre>'; var_dump($categorie); echo '</pre><hr>';
					echo '<li class="list-group-item"><a href="?categorie=' . $categorie['categorie'] . '">' . $categorie['categorie'] . '</a></li>';
					
					/*
					echo '<li class="list-group-item">';
					
					echo '<a href="?categorie=' . $categorie['categorie'] . '">' . $categorie['categorie'] . '</a>';
					
					echo '</li>';
					*/
				}		
				
				
				echo '</ul>';
				
				
				echo '<hr>';
				
				echo '<ul class="list-group">
						<li class="list-group-item active">Couleurs</li>';
						
				echo '<li class="list-group-item"><a href="' . URL . '">Tous les produits</a></li>';		
						
				while($couleur = $liste_couleur->fetch(PDO::FETCH_ASSOC)) {
					// echo '<pre>'; var_dump($categorie); echo '</pre><hr>';
					echo '<li class="list-group-item"><a href="?couleur=' . $couleur['couleur'] . '">' . $couleur['couleur'] . '</a></li>';

				}		
				
				
				echo '</ul>';
				
				
			?>
			
			
			
		</div>
		<div class="col-9">
			<div class="row justify-content-around">
			<?php 
				// affichage des articles
				while($article = $liste_article->fetch(PDO::FETCH_ASSOC)) {
					// echo '<pre>'; var_dump($article); echo '</pre><hr>';
					echo '<div class="col-sm-3 text-center p-2">';	
					
					echo '<h5>' . $article['titre'] . '</h5>';
					
					echo '<img src="' . URL . 'img/' . $article['photo'] . '" alt="' . $article['titre'] . '" class="img-thumbnail w-100">';
					
					// Afficher la catégorie, le prix.
					echo '<p>Catégorie : <b>' . $article['categorie'] . '</b><br>';
					echo 'Prix : <b>' . $article['prix'] . '€</b></p>';
					
					// bouton voir la fiche article
					echo '<a href="fiche_article.php?id_article=' . $article['id_article'] . '" class="btn btn-primary w-100">Fiche article</a><hr>';
					
					echo '</div>';					
				}
			
			?>
			</div>
		</div>
	</div>


<?php 
include 'inc/footer.inc.php';