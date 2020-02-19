<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if (!user_is_admin()) {
  header('location:' . URL . 'connexion.php');
  exit(); // bloque l'exécution du code 
}

$liste_top_note = $pdo->query("SELECT titre, ROUND(AVG(note), 1) AS top_note
                          FROM salle, avis
                          WHERE salle.id_salle = avis.id_salle
                          GROUP BY salle.id_salle
                          ORDER BY top_note DESC
                          LIMIT 0, 3");

$liste_top_commande = $pdo->query("SELECT titre, COUNT(id_commande) AS top_commande
                              FROM salle, produit, commande
                              WHERE salle.id_salle = produit.id_salle
                              AND produit.id_produit = commande.id_produit
                              GROUP BY salle.id_salle
                              ORDER BY top_commande DESC
                              LIMIT 0, 3;");



include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">
    <div class="col-12  text-center">
      <h1 class=" text-center">Statistiques</h1>
    </div>
  </div>

  <div class="row m-2">
    <div class="col-sm-6">
      <h2>Salles les mieux notées :</h2>
      <table>
        <?php
        while ($top_note = $liste_top_note->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $top_note['titre'] . '</td>';
        echo '<td>' . $top_note['top_note'] . '</td>';
        echo '</tr>';
        }
        ?>
      </table>

    </div>
    <div class="col-sm-6">
      <h2>Salles les plus commandées :</h2>
      <table>
        <tr>
          <td>Cezanne</td>
          <td>28</td>
        </tr>
        <tr>
          <td>Cezanne</td>
          <td>28</td>
        </tr>
        <tr>
          <td>Cezanne</td>
          <td>28</td>
        </tr>
        <tr>
          <td>Cezanne</td>
          <td>28</td>
        </tr>
        <tr>
          <td>Cezanne</td>
          <td>28</td>
        </tr>
      </table>

    </div>
    <div class="col-sm-6">
      <h2>Membres qui achétent le plus :</h2>
      <table>
        <tr>
          <td>Arnold</td>
          <td>28 commandes</td>
        </tr>
      </table>

    </div>
    <div class="col-sm-6">
      <h2>Membres qui dépensent le plus :</h2>
      <table>
        <tr>
          <td>Arnold</td>
          <td>7777 €</td>
        </tr>
      </table>

    </div>


  </div>

</div>

<hr>



</div>
<!-- /.row -->

</div>
<!-- /.container -->

<?php

include "inc/footer_script.php";
include "inc/footer.php";
?>