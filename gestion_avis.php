<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if (!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}

$msg = '';



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
          <th>id avis</th>
          <th>id_membre</th>
          <th>id_salle</th>
          <th>commentaire</th>
          <th>note</th>
          <th>date_enregistrement</th>
          <th>Actions</th>
        </tr>

        <?php
        $liste_avis = $pdo->query("SELECT * from avis, membre, salle
                                    WHERE avis.id_membre = membre.id_membre
                                      AND avis.id_salle = salle.id_salle;");

        while ($avis = $liste_avis->fetch(PDO::FETCH_ASSOC)) {
          // on récupère les membres en bdd




          echo '<tr>';
          echo '<td>' . $avis['id_avis'] . '</td>';
          echo '<td>' . $avis['id_membre'] . ' - ' . $avis['pseudo'] . '</td>';
          echo '<td>' . $avis['id_salle'] . ' - ' . $avis['titre'] . '</td>';
          echo '<td>' . $avis['commentaire'] . '</td>';
          echo '<td>' . $avis['note'] . '</td>';
          echo '<td>' . $avis['date_enregistrement'] . '</td>';
          echo '<td>';
          echo '<a href="gestion_avis.php?action=modifier&id_avis=' . $avis['id_avis'] . '">';
          echo '<i class="fas fa-exchange-alt"></i></a> ';
          echo '<a href="gestion_avis.php?action=supprimer&id_avis=' . $avis['id_avis'] . '">';
          echo '<i class="fas fa-trash-alt"></i></a>';
          echo '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </div>
    
    <div class="col-12 text-center">
      <br>
      <h2>Modification d'un avis</h3>
        <p class="lead"><?php echo $msg; ?></p>
    </div>
  </div>





  <!-- récupération de l'id_article pour la modification -->

  <form method="post" action="" enctype="multipart/form-data">
    <div class="row">


      <div class="col-6">
        <!-- <input type="hidden" name="id_article" value="<?= '$id_membre'; ?>"> -->



        <!-- Pseudo -->
        <div class="form-group">
          <label for="pseudo">Pseudo</label>
          <input type="text" name="pseudo" id="pseudo" value="" class="form-control">
        </div>
        <!-- Mot de passe -->
        <div class="form-group">
          <label for="motdepasse">Mot de passe</label>
          <input type="text" name="motdepasse" id="motdepasse" value="" class="form-control">
        </div>
        <!-- Nom -->
        <div class="form-group">
          <label for="nom">Nom</label>
          <input type="text" name="nom" id="nom" value="" class="form-control">
        </div>
        <!-- Prenom -->
        <div class="form-group">
          <label for="prenom">Prenom</label>
          <input type="text" name="prenom" id="prenom" value="" class="form-control">
        </div>
      </div>
      <div class="col-6">
        <!-- Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" value="" class="form-control">
        </div>
        <!-- Civilite -->
        <div class="from-group">
          <label for="civilite">Civilite</label>
          <select name="civilite" id="civilite" class="form-control">
            <option>Homme</option>
            <option>Femme</option>
          </select>
        </div>
        <!-- Statut -->
        <div class="from-group">
          <label for="statut">Statut</label>
          <select name="statut" id="statut" class="form-control">
            <option>Membre</option>
            <option>Admin</option>
          </select>          
        </div>
        <br>
        <!-- Submit -->
        <div class="form-group">
          <button type="submit" name="enregistrement" id="enregistrement" class="form-control btn btn-outline-dark">Enregistrer </button>
        </div>

      </div>


    </div>
  </form>

</div>





</div>
<!-- /.row -->

</div>
<!-- /.container -->

<?php

include "inc/footer.php";
?>