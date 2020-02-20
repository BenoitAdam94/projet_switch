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
// SUPPRESSION D'UNE COMMANDE
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_commande'])) {
  $suppression = $pdo->prepare("DELETE FROM membre WHERE id_commande = :id_commande");
  $suppression->bindParam(":id_commande", $_GET['id_commande'], PDO::PARAM_STR);
  $suppression->execute();

  $_GET['action'] = 'affichage'; // pour provoquer l'affichage du tableau

}


//*********************************************************************
//*********************************************************************
// \FIN SUPPRESSION D'UN MEMBRE
//*********************************************************************
//*********************************************************************


$id_commande = ''; // pour la modification
$id_membre = "";
$id_produit = "";
$prix = "";
$date_enregistrement = "";


$msg = '';



//*********************************************************************
//*********************************************************************
// ENREGISTREMENT & MODIFICATION DES MEMBRES
//*********************************************************************
//*********************************************************************

if (
  isset($_POST['id_membre']) &&
  isset($_POST['id_produit']) &&
  isset($_POST['date_enregistrement'])

) {

  $id_membre = trim($_POST['id_membre']);
  $id_produit = trim($_POST['id_produit']);
  $date_enregistrement = trim($_POST['date_enregistrement']);
  js('isset');

  if (empty($msg)) {
    if (!empty($_POST['id_commande'])) {
      js('Updation');

      // si $id_commande n'est pas vide c'est un UPDATE

      

      $enregistrement = $pdo->prepare("UPDATE commande SET id_membre = :id_membre, id_produit = :id_produit , date_enregistrement = :date_enregistrement WHERE id_commande = :id_commande");

      // on rajoute le bindParam pour l'id_commande car => modification

      $enregistrement->bindParam(":id_commande", $_POST['id_commande'], PDO::PARAM_STR);

    } else {
      // sinon un INSERT
      js('Insertion');



      $enregistrement = $pdo->prepare("INSERT INTO commande (id_commande, id_membre, id_produit, date_enregistrement) VALUES (NULL, :id_membre, :id_produit, :date_enregistrement)");
    }



    // On déclenche l'insertion
    js('execution');
    // on peut déclencher l'enregistrement s'il n'y a pas eu d'erreur dans les traitements précédents


    $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
    $enregistrement->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
    $enregistrement->bindParam(':date_enregistrement', $date_enregistrement, PDO::PARAM_STR);
    $enregistrement->execute();

    
  }
}



//*********************************************************************
//*********************************************************************
// MODIFICATION : RECUPERATION DES INFOS DE L'ARTICLE EN BDD
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_commande'])) {

  $infos_commande = $pdo->prepare("SELECT * FROM commande WHERE id_commande = :id_commande");
  $infos_commande->bindparam(":id_commande", $_GET['id_commande'], PDO::PARAM_STR);
  $infos_commande->execute();


  if ($infos_commande->rowCount() > 0) {
    $commande_actuel = $infos_commande->fetch(PDO::FETCH_ASSOC);

    $id_commande = $commande_actuel['id_commande'];
    $id_membre = $commande_actuel['id_membre'];
    $id_produit = $commande_actuel['id_produit'];
    $date_enregistrement = $commande_actuel['date_enregistrement'];
    
    
    

    
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
      <h2>Gestion des Commandes</h2>

      <table>
        <tr>
          <th>id commande</th>
          <th>id_membre</th>
          <th>id_produit</th>
          <th>Prix</th>
          <th>date_enregistrement</th>
          <th>Actions</th>
        </tr>

        <?php
        $liste_commandes = $pdo->query("SELECT * from commande, membre, produit
                                    WHERE commande.id_membre = membre.id_membre
                                      AND commande.id_produit = produit.id_produit;");

        while ($commandes = $liste_commandes->fetch(PDO::FETCH_ASSOC)) {
          // on récupère les membres en bdd




          echo '<tr>';
          echo '<td>' . $commandes['id_commande'] . '</td>';
          echo '<td>' . $commandes['id_membre'] . ' - ' . $commandes['pseudo'] . '</td>';
          echo '<td>' . $commandes['id_produit'] . '</td>';
          echo '<td>' . $commandes['prix'] . ' € </td>';
          echo '<td>' . $commandes['date_enregistrement'] . '</td>';
          echo '<td>';
          echo '<a href="gestion_commande.php?action=modifier&id_commande=' . $commandes['id_commande'] . '">';
          echo '<i class="fas fa-exchange-alt fa-lg"></i></a> ';
          echo '<a href="gestion_commande.php?action=supprimer&id_commande=' . $commandes['id_commande'] . '">';
          echo '<i class="fas fa-trash-alt fa-lg"></i></a>';

          echo '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </div>

    <div class="col-12 text-center">
      <br>
      <h2>Ajout / Modification d'une Commande</h3>
        <p class="lead"><?php echo $msg; ?></p>
    </div>
  </div>





  <!-- récupération de l'id_article pour la modification -->

  <form method="post" action="" enctype="multipart/form-data">
    <div class="row justify-content-center">


      <div class="col-6">
        <input name="id_commande" value="<?= $id_commande; ?>">



        <!-- id_membre -->
        <div class="form-group">
          <label for="id_membre">id_membre</label>
          <input type="text" name="id_membre" id="id_membre" value="<?= $id_membre; ?>" class="form-control">
        </div>
        <!-- ID Produit -->
        <div class="form-group">
          <label for="id_produite">id_produit</label>
          <input type="text" name="id_produit" id="id_produit" value="<?= $id_produit; ?>" class="form-control">
        </div>
      
        <!-- DATE enregistrement -->
        <div class="form-group">
          <label for="date_enregistrement">date_enregistrement</label>
          <input type="text" name="date_enregistrement" id="date_enregistrement" value="<?= $date_enregistrement ?>" class="form-control">
        </div>
      
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
    $("#date_enregistrement").datepicker({
      dateFormat: "yy-mm-dd 09:00:00"
    });
  });
</script>