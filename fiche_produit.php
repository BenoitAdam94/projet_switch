<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if(!isset($_GET['id_produit'])) {
	header('location:index.php');
} else { dump($_GET); }


$liste_produit = $pdo->prepare("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle");
$liste_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
$liste_produit->execute();


//$liste_produit = $pdo->query("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle ORDER BY date_arrivee");

//$avis_produit = $pdo->query("SELECT note FROM avis");

$produit = $liste_produit->fetch(PDO::FETCH_ASSOC);

dump($produit);









include 'inc/header.php';
include 'inc/navbar.php';
?>


  <!-- Page Content -->
  <div class="container">
    <!-- 1 rst row -->
    <div class="row">
      <div class="col-10">
      <h2><?= $produit['titre'] ?> avis : X/10</h2>
      </div>
      <div class="col-2">
      Reserver
      </div>
      <div class="col-8">
        <img src="img/<?= $produit['photo'] ?>" alt="<?= $produit['photo'] ?>">
      </div>
      <div class="col-4">
        <div>
          <h3>Description</h3>
          <p><?= $produit['description'] ?></p>
        </div>
        <div>
          <h3>localisation</h3>
          google
        </div>
      </div>
      <div class="col-12">
        Informations complémentaires :
      </div>
      <div class="col-4">
      <p>Arrivée : <?= $produit['date_arrivee'] ?></p>
      <p>Départ : <?= $produit['date_depart'] ?></p>
      </div>
      <div class="col-4">
      <p>Capacité : <?= $produit['capacite'] ?></p>
      <p>Catégorie : <?= $produit['categorie'] ?></p>
      </div>
      <div class="col-4">
      <p>Adresse : <?= $produit['adresse'] ?></p>
      <p>Tarif : <?= $produit['prix'] ?></p>
      </div>

      </div>
    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

<?php

include "inc/footer.php";
?>