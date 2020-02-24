<?php
include 'inc/init.inc.php';
$debug = 0;
include 'inc/tools.php';

$id_avis = 15;
$id_salle = 1;
$id_membre = $_SESSION['membre']['id_membre'];
$date = new DateTime();
$date_enregistrement = $date->format('Y-m-d H:i:s');

$tab = array();
$tab['message'] = '';
$tab['connexion'] = '';


if (
  isset($_POST['note']) &&
  isset($_POST['commentaire'])
) {
  $note = $_POST['note'];
  $commentaire = trim($_POST['commentaire']);


  if (!empty($id_avis)) {
    // Si il y a déjà un avis c'est un UPDATE

    $enregistrement = $pdo->prepare("UPDATE avis SET note = :note, commentaire = :commentaire
                                        WHERE id_salle = :id_salle
                                         AND id_membre = :id_membre");
    $enregistrement->bindParam(":id_salle", $id_salle, PDO::PARAM_STR);

    $tab['message'] = 'Avis modifié !';
    

  } else {

    // sinon un INSERT
    $enregistrement = $pdo->prepare("INSERT INTO avis (id_avis, id_membre, id_salle, note, commentaire, date_enregistrement) VALUES (NULL, :id_membre, :id_salle, :note, :commentaire, :date_enregistrement)");

    $enregistrement->bindParam(':date_enregistrement', $date_enregistrement, PDO::PARAM_STR);

    $tab['message'] = 'Avis ajouté !';
    

  }


  // On déclenche l'insertion
  // js('insertion');

  $enregistrement->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
  $enregistrement->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
  $enregistrement->bindParam(':note', $note, PDO::PARAM_STR);
  $enregistrement->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);

  $enregistrement->execute();

  
}

echo json_encode($tab);

?>