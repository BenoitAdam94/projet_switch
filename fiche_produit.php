<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if (!isset($_GET['id_produit'])) {
  header('location:index.php');
} else {
  // dump($_GET);
  $liste_produit = $pdo->prepare("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle  AND id_produit = :id_produit");
  $liste_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
  $liste_produit->execute();

  $produit = $liste_produit->fetch(PDO::FETCH_ASSOC);

  // Requete pour la moyenne notes
  $note_avis = $pdo->prepare("SELECT ROUND(AVG(note), 1) FROM avis WHERE id_salle = :id_salle");
  $note_avis->bindParam(':id_salle', $produit["id_salle"], PDO::PARAM_STR);
  $note_avis->execute();

  // Requête et mise dans un array
  $note = $note_avis->fetch(PDO::FETCH_ASSOC);
  // simplification dans une variable simple
  $note_moyenne = $note['ROUND(AVG(note), 1)'];
  
  
  
}





//$liste_produit = $pdo->query("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle ORDER BY date_arrivee");

//$avis_produit = $pdo->query("SELECT note FROM avis");


// dump($produit);
// dump($note);

include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">
    <div class="col-sm-10 border">
      <h2>Salle <?= $produit['titre'] ?></h2>
    </div>
    <div class="col-sm-2 border text-center">
      <h3><a href="reservation.php">Reserver</a></h3>
    </div>
    <div class="col-sm-8 border">
      <img src="img/<?= $produit['photo'] ?>" alt="<?= $produit['photo'] ?>">
    </div>
    <div class="col-sm-4 border">
      <div>
        <h3>Description</h3>
        <p><?= $produit['description'] ?></p>
      </div>
      <div>
        <h3>localisation</h3>
        google
      </div>
    </div>
    <div class="col-sm-12 border">
      Informations complémentaires :
    </div>
    <div class="col-sm-4 border">
      <p>Arrivée : <?= $produit['date_arrivee'] ?></p>
      <p>Départ : <?= $produit['date_depart'] ?></p>
    </div>
    <div class="col-sm-4 border">
      <p>Capacité : <?= $produit['capacite'] ?> personnes</p>
      <p>Catégorie : <?= $produit['categorie'] ?></p>
    </div>
    <div class="col-sm-4 border">
      <p>Adresse : <?= $produit['adresse'] ?> <?= $produit['cp'] ?> <?= $produit['ville'] ?></p>
      <p>Tarif : <?= $produit['prix'] ?> €</p>
    </div>

  </div>


  <div class="row">
    <div class="col-12 border text-center">
      <h2>AVIS</h2>
      <h3>Note moyenne : <?= $note_moyenne; ?>/10</h3>
    </div>
  </div>
  
  <?php
  // Requete pour avoir les avis
  $liste_avis = $pdo->prepare("SELECT * FROM avis, membre WHERE id_salle = :id_salle AND avis.id_membre = membre.id_membre");
  $liste_avis->bindparam(":id_salle", $produit['id_salle'], PDO::PARAM_STR);
  $liste_avis->execute();

  // Boucle pour générer les boites des avis
  while ($avis = $liste_avis->fetch(PDO::FETCH_ASSOC)) {
  ?>
    <div class="row p-4">
      <div class="col-sm-6 border border-primary">
        <?= $avis['pseudo']; ?> - (<?= $avis['nom']; ?> <?= $avis['prenom']; ?>) <br>
        Date et heure de reservation : <?= $avis['date_enregistrement']; ?> <br>
        Note : <?= $avis['note']; ?> /10
      </div>
      <div class="col-sm-6 border border-secondary">
        <?= $avis['commentaire']; ?>
      </div>
    </div>
  <?php } ?>

<!--
  <form>
    <div class="row">
      <div class="col-12">
        Donnez votre avis sur cette salle :
      </div>
      <div class="col-12">
        <label for="message">Avis :</label>
				<textarea name="message" id="message"></textarea>
      </div>
    </div>
  </form>
-->
</div>







</div>
<!-- /.container -->

<?php
include "inc/footer_script.php";
include "inc/footer.php";
?>