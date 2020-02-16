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
          <th>id_membre</th>
          <th>pseudo</th>
          <th>nom</th>
          <th>prenom</th>
          <th>e-mail</th>
          <th>civilite</th>
          <th>statut</th>
          <th>date_enregistrement</th>
          <th>Actions</th>
        </tr>

        <?php
        $liste_membre = $pdo->query("SELECT * FROM membre");

        while ($membre = $liste_membre->fetch(PDO::FETCH_ASSOC)) {
          // on récupère les membres en bdd




          echo '<tr>';
          echo '<td>' . $membre['id_membre'] . '</td>';
          echo '<td>' . $membre['pseudo'] . '</td>';
          echo '<td>' . $membre['nom'] . '</td>';
          echo '<td>' . $membre['prenom'] . '</td>';
          echo '<td>' . $membre['email'] . '</td>';
          echo '<td>' . $membre['civilite'] . '</td>';
          echo '<td>' . $membre['statut'] . '</td>';
          echo '<td>' . $membre['date_enregistrement'] . '</td>';
          echo '<td>';
          echo '<a href="gestion_membre.php?action=modifier&id_membre=' . $membre['id_membre'] . '">';
          echo '<i class="fas fa-exchange-alt"></i></a> ';
          echo '<a href="gestion_membre.php?action=supprimer&id_membre=' . $membre['id_membre'] . '">';
          echo '<i class="fas fa-trash-alt"></i></a>';
          echo '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </div>

    <div class="col-12 text-center">
      <h2>Ajouter un membre</h3>
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