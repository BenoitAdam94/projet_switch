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
// SUPPRESSION D'UN ARTICLE
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_salle'])) {
  $suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
  $suppression->bindParam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
  $suppression->execute();

  // $_GET['action'] = 'affichage'; // pour provoquer l'affichage du tableau

}

//*********************************************************************
//*********************************************************************
// \FIN SUPPRESSION D'UN ARTICLE
//*********************************************************************
//*********************************************************************

$id_salle = '';
$titre = '';
$description = '';
$photo = '';
$capacite = '';
$categorie = '';
$pays = '';
$ville = '';
$adresse = '';
$cp = '';
$nom_photo = '';
$reference = rand(0, 9999);


// on controle l'existence des champs du formulaire	
if (
  isset($_POST['titre']) &&
  isset($_POST['description']) &&
  isset($_POST['capacite']) &&
  isset($_POST['categorie']) &&
  isset($_POST['pays']) &&
  isset($_POST['ville']) &&
  isset($_POST['adresse']) &&
  isset($_POST['cp'])
) {
  $titre = trim($_POST['titre']);
  $description = trim($_POST['description']);
  $capacite = trim($_POST['capacite']);
  $categorie = trim($_POST['categorie']);
  $pays = trim($_POST['pays']);
  $ville = trim($_POST['ville']);
  $adresse = trim($_POST['adresse']);
  $cp = trim($_POST['cp']);

  if (!empty($_POST['photo_actuelle'])) {
    $nom_photo = $_POST['photo_actuelle'];
  }

  $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $titre);

  if (!$verif_caractere && !empty($titre)) {
    $msg .= '<div class="alert alert-danger mt-3">Titre invalide, caractères autorisés : a-z et de 0-9</div>';
  }

  // Taille titre entre 4 et 14
  if (iconv_strlen($titre) < 4 || iconv_strlen($titre) > 14) {
    $msg .= '<div class="alert alert-danger mt-3">titre invalide, le titre doit avoir entre 4 et 14 caractères inclus</div>';
  }



  // S'il n'y pas eu d'erreur au préalable, on doit vérifier si le titre existe déjà dans la BDD
  if (empty($msg)) {
    // si la variable $msg est vide, alors il n'y a pas eu d'erreur dans nos controles.

    // on vérifie si le titre est disponible.
    $verif_titre = $pdo->prepare("SELECT * FROM salle WHERE titre = :titre");
    $verif_titre->bindParam(":titre", $titre, PDO::PARAM_STR);
    $verif_titre->execute();

    /*
    if ($verif_titre->rowCount() > 0  && empty($id_salle)) {
      // si le nombre de ligne est supérieur à zéro, alors le titre est déjà utilisé.
      $msg .= '<div class="alert alert-danger mt-3">Vous venez deffectuer une modification</div>';
    } else {*/


    if (!empty($_FILES['photo']['name'])) {
      $nom_photo = verif_photo_pj();

      if ($nom_photo === false) {
        $msg .= '<div class="alert alert-danger mt-3">Attention, le format de la photo est invalide, extensions autorisées : jpg, jpeg, png, gif.</div>';
      }
    }




    if (empty($msg)) {
      if (!empty($_POST['id_salle'])) {
        // si $id_salle n'est pas vide c'est un UPDATE
        $enregistrement = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description , photo = :photo, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie WHERE id_salle = :id_salle");

        $enregistrement->bindParam(":id_salle", $_POST['id_salle'], PDO::PARAM_STR);
      } else {
        // sinon un INSERT
        $enregistrement = $pdo->prepare("INSERT INTO salle (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (NULL, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
      }



      // On déclenche l'insertion
      js('insertion');

      // on peut déclencher l'enregistrement s'il n'y a pas eu d'erreur dans les traitements précédents


      $enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
      $enregistrement->bindParam(':description', $description, PDO::PARAM_STR);
      $enregistrement->bindParam(':photo', $nom_photo, PDO::PARAM_STR);
      $enregistrement->bindParam(':pays', $pays, PDO::PARAM_STR);
      $enregistrement->bindParam(':ville', $ville, PDO::PARAM_STR);
      $enregistrement->bindParam(':adresse', $adresse, PDO::PARAM_STR);
      $enregistrement->bindParam(':cp', $cp, PDO::PARAM_STR);
      $enregistrement->bindParam(':capacite', $capacite, PDO::PARAM_STR);
      $enregistrement->bindParam(':categorie', $categorie, PDO::PARAM_STR);
      $enregistrement->execute();
    }
  }
} else {
  $msg .= 'Veuillez remplir le formulaire<br>';
}




