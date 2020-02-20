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
include "inc/footer_script.php";
include "inc/footer.php";
?>