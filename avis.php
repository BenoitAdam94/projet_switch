<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';



if (
  isset($_GET['salle'])
) {

  dump($_SESSION);
  $id_salle=$_GET['salle'];
  $note=$_POST['note'];
  $commentaire=$_POST['commentaire'];

  dump($id_salle);
  dump($note);
  dump($commentaire);

  if (empty($msg)) {
      if (!empty($id_salle)) {
        // Si il y a déjà un avis c'est un UPDATE

        $msg .= 'Pas de possibilité de modifier un avis pour le moment';

        /* $enregistrement = $pdo->prepare("UPDATE avis SET note = :note, commentaire = :commentaire WHERE id_salle = :id_salle");
        $enregistrement->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);*/
      } else {
        
        // sinon un INSERT
        $enregistrement = $pdo->prepare("INSERT INTO avis (id_avis, id_membre, id_salle, note, commentaire, date_enregistrement) VALUES (NULL, :id_membre, :id_salle, :note, :commentaire, :date_enregistrement)");
      }
      

      // On déclenche l'insertion
      js('insertion');

      // on peut déclencher l'enregistrement s'il n'y a pas eu d'erreur dans les traitements précédents

      $enregistrement->bindParam(':note', $note, PDO::PARAM_STR);
      $enregistrement->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
      $enregistrement->execute();
    }




}

include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">


    <form method="post">

      <div>
        <label for="note">Note</label>
        <select id="note" name="note" required>
          <script>
            for (var i = 0; i <= 10; i++) {
              document.write('<option>' + i + '</option>');
            }
          </script>
        </select>
      </div>

      <div>
        <label for="commentaire">Commentaire</label>
        <textarea name="commentaire" id="commentaire">

      </textarea>
      </div>

      <button class="mt-2" type="submit" class="form-control btn btn-outline-dark">Envoyer</button>
    </form>

  </div>
  <!-- /.row -->

</div>
<!-- /.container -->

<?php
include "inc/footer_script.php";
include "inc/footer.php";
?>