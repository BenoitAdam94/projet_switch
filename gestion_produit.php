<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if (!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}


$id_produit = ''; // pour la modification
$id_salle = "";
$date_arrivee = "";
$date_depart = "";
$prix = "";

$msg = '';

//*********************************************************************
//*********************************************************************
// SUPPRESSION D'UN PRODUIT
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_produit'])) {



  $interrogation = $pdo->prepare("SELECT COUNT(id_produit) FROM commande WHERE id_produit = :id_produit;");
  $interrogation->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
  $interrogation->execute();

  $count_id_produit = $interrogation->fetch(PDO::FETCH_ASSOC);

  $count = $count_id_produit['COUNT(id_produit)'];
  $count = intval($count);

  // Si il y a une commande associé à ce produit, on ne peut la supprimer
  if ($count > 0) {

    $msg .= 'Vous ne pouvez supprimer ce produit car il est associé à une commande existante.';
  } else {

    // Sinon suppression

    $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $suppression->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
    $suppression->execute();

    // $pdo->query("SET FOREIGN_KEY_CHECKS=0");
    // $pdo->query("SET FOREIGN_KEY_CHECKS=1");

  }
}






//*********************************************************************
//*********************************************************************
// ENREGISTREMENT & MODIFICATION DES PRODUITS
//*********************************************************************
//*********************************************************************
if (
  isset($_POST['id_salle']) &&
  isset($_POST['date_arrivee']) &&
  isset($_POST['date_depart']) &&
  isset($_POST['prix'])
) {

  js('test');


  $id_salle = trim($_POST['id_salle']);
  $date_arrivee = trim($_POST['date_arrivee']);
  $date_depart = trim($_POST['date_depart']);
  $prix = trim($_POST['prix']);

  $id_produit = trim($_POST['id_produit']);




  if (empty($msg)) {
    js('if empty msg');

    if (!empty($id_produit)) {
      // si id_produit existe un UPDATE
      js('if id_produit UPDATE');

      $enregistrement = $pdo->prepare("UPDATE produit SET id_salle = :id_salle, date_arrivee = :date_arrivee, date_depart = :date_depart, prix = :prix WHERE id_produit = :id_produit");

      $enregistrement->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
    } else {
      // sinon un INSERT
      js('ELSE insert');

      $enregistrement = $pdo->prepare("INSERT INTO produit (id_produit, id_salle, date_arrivee, date_depart, prix)
                                         VALUES (NULL, :id_salle, :date_arrivee, :date_depart, :prix)");
    }



    $enregistrement->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);
    $enregistrement->bindParam(":date_arrivee", $date_arrivee, PDO::PARAM_STR);
    $enregistrement->bindParam(":date_depart", $date_depart, PDO::PARAM_STR);
    $enregistrement->bindParam(":prix", $prix, PDO::PARAM_STR);
    $enregistrement->execute();
  }
}



//*********************************************************************
//*********************************************************************
// MODIFICATION : RECUPERATION DES INFOS DE L'ARTICLE EN BDD
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_produit'])) {

  $infos_produit = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
  $infos_produit->bindparam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
  $infos_produit->execute();


  if ($infos_produit->rowCount() > 0) {
    $produit_actuel = $infos_produit->fetch(PDO::FETCH_ASSOC);

    $id_produit = $produit_actuel['id_produit'];
    $id_salle = $produit_actuel['id_salle'];
    $date_arrivee = $produit_actuel['date_arrivee'];
    $date_depart = $produit_actuel['date_depart'];
    $prix = $produit_actuel['prix'];



    // $msg .= 'Modification de ' . $id_salle . ' ' . $titre;
  }
}

//*********************************************************************
//*********************************************************************
// \FIN MODIFICATION : RECUPERATION DES INFOS DE L'ARTICLE EN BDD
//*********************************************************************
//*********************************************************************



