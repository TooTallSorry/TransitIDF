<?php
declare(strict_types=1);
session_start();

require_once('include/functions.inc.php');
require_once('include/util.inc.php');

$lang = manageLangCookie();
$style = manageStyleCookie();
$pageTitle = "Statistiques des Recherches";
$pageDescription = "Visualisez les statistiques des gares et itinéraires les plus recherchés";
$body = "bodyIndex";
$apiKey = 'dabd7771-f8b6-4d4f-9255-bb3972fc1eb5'; // Utilisez votre clé API Navitia

$filename = 'search_log.csv';
$gareSearches = [];
$itineraireSearches = [];

// Lire et traiter le fichier CSV pour les statistiques
if (file_exists($filename)) {
    $file = fopen($filename, "r");
    while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
        if ($data[0] == "Gare") {
            $searchKey = strtolower(trim($data[1])); 
            if (!isset($gareSearches[$searchKey])) {
                $gareSearches[$searchKey] = 0;
            }
            $gareSearches[$searchKey]++;
        } elseif ($data[0] == "Itineraire") {
            $depart = strtolower(trim($data[1]));
            $arrivee = strtolower(trim($data[2]));
            $searchKey = $depart . " - " . $arrivee;
            if (!isset($itineraireSearches[$searchKey])) {
                $itineraireSearches[$searchKey] = 0;
            }
            $itineraireSearches[$searchKey]++;
        }
    }
    fclose($file);
}

// Trier et préparer les données pour l'affichage
arsort($gareSearches);
arsort($itineraireSearches);

$topGares = array_slice($gareSearches, 0, 5);
$topItineraires = array_slice($itineraireSearches, 0, 5);

$gareData = array_values($topGares);
$gareLabels = array_keys($topGares);
$itineraireData = array_values($topItineraires);
$itineraireLabels = array_keys($topItineraires);
$colors = ['#FF6384', '#36A2EB', '#FFCE56', '#8A2BE2', '#00BFFF'];

$gareHistogram = createHistogram($gareData, $gareLabels, $colors, "Gares les Plus Recherchées");
$itineraireHistogram = createHistogram($itineraireData, $itineraireLabels, $colors, "Itinéraires les Plus Recherchés", true);
$stationHistogram = createHistogram($gareData, $gareLabels, $colors, "Most Searched Stations");
$itineraryHistogram = createHistogram($itineraireData, $itineraireLabels, $colors, "Most Searched Itineraries", true);

require_once("include/{$lang}header.inc.php");
require_once("include/{$lang}.inc.statistiques.php");
require_once("include/{$lang}footer.inc.php"); ?>
