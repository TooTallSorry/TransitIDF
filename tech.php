<?php
declare(strict_types=1);
require_once('include/functions.inc.php');
require_once('include/util.inc.php');

$lang = manageLangCookie();
$style = manageStyleCookie();
$pageTitle = "Page Tech";
$pageDescription = "Affichage APOD, Localisation XML, Localisation JSON";
$body = "bodyTD";
if (isset($_GET['style']) && !empty($_GET['style'])) {
    $style = $_GET['style'];
    $img ="zhtmlBlack";
    $imgFac ="cergyBlack";
}
$apiKeyApod = "akIm0Pn4TjxZ2qNFm7Di1uUmqfVZEgDKpvkHngFT";
$ip = $_SERVER['REMOTE_ADDR'];
$apiKeyDeepL = "9402dc45-f98d-41cb-bd7c-fa2103cafbce:fx";
$explanationAPOD = getApod($apiKeyApod);

require_once("include/{$lang}header.inc.php");
require_once("include/{$lang}.inc.tech.php");
require_once("include/{$lang}footer.inc.php"); ?>
