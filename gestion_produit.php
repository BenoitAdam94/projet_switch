<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if (!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}

//*********************************************************************
//*********************************************************************
// SUPPRESSION D'UN PRODUIT
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_produit'])) {
  $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
  $suppression->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
  $suppression->execute();

  // $_GET['action'] = 'affichage'; // pour provoquer l'affichage du tableau

}


// $id_produit = ''; // pour la modification
$id_salle = "";
$date_arrivee = "";
$date_depart = "";
$prix = "";

$msg = '';

dump($_POST);


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


  // transformation format date_arrivee
  // format reçu par jQuery : 11/29/2016
  // format attendu par PHP : 2016-11-29 09:00:00
  // format attendu par PHP : YYYY-MM-DD

  
  if (empty($msg)) {
    js('if empty msg');

		if (!empty($id_produit)) {
      // si id_produit existe un UPDATE
      js('if id_membre UPDATE');
			
			$enregistrement = $pdo->prepare("UPDATE produit SET id_salle = :id_salle, date_arrivee = :date_arrivee, date_depart = :date_depart, prix = :prix WHERE id_produit = :id_produit");
			
			$enregistrement->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);
      
		} else {
      // sinon un INSERT
      js('if empty msg ELSE insert');
			
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
          /*
          echo '<a href="gestion_avis.php?action=modifier&id_avis=' . $produits['id_avis'] . '">';
          echo '<i class="fas fa-exchange-alt"></i></a> ';
          echo '<a href="gestion_avis.php?action=supprimer&id_avis=' . $produits['id_avis'] . '">';
          echo '<i class="fas fa-trash-alt"></i></a>';
          */
          echo '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </div>

    <div class="col-12 text-center">
      <br>
      <h2>Modification d'un produit</h3>
        <p class="lead"><?php echo $msg; ?></p>
    </div>
  </div>





  <!-- récupération de l'id_article pour la modification -->

  <form method="post" action="" enctype="multipart/form-data">
    <div class="row">


      <div class="col-6">
        <!-- <input type="hidden" name="id_article" value="<?= '$id_membre'; ?>"> -->



        <!-- Date d'arrivée -->
        <div class="form-group">
          <label for="date_arrivee">Date d'arrivée</label>
          <input type="text" class="form-control" id="date_arrivee" name ="date_arrivee" required>
        </div>
        <!-- Date de départ -->
        <div class="form-group">
          <label for="date_depart">Date de départ</label>
          <input type="text" class="form-control" id="date_depart" name ="date_depart" required>
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
              echo '<option value = "' . $salle['id_salle'] . '">';
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
          <input type="text" name="prix" id="prix" value="" class="form-control">
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
<script>
  $(function() {
    $("#date_arrivee").datepicker({
        dateFormat: "yy-mm-dd 09:00:00"
    });
    $("#date_depart").datepicker({
    dateFormat: "yy-mm-dd 19:00:00"
    });
    
  });
</script>