include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">

    <div class="col-12 text-center">
      <h2>Gestion des Produits</h2>

      <table>
        <tr>
          <th>id produit</th>
          <th>date_arrivee</th>
          <th>date_depart</th>
          <th>id_salle</th>
          <th>prix</th>
          <th>Etat</th>
          <th>Actions</th>
        </tr>

        <?php
        $liste_produits = $pdo->query("SELECT * from produit, salle
                                    WHERE produit.id_salle = salle.id_salle;");

        while ($produits = $liste_produits->fetch(PDO::FETCH_ASSOC)) {
          // on récupère les membres en bdd




          echo '<tr>';
          echo '<td>' . $produits['id_produit'] . '</td>';
          echo '<td>' . $produits['date_arrivee'] . '</td>';
          echo '<td>' . $produits['date_depart'] . '</td>';
          echo '<td>' . $produits['id_salle'] . ' - ' . $produits['titre'] . '</td>';
          echo '<td>' . $produits['prix'] . ' € </td>';
          echo '<td>' . $produits['etat'] . '</td>';
          echo '<td>';

          echo '<a title="modifier" ';
          echo 'href="gestion_produit.php?action=modifier&id_produit=' . $produits['id_produit'] . '&id_salle=' . $produits['id_salle'] . '">';
          echo '<i class="fas fa-exchange-alt fa-lg"></i></a> ';

          echo '<a title="supprimer" href="gestion_produit.php?action=supprimer&id_produit=' . $produits['id_produit'] . '">';
          echo '<i class="fas fa-trash-alt fa-lg"></i></a>';

          echo '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </div>

    <div class="col-12 text-center">
      <br>
      <h2>Ajout/Modification d'un produit</h3>
        <p class="lead"><?php echo $msg; ?></p>
    </div>
  </div>





  <!-- récupération de l'id_article pour la modification -->

  <form method="post" action="" enctype="multipart/form-data">
    <div class="row">


      <div class="col-6">
        <div class="form-group">
          <label for="id_produit">Produit actuel :</label>
          <!-- affichage du produit actuel ou de "nouveau produit" si vide -->
          <?php
          if (empty($id_produit)) { ?>
            Nouveau produit
            <input type="hidden" name="id_produit" value="">
          <?php } else { ?>
            <input name="id_produit" value="<?= $id_produit; ?>">
            <p>Modifier ou <a href="gestion_produit.php">Ajouter un nouveau produit</a></p>
          <?php } ?>
        </div>



        <!-- Date d'arrivée -->
        <div class="form-group">
          <label for="date_arrivee">Date d'arrivée</label>
          <input type="text" class="form-control" id="date_arrivee" name="date_arrivee" value="<?= $date_arrivee; ?>" required>
        </div>
        <!-- Date de départ -->
        <div class="form-group">
          <label for="date_depart">Date de départ</label>
          <input type="text" class="form-control" id="date_depart" name="date_depart" value="<?= $date_depart; ?>" required>
        </div>
      </div>
      <div class="col-6">
        <!-- Salle -->
        <div class="form-group">
          <label for="nom">Salle</label>
          <select name="id_salle" id="id_salle" class="form-control">
            <?php
            $liste_salle = $pdo->query("SELECT * FROM salle");

            while ($salle = $liste_salle->fetch(PDO::FETCH_ASSOC)) {

              echo '<option ';
              // Option "selected" pour la modification
              if (!empty($_GET['id_salle']) && ($salle['id_salle'] == $_GET['id_salle'])) {
                echo 'selected ';
              }
              // Value
              echo 'value = "' . $salle['id_salle'] . '">';

              echo $salle['id_salle'] . ' - ' . $salle['titre'] . ' - ';
              echo $salle['adresse'] . ' - ' . $salle['cp'] . ' ' . $salle['ville'] . ' - ';
              echo $salle['capacite'] . ' Personnes';
              echo '</option>';
            }
            ?>

          </select>
        </div>
        <!-- Tarif -->
        <div class="form-group">
          <label for="prix">Prix</label>
          <input type="text" name="prix" id="prix" value="<?= $prix; ?>" class="form-control">
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
include "inc/footer_script.php";
include "inc/footer.php";
?>
<script>
  // DATEPICKER
  // format reçu par jQuery : 11/29/2016
  // format attendu par PHP : 2016-11-29 09:00:00
  $(function() {
    $("#date_arrivee").datepicker({
      dateFormat: "yy-mm-dd 09:00:00"
    });
    $("#date_depart").datepicker({
      dateFormat: "yy-mm-dd 19:00:00"
    });

  });
</script>