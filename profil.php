<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


if(!user_is_connect()) {
	header('location:connexion.php');
}


include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">
  <!-- 1 rst row -->
  <div class="row">

    <div class="starter-template">
      <h1><i class="fas fa-user" style="color: #4c6ef5;"></i> Profil <i class="fas fa-user" style="color: #4c6ef5;"></i></h1>
      <p class="lead"><?php echo $msg; ?></p>
    </div>

    <div class="row">
      <div class="col-12">
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
              <li class="list-group-item">Ville : <b><?php echo ucfirst($_SESSION['membre']['ville']); ?></b></li>
              <li class="list-group-item">Code postal : <b><?php echo $_SESSION['membre']['cp']; ?></b></li>
              <li class="list-group-item">Adresse : <b><?php echo ucfirst($_SESSION['membre']['adresse']); ?></b></li>
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
      </div>
    </div>

  </div>
  <!-- /.row -->

</div>
<!-- /.container -->

<?php

include "inc/footer.php";
?>