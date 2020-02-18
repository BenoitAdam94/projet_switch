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
// SUPPRESSION D'UN MEMBRE
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_avis'])) {
	$suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
	$suppression->bindParam(":id_avis", $_GET['id_avis'], PDO::PARAM_STR);
	$suppression->execute();

	$_GET['action'] = 'affichage'; // pour provoquer l'affichage du tableau

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
      <h2>Gestion des avis</h2>

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
          /*
          echo '<a href="gestion_avis.php?action=modifier&id_avis=' . $avis['id_avis'] . '">';
          echo '<i class="fas fa-exchange-alt"></i></a> ';
          */
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
      <h2>Les avis ne sont pas modifiable pour le moment</h3>
        <p class="lead"><?php echo $msg; ?></p>
    </div>
  </div>




</div>





</div>
<!-- /.row -->

</div>
<!-- /.container -->

<?php

include "inc/footer.php";
?>