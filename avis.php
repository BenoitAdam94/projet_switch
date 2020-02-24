<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


$id_salle = $_GET['salle'];
$id_membre = $_SESSION['membre']['id_membre'];
$note = '';
$commentaire = '';

$date = new DateTime();
$date_enregistrement = $date->format('Y-m-d H:i:s');



if (
  isset($_POST['id_avis']) &&
  isset($_POST['note']) &&
  isset($_POST['commentaire'])
) {

  $id_avis = $_POST['id_avis'];
  $note = $_POST['note'];
  $commentaire = trim($_POST['commentaire']);


  if (!empty($id_avis)) {
    // Si il y a déjà un avis c'est un UPDATE

    $enregistrement = $pdo->prepare("UPDATE avis SET note = :note, commentaire = :commentaire
                                        WHERE id_salle = :id_salle
                                         AND id_membre = :id_membre");
    $enregistrement->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);

    $msg .= 'Avis modifié !';
  } else {

    // sinon un INSERT
    $enregistrement = $pdo->prepare("INSERT INTO avis (id_avis, id_membre, id_salle, note, commentaire, date_enregistrement) VALUES (NULL, :id_membre, :id_salle, :note, :commentaire, :date_enregistrement)");

    $enregistrement->bindParam(':date_enregistrement', $date_enregistrement, PDO::PARAM_STR);

    $msg .= 'Avis ajouté !';
  }


  // On déclenche l'insertion
  js('insertion');

  $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
  $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
  $enregistrement->bindParam(':note', $note, PDO::PARAM_STR);
  $enregistrement->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);

  $enregistrement->execute();
}


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


dump($note);

include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row text-center">
    <h2 class="text-center">Votre avis sur la salle <?= $titre; ?></h2>
  </div>
  <div>

    <form method="post" id="form_avis" action="">

      <div class="col-6">
        <p><?= $msg; ?></p>


        <!-- id_s -->
        <div>
          <input type="hidden" name="id_avis" value="<?= $id_avis; ?>">
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
          <button class="form-control btn btn-outline-primary" type="submit" class="form-control btn btn-outline-dark">Envoyer</button>
        </div>
        
    </form>
    
  </div>
</div>
<!-- /.row -->

</div>
<!-- /.container -->

<?php
include "inc/footer_script.php";
include "inc/footer.php";
?>