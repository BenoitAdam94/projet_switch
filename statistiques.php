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
                              LIMIT 0, 3");
                            
$liste_top_achat = $pdo->query("SELECT pseudo, COUNT(id_commande) AS top_achat
                              FROM membre, commande
                              WHERE membre.id_membre = commande.id_membre
                              GROUP BY membre.id_membre
                              ORDER BY top_achat DESC
                              LIMIT 0, 3");

$liste_top_depense = $pdo->query("SELECT pseudo, SUM(prix) AS top_depense
                              FROM membre, commande, produit
                              WHERE membre.id_membre = commande.id_membre
                              AND commande.id_produit = produit.id_produit
                              GROUP BY membre.id_membre
                              ORDER BY top_depense DESC
                              LIMIT 0, 3");

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
        <?php
        while ($top_commande = $liste_top_commande->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $top_commande['titre'] . '</td>';
        echo '<td>' . $top_commande['top_commande'] . '</td>';
        echo '</tr>';
        }
        ?>
      </table>

    </div>
    <div class="col-sm-6">
      <h2>Membres qui achétent le plus :</h2>
      <table>
        <?php
        while ($top_achat = $liste_top_achat->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . $top_achat['pseudo'] . '</td>';
        echo '<td>' . $top_achat['top_achat'] . '</td>';
        echo '</tr>';
        }
        ?>
      </table>

    </div>
    <div class="col-sm-6">
      <h2>Membres qui dépensent le plus :</h2>
      <table>
        <?php
        foreach($top_depense = $liste_top_depense->fetchAll(PDO::FETCH_ASSOC) AS $ind => $val) {

        $ind = $ind + 1;

        echo '<tr>';
        echo '<td>' . $ind . ' - ' . $val['pseudo']  . '</td>';
        echo '<td>' . $val['top_depense'] . ' € </td>';
        echo '</tr>';
        
        }
        ?>
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