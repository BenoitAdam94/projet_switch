<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">SWITCH</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Accueil
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <?php if (!user_is_connect()) { ?>

          <li class="nav-item">
            <a class="nav-link" href="inscription.php">Inscription</a>
          </li>
          <li>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#connexion">
              Connexion
            </button>
          </li>

        <?php } else { ?>

          <li class="nav-item">
            <a class="nav-link" href="profil.php">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?action=deconnexion">Déconnexion</a>
          </li>

        <?php } ?>



        <?php if (user_is_admin()) : ?>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Administration</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="gestion_salle.php">Gestion des salles</a>
              <a class="dropdown-item" href="gestion_produit.php">Gestion des produits</a>
              <a class="dropdown-item" href="gestion_membre.php">Gestion des membres</a>
              <a class="dropdown-item" href="gestion_avis.php">Gestion des avis</a>
              <a class="dropdown-item" href="gestion_commande.php">Gestion des commandes</a>
              <a class="dropdown-item" href="statistiques.php">Statistiques</a>
            </div>
          </li>

        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>





<div class="modal fade" id="connexion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Connexion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="" id="form_connexion">
          <div class="form-group">
            <label>Pseudo</label>
            <input type="text" name="pseudo" value="" id="pseudo" class="form-control">
          </div>
          <div class="form-group">
            <label>Mot de passe</label>
            <input type="text" name="mdp" value="" id="mdp" class="form-control">
          </div>
          <div class="form-group">

            <input type="submit" name="connexion" value="Connexion" id="connexion" class="form-control btn btn-primary">
          </div>

          <hr>
          <div id="resultat"></div>
        </form>
        <p><?= $msg; ?></p>
      </div>
    </div>
  </div>
</div>