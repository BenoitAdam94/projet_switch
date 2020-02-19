<?php
$debug = 0;
include 'inc/tools.php';
include 'inc/init.inc.php';
include 'inc/fonction.inc.php';


// Categorie = Bureau / Formation / Réunion




// ****************************************************************
// Definition des valeurs par défaut pour les Slider-Range jQuery
// ****************************************************************

if (empty($price_min)) {
  $price_min = '0';
}

if (empty($price_max)) {
  $price_max = '1500';
}

if (empty($capacite_min)) {
  $capacite_min = '0';
}

if (empty($capacite_max)) {
  $capacite_max = '40';
}


// ********
// Filtres
// ********

if (
  isset($_GET['categorie']) &&
  isset($_GET['pays']) &&
  isset($_GET['capacitemin']) &&
  isset($_GET['capacitemax']) &&
  isset($_GET['pricemin']) &&
  isset($_GET['pricemax'])
) {

  $categorie = $_GET['categorie'];
  $pays = $_GET['pays'];
  $capacite_min = &$_GET['capacitemin'];
  $capacite_max = &$_GET['capacitemax'];
  $price_min = $_GET['pricemin'];
  $price_max = $_GET['pricemax'];

  $liste_produit = $pdo->prepare("SELECT * FROM produit, salle
                                    WHERE produit.id_salle = salle.id_salle
                                    AND categorie = :categorie
                                    AND pays = :pays
                                    AND capacite > :capacitemin
                                    AND capacite < :capacitemax
                                    AND prix > :pricemin
                                    AND prix < :pricemax
                                    ORDER BY date_arrivee");
  $liste_produit->bindParam(':categorie', $categorie, PDO::PARAM_STR);
  $liste_produit->bindParam(':pays', $pays, PDO::PARAM_STR);
  $liste_produit->bindParam(':capacitemin', $capacite_min, PDO::PARAM_STR);
  $liste_produit->bindParam(':capacitemax', $capacite_max, PDO::PARAM_STR);
  $liste_produit->bindParam(':pricemin', $price_min, PDO::PARAM_STR);
  $liste_produit->bindParam(':pricemax', $price_max, PDO::PARAM_STR);
  $liste_produit->execute();
} else {

  $liste_produit = $pdo->query("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle ORDER BY date_arrivee");
}





include 'inc/header.php';
include 'inc/navbar.php';
?>


<!-- Page Content -->
<div class="container">

  <div class="row">

    <div class="col-lg-3">

      <form>

        <!-- Catégorie -->
        <div>
          <h3 class="my-4 border"><label for="categorie">Catégorie</label></h3>
          <select id="categorie" name="categorie" required>
            <option selected disabled>Choisir une categorie</option>
            <option value="reunion" <?php
                                    if (!empty($_GET['categorie']) && ($_GET['categorie'] == 'reunion')) {
                                      echo 'selected ';
                                    }
                                    ?>>Réunion</option>
            <option value="formation" <?php
                                      if (!empty($_GET['categorie']) && ($_GET['categorie'] == 'formation')) {
                                        echo 'selected ';
                                      }
                                      ?>>formation</option>
            <option value="bureau" <?php
                                    if (!empty($_GET['categorie']) && ($_GET['categorie'] == 'bureau')) {
                                      echo 'selected ';
                                    }
                                    ?>>Bureau</option>
          </select>
        </div>

        <!-- Pays -->

        <div>
          <h3 class="my-4 border"><label for="pays">Pays</label></h3>
          <select id="pays" name="pays" required>
            <option selected disabled>Choisir un Pays</option>
            <option value="france" <?php
                                    if (!empty($_GET['pays']) && ($_GET['pays'] == 'france')) {
                                      echo 'selected ';
                                    }
                                    ?>>France</option>
            <option value="corse" <?php
                                  if (!empty($_GET['pays']) && ($_GET['pays'] == 'corse')) {
                                    echo 'selected ';
                                  }
                                  ?>>Corse</option>
            <option value="DOM" <?php
                                if (!empty($_GET['pays']) && ($_GET['pays'] == 'DOM')) {
                                  echo 'selected ';
                                }
                                ?>>DOM</option>
          </select>
        </div>


        <!-- Capacité -->

        <h3 class="my-4 border"><label for="amount-capacite">Capacité</label></h3>
        <input type="text" id="amount-capacite" readonly style="border:0; color:#f6931f; font-weight:bold;">

        <div id="slider-range-capacite"></div>
        <input id="capacitemin" name="capacitemin" type="hidden" value="<?= $capacite_min; ?>">
        <input id="capacitemax" name="capacitemax" type="hidden" value="<?= $capacite_max; ?>">


        <!-- Prix -->

        <div class="list-group">

          <h3 class="my-4 border"><label for="amount-prix">Prix :</label></h3>
          <input type="text" id="amount-prix" readonly style="border:0; color:#f6931f; font-weight:bold;">

          <div id="slider-range-prix"></div>
          <input id="pricemin" name="pricemin" type="hidden" value="<?= $price_min; ?>">
          <input id="pricemax" name="pricemax" type="hidden" value="<?= $price_max; ?>">
        </div>


        <!-- Période -->

        <div>
          <h3 class="my-4 border">Période</h3>

        </div>


        <!-- Validation -->

        <div>
          <button class="mt-2" type="submit" id="pricerange" class="form-control btn btn-outline-dark">Filtrer</button>
        </div>


      </form>
    </div>



    <!-- /.col-lg-3 -->

    <div class="col-lg-9">

      <br>

      <div class="row">



        <?php
        // ****************************************************************
        // Boucle pour l'affichage des produits
        // ****************************************************************
        while ($produit = $liste_produit->fetch(PDO::FETCH_ASSOC)) {
        ?>

          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
              <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>"><img class="card-img-top" src="img/<?= $produit['photo'] ?>" alt="<?= $produit['photo'] ?>"></a>
              <div class="card-body">
                <h4 class="card-title">
                  <a href="fiche_produit.php?id_produit=<?= $produit['id_produit'] ?>"><?= $produit['titre'] ?></a>
                  <h6><?php if ($produit['categorie'] === 'reunion') {
                        echo '<i class="fas fa-users"></i> Reunion';
                      } else if ($produit['categorie'] === 'bureau') {
                        echo '<i class="fas fa-briefcase"></i> Bureau';
                      } else if ($produit['categorie'] === 'formation') {
                        echo '<i class="fas fa-chalkboard-teacher"></i> Formation';
                      }; ?></h6>

                </h4>
                <h5><?= $produit['prix'] ?> €</h5>
                <h6><?= $produit['pays'] ?> - <?= $produit['ville'] ?></h6>
                <p class="card-text"><?= $produit['date_arrivee'] ?></p>
              </div>
              <div class="card-footer">
                <small class="text-muted">&#9733; avis indisponible</small>

              </div>
            </div>
          </div>

        <?php
          // ** Fin de la boucle ** //
        }
        ?>



      </div>
      <!-- /.row -->

    </div>
    <!-- /.col-lg-9 -->

  </div>
  <!-- /.row -->

</div>
<!-- /.container -->

<?php
include "ajax_connexion.php";
include "inc/footer_script.php";
include "inc/footer.php";
?>
<script>
  $(function() {
    $("#slider-range-prix").slider({
      range: true,
      min: 0,
      max: 1500,
      values: [<?= $price_min; ?>, <?= $price_max; ?>],
      slide: function(event, ui) {
        $("#amount-prix").val("€" + ui.values[0] + " - €" + ui.values[1]);
        $("#pricemin").val(ui.values[0]);
        $("#pricemax").val(ui.values[1]);
      }
    });
    $("#amount-prix").val("€" + $("#slider-range-prix").slider("values", 0) + " - €" + $("#slider-range-prix").slider("values", 1));
  });
  $(function() {
    $("#slider-range-capacite").slider({
      range: true,
      min: 0,
      max: 40,
      values: [<?= $capacite_min; ?>, <?= $capacite_max; ?>],
      slide: function(event, ui) {
        $("#amount-capacite").val(ui.values[0] + " - " + ui.values[1]);
        $("#capacitemin").val(ui.values[0]);
        $("#capacitemax").val(ui.values[1]);
      }
    });
    $("#amount-capacite").val($("#slider-range-capacite").slider("values", 0) + " - " + $("#slider-range-capacite").slider("values", 1));
  });
</script>