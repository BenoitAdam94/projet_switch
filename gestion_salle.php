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



// dump($_SESSION);



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

  js('1isset');

  // echo 'TEST';
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
    js('2if');
  }

  $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $titre);

  if (!$verif_caractere && !empty($titre)) {
    // Message d'erreur
    $msg .= '<div class="alert alert-danger mt-3">Titre invalide, caractères autorisés : a-z et de 0-9</div>';
  }

  // Taille titre entre 4 et 14
  if (iconv_strlen($titre) < 4 || iconv_strlen($titre) > 14) {
    // Message d'erreur
    $msg .= '<div class="alert alert-danger mt-3">titre invalide, le titre doit avoir entre 4 et 14 caractères inclus</div>';
  }



  // S'il n'y pas eu d'erreur au préalable, on doit vérifier si le titre existe déjà dans la BDD
  if (empty($msg)) {
    js('3msgempty');
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

    js('else');

    if (!empty($_FILES['photo']['name'])) {
      $nom_photo = verif_photo_pj();
      dump($nom_photo);
      if ($nom_photo === false) {
        $msg .= '<div class="alert alert-danger mt-3">Attention, le format de la photo est invalide, extensions autorisées : jpg, jpeg, png, gif.</div>';
        // dump($msg);
      }
    }
    
    /*
    if (empty($_FILES['photo']['name']) && $nom_photo === '') {
      $msg .= '<div class="alert alert-danger mt-3">Veuillez attacher une piece jointe</div>';
    }
    */


    if (empty($msg)) {
      if (!empty($_POST['id_salle'])) {
        // si $id_salle n'est pas vide c'est un UPDATE
        $enregistrement = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description , photo = :photo, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie WHERE id_salle = :id_salle");
        // on rajoute le bindParam pour l'id_salle car => modification
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
    dump($photo_actuelle);

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
dump($_POST);
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
          echo '<td><img src="img/' . $salle['photo'] . '" width="100px";> </td>';
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
      <h2>Ajouter une salle</h3>
        <p class="lead"><?php echo $msg; ?></p>
    </div>
  </div>





  <!-- récupération de l'id_article pour la modification -->

  <form method="post" action="" enctype="multipart/form-data">
    <div class="row">


      <div class="col-6">
        <input type="hidden" name="id_salle" value="<?= $id_salle; ?>">
        <div class="form-group">
          <label for="titre">Titre</label>
          <input type="text" name="titre" id="titre" value="<?= $titre; ?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea name="description" id="description" rows="2" class="form-control"><?= $description; ?></textarea>
        </div>
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
        <div class="form-group">
          <label for="photo">Photo</label>
          <input type="file" name="photo" id="photo" class="form-control">
        </div>
        <div class="from-group">
          <label for="capacite">Capacité</label>
          <select name="capacite" id="capacite" class="form-control">
            <script>
              for (i = 1; i < 51; i++) {
                document.write('<option value="' + i + '">' + i + '</option>');
              }
            </script>
          </select>
        </div>
        <div class="from-group">
          <label for="categorie">Categorie</label>
          <select name="categorie" id="categorie" class="form-control">
            <option>bureau</option>
            <option>reunion</option>
            <option>formation</option>
          </select>
        </div>
      </div>
      <div class="col-6">
        <div class="from-group">
          <label for="pays">Pays</label>
          <select name="pays" id="pays" class="form-control">
            <option value="france">France</option>
            <option value="corse">Corse</option>
            <option value="dom">DOM</option>
          </select>
        </div>
        <div class="from-group">
          <label for="ville">Ville</label>
          <select name="ville" id="ville" class="form-control">
            <option value="paris">Paris</option>
            <option value="lyon">Lyon</option>
            <option value="marseille">Marseille</option>
          </select>
        </div>
        <div class="from-group">
          <label for="adresse">Adresse</label>
          <textarea name="adresse" id="adresse" rows="2" class="form-control"><?= $adresse; ?></textarea>
        </div>
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

include "inc/footer.php";
?>