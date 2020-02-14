<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?php echo URL; ?>index.php">SWITCH</a>
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
            <a class="nav-link" href="<?php echo URL; ?>inscription.php">Inscription</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URL; ?>connexion.php">Connexion</a>
          </li>

        <?php } else { ?>

          <li class="nav-item">
            <a class="nav-link" href="<?php echo URL; ?>profil.php">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo URL; ?>connexion.php?action=deconnexion">Déconnexion</a>
          </li>

        <?php } ?>



        <?php if (user_is_admin()) : ?>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Administration</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_article.php">Gestion des articles</a>
              <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_membre.php">Gestion des membres</a>
              <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_commande.php">Gestion des commandes</a>
              <a class="dropdown-item" href="<?php echo URL; ?>admin/statistiques.php">Statistiques</a>
            </div>
          </li>

        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>