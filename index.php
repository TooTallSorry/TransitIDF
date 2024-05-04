<?php
session_start(); // Cette ligne doit être la première avant toute sortie HTML et avant toute manipulation de session

require_once('include/util.inc.php');
require_once('include/functions.inc.php');

$style = manageStyleCookie(); // Gérer le style à partir des cookies ou des paramètres GET

$pageTitle = "TransitIDF";
$pageDescription = "Page de liaison entre les pages du site web";
$body = "bodyIndex";

// Gestion de la langue
$lang = manageLangCookie();

// Gestion du téléchargement des images
if (isset($_POST['download'])) {
    $resultMessage = downloadUnsplashImages();  // Appel de la fonction de téléchargement d'images
    $_SESSION['message'] = $resultMessage;  // Stockage du message dans la session
    header("Location: " . $_SERVER['PHP_SELF']);  // Redirection pour éviter la resoumission du formulaire
    exit;
}

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

// Gestion des styles via GET request
if (isset($_GET['style']) && !empty($_GET['style'])) {
    $style = $_GET['style'];
    // Gestion des styles additionnels ici si nécessaire
}

// Inclusion des fichiers spécifiques à la langue
require_once("include/{$lang}header.inc.php");
require_once("include/{$lang}.inc.php");
require_once("include/{$lang}footer.inc.php");
?>
