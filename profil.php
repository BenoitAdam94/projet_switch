<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if (!user_is_connect()) {
  header('location:connexion.php');
}




include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">

    <div class="col-12">
      <h1><i class="fas fa-user" style="color: #4c6ef5;"></i> Profil <i class="fas fa-user" style="color: #4c6ef5;"></i></h1>
      <p class="lead"><?php echo $msg; ?></p>
    </div>
  </div>


  <div class="row">
    <div class="col-6">
      <ul class="list-group">
        <li class="list-group-item active">Bonjour <b><?php echo ucfirst($_SESSION['membre']['pseudo']); ?></b></li>
        <li class="list-group-item">Pseudo : <b><?php echo ucfirst($_SESSION['membre']['pseudo']); ?></b></li>
        <li class="list-group-item">Nom : <b><?php echo ucfirst($_SESSION['membre']['nom']); ?></b></li>
        <li class="list-group-item">Prénom : <b><?php echo ucfirst($_SESSION['membre']['prenom']); ?></b></li>
        <li class="list-group-item">Email : <b><?php echo $_SESSION['membre']['email']; ?></b></li>
        <li class="list-group-item">civilite : <b>
            <?php
            if ($_SESSION['membre']['civilite'] == 'm') {
              echo 'Homme';
            } else {
              echo 'Femme';
            }
            ?></b>
        </li>

        <li class="list-group-item">Statut : <b>
            <?php
            if ($_SESSION['membre']['statut'] == 1) {
              echo 'membre';
            } elseif ($_SESSION['membre']['statut'] == 2) {
              echo 'administrateur';
            }
            ?>
          </b></li>
      </ul>
    </div>
    <div class="col-6">
      <img src="img/profil.jpg" alt="image profil" class="img-thumbnail w-100">
    </div>

  </div>
  <div class="row">
    <div class="col-12 text-center">
      <h2>Liste de vos commandes</h2>

      <table>
        <tr>
          <th>Numéro de commande</th>
          <th>Reference Produit</th>
          <th>Prix</th>
          <th>date_enregistrement</th>
          <th>Salle</th>
          <th>Action</th>

        </tr>
        <?php

        $id_membre = $_SESSION['membre']['id_membre'];


        $liste_commandes = $pdo->prepare("SELECT * FROM commande, produit, salle
                                          WHERE commande.id_produit = produit.id_produit
                                          AND produit.id_salle = salle.id_salle
                                          AND id_membre = :id_membre");
        $liste_commandes->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
        $liste_commandes->execute();


        while ($commandes = $liste_commandes->fetch(PDO::FETCH_ASSOC)) {

          echo '<tr>';
          echo '<td>' . $commandes['id_commande'] . '</td>';
          echo '<td>' . $commandes['id_produit'] . '</td>';
          echo '<td>' . $commandes['prix'] . ' € </td>';
          echo '<td>' . $commandes['date_enregistrement'] . '</td>';
          echo '<td>' . $commandes['id_salle'] . ' - ' . $commandes['titre'] . '</td>';

          echo '<td>';
          echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_avis">
              Avis
            </button>';
          echo '<a title="noter la salle" href="avis.php?salle=' . $commandes['id_salle'] . '">';

          echo '<i class="fas fa-star fa-lg"></i>';
          echo '</a>';
          echo '</td>';

          echo '</tr>';
        }
        ?>
      </table>
    </div>
  </div>

</div>
<!-- /.container -->


<?php

$id_salle = 1;
$id_membre = $_SESSION['membre']['id_membre'];
$note = '';
$commentaire = '';

$date = new DateTime();
$date_enregistrement = $date->format('Y-m-d H:i:s');



//*********************************************************************
//*********************************************************************
// RECUPERATION : RECUPERATION DU NOM DE LA SALLE
//*********************************************************************
//*********************************************************************

$infos_salle = $pdo->prepare("SELECT titre FROM salle WHERE id_salle = :id_salle");
$infos_salle->bindParam("id_salle", $id_salle, PDO::PARAM_STR);
$infos_salle->execute();

$salle_actuel = $infos_salle->fetch(PDO::FETCH_ASSOC);

$titre = $salle_actuel['titre'];


//*********************************************************************
//*********************************************************************
// RECUPERATION : RECUPERATION DE L'AVIS EN BDD
//*********************************************************************
//*********************************************************************







$infos_avis = $pdo->prepare("SELECT * FROM avis
                              WHERE id_salle = :id_salle
                                AND id_membre = :id_membre");
$infos_avis->bindparam(":id_salle", $id_salle, PDO::PARAM_STR);
$infos_avis->bindparam(":id_membre", $id_membre, PDO::PARAM_STR);
$infos_avis->execute();

// $titre = $avis_actuel['titre'];





if ($infos_avis->rowCount() > 0) {
  $avis_actuel = $infos_avis->fetch(PDO::FETCH_ASSOC);

  $id_avis = $avis_actuel['id_avis'];
  $note = $avis_actuel['note'];
  $note = intval($note);
  $commentaire = $avis_actuel['commentaire'];
}

?>








</div>

<?php
include "inc/footer.php";
?>

<div class="modal fade" id="modal_avis" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Avis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="form_avis">

          <div class="col-6">
            <p><?= $msg; ?></p>


            <!-- id_s -->
            <div>
              <input type="" name="id_avis" value="<?= $id_avis; ?>">
              <input type="hidden" name="id_membre" value="<?= $id_membre; ?>">
              <input type="hidden" name="id_salle" value="<?= $id_salle; ?>">
            </div>
            <hr>


            <!-- Note -->
            <div class="form-group">

              <label for="note">Votre Note (Note actuelle : <?= $note; ?>)</label>
              <select id="note" name="note" class="form-control" required>

                <?php
                for ($i = 10; $i > 0; $i--) {

                  echo '<option ';
                  if ($i == $note) {
                    echo 'selected ';
                  }
                  echo '>' . $i . '</option>';
                }
                ?>

              </select>
            </div>


            <!-- Commentaire -->
            <div class="form-group">
              <label for="commentaire">Commentaire</label>
              <textarea name="commentaire" id="commentaire" class="form-control"><?= $commentaire; ?></textarea>
            </div>

            <div>
              <button class="form-control btn btn-outline-primary" name="avis" type="submit" class="form-control btn btn-outline-dark">Envoyer</button>
            </div>
            <hr>
            <div id="resultat_avis"></div>

        </form>



        <p><?= $msg; ?></p>
      </div>
    </div>
  </div>

  <?php include "inc/footer_script.php"; ?>