//*********************************************************************
//*********************************************************************
// MODIFICATION : RECUPERATION DES INFOS DE L'ARTICLE EN BDD
//*********************************************************************
//*********************************************************************
if (isset($_GET['action']) && $_GET['action'] == 'modifier' && !empty($_GET['id_salle'])) {

  $infos_salle = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
  $infos_salle->bindparam(":id_salle", $_GET['id_salle'], PDO::PARAM_STR);
  $infos_salle->execute();


  if ($infos_salle->rowCount() > 0) {
    $salle_actuel = $infos_salle->fetch(PDO::FETCH_ASSOC);

    $id_salle = $salle_actuel['id_salle'];
    $titre = $salle_actuel['titre'];
    $categorie = $salle_actuel['categorie'];
    $capacite = $salle_actuel['capacite'];
    $pays = $salle_actuel['pays'];
    $ville = $salle_actuel['ville'];
    $cp = $salle_actuel['cp'];
    $adresse = $salle_actuel['adresse'];
    $description = $salle_actuel['description'];
    $photo_actuelle = $salle_actuel['photo'];
  }
}

//*********************************************************************
//*********************************************************************
// \FIN MODIFICATION : RECUPERATION DES INFOS DE L'ARTICLE EN BDD
//*********************************************************************
//*********************************************************************


$capacite = intval($capacite);



