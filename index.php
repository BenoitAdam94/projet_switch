<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';



// $liste_salle = $pdo->query("SELECT * FROM salle ORDER BY titre");

$liste_produit = $pdo->query("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle ORDER BY date_arrivee");

$avis_produit = $pdo->query("SELECT note FROM avis");



include 'inc/header.php';
include 'inc/navbar.php';
?>


  <!-- Page Content -->
  <div class="container">

    <div class="row">

      <div class="col-lg-3">

        <h3 class="my-4">Catégorie</h3>
        <div class="list-group">
          <a href="#" class="list-group-item">Réunion</a>
          <a href="#" class="list-group-item">Bureau</a>
          <a href="#" class="list-group-item">Formation</a>
        </div>
        <h3 class="my-4">Ville</h3>
        <div class="list-group">
          <a href="#" class="list-group-item">Paris</a>
          <a href="#" class="list-group-item">Lyon</a>
          <a href="#" class="list-group-item">Toulouse</a>
        </div>
        <h3 class="my-4">Capacité</h3>
        <div class="list-group">
          <a href="#" class="list-group-item">Petite (- de 6)</a>
          <a href="#" class="list-group-item">Moyenne (6 a 12)</a>
          <a href="#" class="list-group-item">Grande (12 et +)</a>
        </div>
        <h3 class="my-4">Prix</h3>
        <div class="list-group">
          <a href="#" class="list-group-item">Eco (- de 500€)</a>
          <a href="#" class="list-group-item">Normale (500€ a 1000€))</a>
          <a href="#" class="list-group-item">Deluxe (1000€ et +)</a>
        </div>
        <h3 class="my-4">Période</h3>
        <div class="list-group">
          <a href="#" class="list-group-item">Hiver</a>
          <a href="#" class="list-group-item">Printemps</a>
          <a href="#" class="list-group-item">Eté</a>
          <a href="#" class="list-group-item">Automne</a>
        </div>

      </div>
      <!-- /.col-lg-3 -->

      <div class="col-lg-9">

        <br>

        <div class="row">

          <?php
          while($produit = $liste_produit->fetch(PDO::FETCH_ASSOC)) {

          

          ?>

          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
              <a href="fiche_produit.php"><img class="card-img-top" src="img/<?= $produit['photo'] ?>" alt="<?= $produit['photo'] ?>"></a>
              <div class="card-body">
                <h4 class="card-title">
                  <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>"><?= $produit['titre'] ?></a>
                </h4>
                <h5><?= $produit['prix'] ?> €</h5>
                <p class="card-text"><?= $produit['date_arrivee'] ?></p>
              </div>
              <div class="card-footer">
                <small class="text-muted">&#9733; avis indisponible</small>
                
              </div>
            </div>
          </div>

          <?php
          }
          ?>
          


        </div>
        <!-- /.row -->

      </div>
      <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

<?php

include "inc/footer.php";
?>