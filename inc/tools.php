<?php

// Cookies

$_1year_in_seconds = 31536000;
$_2year_in_seconds = 63072000;

//dump

function dump($dump) {
    echo '<pre>';
    var_dump($dump);
    echo '</pre>';
}

function sep() {
    echo '<hr>';
}

function separateur() {
    echo '<hr><hr><hr>';
}

// Javascript Console for PHP

function js($js) {
    echo '<script>';
    echo "console.log('PHP LOG == ' + '$js');";
    echo '</script>';
}

function info($js) {
    echo '<script>';
    echo "console.info('PHP INFO == ' + '$js');";
    echo '</script>';
}

// Javascript Alert New Mdp for PHP

function alert($js) {
    echo '<script>';
    echo "alert('$js')";
    echo '</script>';
}

function alertnewmdp($alert_pseudo,$alert_mdp) {
    echo '<script>';
    echo "alert('Nouveau mot de passe pour ' + '$alert_pseudo' + ' : ' + '$alert_mdp')";
    echo '</script>';
}

// Informations

if($debug == 1) {
    dump($_COOKIE);
    dump($_SERVER);
    dump($_POST);
    dump($_GET);
    }

function genererChaineAleatoire($longueur, $listeCar = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $chaine = '';
    $max = mb_strlen($listeCar, '8bit') - 1;
    for ($i = 0; $i < $longueur; ++$i) {
    $chaine .= $listeCar[random_int(0, $max)];
    }
    return $chaine;
}
// Utilisation de la fonction
// echo genererChaineAleatoire(10, 'abcdefghijklmnopqrstuvwxyz');
// echo genererChaineAleatoire(10)
?>