include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">
    <div class="col-12 text-center">
      <h2>Gestion des salles</h2>

      <table>
        <tr>
          <th>id_salle</th>
          <th>titre</th>
          <th>description</th>
          <th>photo</th>
          <th>pays</th>
          <th>ville</th>
          <th>adresse</th>
          <th>CP</th>
          <th>Capacite</th>
          <th>Categorie</th>
          <th>Actions</th>
        </tr>

        <?php
        $liste_salle = $pdo->query("SELECT * FROM salle");

        while ($salle = $liste_salle->fetch(PDO::FETCH_ASSOC)) {
          // on récupère les articles en bdd




          echo '<tr>';
          echo '<td>' . $salle['id_salle'] . '</td>';
          echo '<td>' . $salle['titre'] . '</td>';
          echo '<td>' . $salle['description'] . '</td>';
          echo '<td>';
          echo '<a href="img/' . $salle['photo'] . '" target="_blank">';
          echo '<img src="img/' . $salle['photo'] . '" width="100px";>';
          echo '</a></td>';
          echo '<td>' . $salle['pays'] . '</td>';
          echo '<td>' . $salle['ville'] . '</td>';
          echo '<td>' . $salle['adresse'] . '</td>';
          echo '<td>' . $salle['cp'] . '</td>';
          echo '<td>' . $salle['capacite'] . '</td>';
          echo '<td>' . $salle['categorie'] . '</td>';
          echo '<td>';
          echo '<a href="gestion_salle.php?action=modifier&id_salle=' . $salle['id_salle'] . '">';
          echo '<i class="fas fa-exchange-alt fa-lg"></i></a> ';
          echo '<a href="gestion_salle.php?action=supprimer&id_salle=' . $salle['id_salle'] . '">';
          echo '<i class="fas fa-trash-alt fa-lg"></i></a>';
          echo '</td>';
          echo '</tr>';
        }
        ?>
      </table>
    </div>

    <div class="col-12 text-center">
      <h2>Ajouter/Modifier une salle</h2>
      <p class="lead"><?php echo $msg; ?></p>
    </div>
  </div>





  <!-- récupération de l'id_article pour la modification -->

  <form method="post" action="" enctype="multipart/form-data">
    <div class="row">


      <div class="col-6">

        <!-- Affichage id_salle -->
        <input type="hidden" name="id_salle" value="<?= $id_salle; ?>">


        <div class="form-group">
          <label for="id_salle">Salle actuel : <?= $id_salle; ?></label>
          <!-- affichage du produit actuel ou de "nouveau produit" si vide -->
          <?php
          if (empty($id_salle)) { ?>
            Nouvelle salle
            <input type="hidden" name="id_salle" value="">
          <?php } else { ?>
            <input type="hidden" name="id_salle" value="<?= $id_salle; ?>">
            <p>Modifier la salle ou <a href="gestion_salle.php">Ajouter une nouvelle salle</a></p>
          <?php } ?>
        </div>

        <!-- Titre -->
        <div class="form-group">
          <label for="titre">Titre</label>
          <input type="text" name="titre" id="titre" value="<?= $titre; ?>" class="form-control">
        </div>

        <!-- Description -->
        <div class="form-group">
          <label for="description">Description</label>
          <textarea name="description" id="description" rows="2" class="form-control"><?= $description; ?></textarea>
        </div>

        <!-- Affichage Photo -->
        <?php
        // récupération de la photo de l'article en cas de modification. Pour la consever si l'utilisateur n'en charge pas une nouvelle
        if (!empty($photo_actuelle)) {
          echo '<div class="form-group text-center">';
          echo '<label>Photo actuelle</label><hr>';
          echo '<img src="img/' . $photo_actuelle . '" class="w-25 img-thumbnail" alt="image de l\'article">';
          echo '<input name="photo_actuelle" value="' . $photo_actuelle . '">';
          echo '</div>';
        }
        ?>

        <!-- Piece Jointe -->
        <div class="form-group">
          <label for="photo">Photo</label>
          <input type="file" name="photo" id="photo" class="form-control">
        </div>

        <!-- Capacité -->
        <div class="from-group">
          <label for="capacite">Capacité</label>
          <select name="capacite" id="capacite" class="form-control">
            <script>
              // Faire une boucle pour avoir 50 capacité en <option>
              // Ceci est le script le plus optimisé (appel une seule fois à la variable PHP "capacite")
              var capacite = <?= $capacite; ?>;
              for (i = 1; i < 51; i++) {
                document.write('<option ');
                if (i === capacite) {
                  document.write('selected ');
                }
                document.write('value="' + i + '">' + i + '</option>');
              }
            </script>
          </select>
        </div>

        <!-- Catégorie -->
        <div class="from-group">
          <label for="categorie">Categorie</label>
          <select name="categorie" id="categorie" class="form-control">
            <option value="reunion" <?php
                                    if (!empty($categorie) && ($categorie == 'reunion')) {
                                      echo 'selected ';
                                    }
                                    ?>>Réunion</option>
            <option value="formation" <?php
                                      if (!empty($categorie) && ($categorie == 'formation')) {
                                        echo 'selected ';
                                      }
                                      ?>>Formation</option>
            <option value="bureau" <?php
                                    if (!empty($categorie) && ($categorie == 'bureau')) {
                                      echo 'selected ';
                                    }
                                    ?>>Bureau</option>
          </select>
        </div>
      </div>
      <div class="col-6">

        <!-- Pays -->
        <div class="from-group">
          <label for="pays">Pays</label>
          <select name="pays" id="pays" class="form-control">
            <option value="france" <?php
                                    if (!empty($pays) && ($pays == 'france')) {
                                      echo 'selected ';
                                    }
                                    ?>>France</option>
            <option value="corse" <?php
                                  if (!empty($pays) && ($pays == 'corse')) {
                                    echo 'selected ';
                                  }
                                  ?>>Corse</option>
            <option value="DOM" <?php
                                if (!empty($pays) && ($pays == 'DOM')) {
                                  echo 'selected ';
                                }
                                ?>>DOM</option>
          </select>
        </div>
        <!-- Ville -->
        <div class="from-group">
          <label for="ville">Ville</label>
          <input type="text" name="ville" id="ville" value="<?= $ville; ?>" class="form-control">
        </div>

        <!-- Adresse -->
        <div class="from-group">
          <label for="adresse">Adresse</label>
          <textarea name="adresse" id="adresse" rows="2" class="form-control"><?= $adresse; ?></textarea>
        </div>

        <!-- Code Postal -->
        <div class="form-group">
          <label for="cp">Code Postal</label>
          <input type="cp" name="cp" id="cp" value="<?= $cp; ?>" class="form-control">
        </div>
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