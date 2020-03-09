<?php

// Connexion à la BDD
$host_db = 'mysql:host=db5000317889.hosting-data.io;dbname=dbs309909'; 
$login = 'dbu572124'; 
$password = 'Num4Num@'; 
$options = array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' 
				);				
$pdo = new PDO($host_db, $login, $password, $options);

// Création d'une variable destinée à afficher des messages utilisateur
$msg = "";

// ouverture d'une session
session_start();

// déclaration de constante
// URL racine du projet
define('URL', 'http://benoit-sakote.fr/projet_switch/'); // lien absolu racine du projet
// Chemin racine du serveur
define('SERVER_ROOT', $_SERVER['DOCUMENT_ROOT']);
// Chemin racine du dossier du site depuis le serveur
define('SITE_ROOT', '/projet_switch/');









