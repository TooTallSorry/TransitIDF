<?php
declare(strict_types=1);
session_start();

require_once('include/functions.inc.php');
require_once('include/util.inc.php');

$lang = manageLangCookie();
$style = manageStyleCookie();
$pageTitle = "Recherche de Gare";
$pageDescription = "Recherchez des informations sur les gares";
$body = "bodyIndex";
$apiKey = 'dabd7771-f8b6-4d4f-9255-bb3972fc1eb5'; // Utilisez votre clé API Navitia

$departures = []; // Initialiser la variable pour stocker les résultats des départs
$error = ''; // Variable pour stocker les messages d'erreur

// Gestion de la soumission du formulaire de recherche de départs
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['stationName'])) {
    $stationName = $_POST['stationName'];
    $lineName = $_POST['lineName'] ?? ''; // Le nom de la ligne est optionnel
    $file = 'search_log.csv';
    $line = "Gare," . $stationName . "\n"; // Ajout de l'identifiant "Gare"
    file_put_contents($file, $line, FILE_APPEND);

    // Obtenir l'ID de la station à partir de son nom
    $stationId = getStationId($stationName, $apiKey);

    if ($stationId) {
        // Obtenir les départs de la station
        $departures = getDeparturesFromStation($stationId, $lineName, $apiKey);
        $stationFullName = getStationNameById($stationId, $apiKey);

        if (isset($departures['error'])) {
            $error = $departures['error'];
        }
    } else {
        $error = "Station non trouvée.";
    }
}

require_once("include/{$lang}header.inc.php");
require_once("include/{$lang}.inc.rechercheGare.php");
require_once("include/{$lang}footer.inc.php"); ?>
