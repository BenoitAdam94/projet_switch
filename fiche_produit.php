<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if(!isset($_GET['id_produit'])) {
	header('location:index.php');
} else { 
  // dump($_GET);
  $liste_produit = $pdo->prepare("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle  AND id_produit = :id_produit");
  $liste_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
  $liste_produit->execute();

  $produit = $liste_produit->fetch(PDO::FETCH_ASSOC);


  $liste_avis = $pdo->prepare("SELECT note FROM avis WHERE id_salle = :id_salle");
  $liste_avis->bindParam(':id_salle', $produit["id_salle"], PDO::PARAM_STR);
  $liste_avis->execute();

  $avis = $liste_avis->fetch(PDO::FETCH_ASSOC);
}





//$liste_produit = $pdo->query("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle ORDER BY date_arrivee");

//$avis_produit = $pdo->query("SELECT note FROM avis");


// dump($produit);
dump($avis);

include 'inc/header.php';
include 'inc/navbar.php';
?>


  <!-- Page Content -->
  <div class="container">
    <!-- 1 rst row -->
    <div class="row">
      <div class="col-10">
      <h2><?= $produit['titre'] ?> avis : <?= $avis['note'] ?>/10</h2>
      </div>
      <div class="col-2 text-center">
      <h3><a href="reservation.php">Reserver</a></h3>
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
      <p>Capacité : <?= $produit['capacite'] ?> personnes</p>
      <p>Catégorie : <?= $produit['categorie'] ?></p>
      </div>
      <div class="col-4">
      <p>Adresse : <?= $produit['adresse'] ?> <?= $produit['cp'] ?> <?= $produit['ville'] ?></p>
      <p>Tarif : <?= $produit['prix'] ?> €</p>
      </div>

      </div>
    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

<?php

include "inc/footer.php";
?>