<?php 
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';
$debug = 0;
include 'inc/tools.php';

if(!isset($_GET['id_article'])) {
	header('location:index.php');
}

$infos_article = $pdo->prepare("SELECT * FROM article WHERE id_article = :id_article");
$infos_article->bindParam(':id_article', $_GET['id_article'], PDO::PARAM_STR);
$infos_article->execute();

if($infos_article->rowCount() < 1) {
	header('location:index.php');
}

$article = $infos_article->fetch(PDO::FETCH_ASSOC);

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
//echo '<pre>'; var_dump($article); echo '</pre>';
?>

	<div class="starter-template">
		<h1><i class="fas fa-ghost" style="color: #4c6ef5;"></i> <?php echo $article['titre']; ?> <i class="fas fa-ghost" style="color: #4c6ef5;"></i></h1>
		<p class="lead"><?php echo $msg; ?></p>
	</div>

	<div class="row">
		<div class="col-6">
			<ul class="list-group">
				<li class="list-group-item active"><b><?php echo $article['titre']; ?></b></li>
				<li class="list-group-item">Référence : <b><?php echo $article['reference']; ?></b></li>
				<li class="list-group-item">Catégorie : <b><?php echo $article['categorie']; ?></b></li>
				<li class="list-group-item">Couleur : <b><?php echo $article['couleur']; ?></b></li>
				<li class="list-group-item">Taille : <b><?php echo $article['taille']; ?></b></li>
				<li class="list-group-item">Sexe : <b><?php echo $article['sexe']; ?></b></li>
				
				<?php if($article['stock'] > 0) { ?>
				
				<li class="list-group-item">Stock : <b><?php echo $article['stock']; ?></b></li>
				
				<?php } else { ?>
				
				<li class="list-group-item"><span class="text-danger">Rupture de stock pour cet article</span></li>
				
				<?php } ?>
				
				<li class="list-group-item">Prix : <b><?php echo $article['prix']; ?></b>€</li>
				<li class="list-group-item">Description : <?php echo $article['description']; ?></li>
			</ul>
			
		</div>
		<div class="col-6">
			<?php if($article['stock'] > 0) { ?>
			<form method="post" action="panier.php">
				<input type="hidden" name="id_article" value="<?php echo $article['id_article']; ?>">
				<div class="form-row">
					<div class="col">
						<select name="quantite" class="form-control">
						<?php
							for($i = 1; $i <= $article['stock'] && $i <= 5; $i++) {
								echo '<option>' . $i . '</option>';
							}
						?>
						</select>
					</div>
					<div class="col">
						<button type="submit" class="btn btn-primary w-100" name="ajouter_au_panier">Ajouter au panier</button>
					</div>
				</div>
			</form>
			<hr>
			<?php } ?>
			
			<img src="<?php echo URL . 'img/' . $article['photo']; ?>" alt="<?php echo $article['titre']; ?>" class="w-100 img-thumbnail">
		</div>
	</div>


<?php 
include 'inc/footer.inc.php';