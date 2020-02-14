<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';

if (!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}


include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">
    <div class="col-12 text-center">
      <h2>Gestion des salles</h2>

      <table>
        <tr>
          <th>id_salle</th>
          <th>titre</th>
          <th>description</th>
          <th>photo</th>
          <th>pays</th>
          <th>ville</th>
          <th>adresse</th>
          <th>CP</th>
          <th>Capacite</th>
          <th>Categorie</th>
          <th>Actions</th>
        </tr>

        <?php
        $liste_salle = $pdo->query("SELECT * FROM salle");

        while ($salle = $liste_salle->fetch(PDO::FETCH_ASSOC)) {
          // on récupère les articles en bdd




          echo '<tr>';
          echo '<td>' . $salle['id_salle'] . '</td>';
          echo '<td>' . $salle['titre'] . '</td>';
          echo '<td>' . $salle['description'] . '</td>';
          echo '<td><img src="img/' . $salle['photo'] . '" width="100px";> </td>';
          echo '<td>' . $salle['pays'] . '</td>';
          echo '<td>' . $salle['ville'] . '</td>';
          echo '<td>' . $salle['adresse'] . '</td>';
          echo '<td>' . $salle['cp'] . '</td>';
          echo '<td>' . $salle['capacite'] . '</td>';
          echo '<td>' . $salle['categorie'] . '</td>';
          echo '<td><i class="fas fa-exchange-alt"></i> <i class="fas fa-trash-alt"></i></td>';
          echo '</td>';
        }
        ?>
      </table>
    </div>

    <div class="col-12 text-center">
      <h2>Ajouter une salle</h3>
    </div>

    <div class="col-6">
      <form method="post" action="">
        <!-- enctype="multipart/form-data" -->

        <!-- récupération de l'id_article pour la modification -->
        <input name="id_article" value="<?= '$id_article' ?>">

        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="titre">Titre</label>
              <input type="text" name="titre" id="titre" value="<?= '' ?>" class="form-control">
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea name="description" id="description" rows="2" class="form-control"><?= '' ?></textarea>
            </div>
            <div class="form-group">
              <label for="photo">Photo</label>
              <input type="file" name="photo" id="photo" class="form-control">
            </div>
            <div class="from-group">
              <label for="capacite">Capacité</label>
              <select name="capacite" id="capacite" class="form-control">
                <option>1</option>
                <option>5</option>
                <option>10</option>
                <option>15</option>
              </select>
              <div>
                <div class="from-group">
                  <label for="categorie">Categorie</label>
                  <select name="categorie" id="categorie" class="form-control">
                    <option>1</option>
                    <option>5</option>
                    <option>10</option>
                    <option>15</option>
                  </select>
                </div>
                <div class="from-group">
                  <label for="pays">Pays</label>
                  <select name="pays" id="pays" class="form-control">
                    <option>France</option>
                    <option>Corse</option>
                    <option>DOM</option>
                  </select>
                </div>
                <div class="from-group">
                  <label for="ville">Ville</label>
                  <select name="ville" id="ville" class="form-control">
                    <option>Paris</option>
                    <option>Lyon</option>
                    <option>Marseille</option>
                  </select>
                </div>
                <div class="from-group">
                  <label for="adresse">Adresse</label>
                  <textarea name="adresse" id="adresse" rows="2" class="form-control"><?= '' ?></textarea>
                </div>
                <div class="form-group">
                  <label for="cp">Code Postal</label>
                  <input type="cp" name="cp" id="cp" value="<?= 'valeur' ?>" class="form-control">
                </div>
                <div class="form-group">
                  <button type="submit" name="enregistrement" id="enregistrement" class="form-control btn btn-outline-dark">Enregistrer </button>
                </div>

              </div>
            </div>
      </form>

    </div>
  </div>





</div>
<!-- /.row -->

</div>
<!-- /.container -->

<?php

include "inc/footer.php";
?>