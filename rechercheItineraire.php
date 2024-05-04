<?php
declare(strict_types=1);
session_start();

require_once('include/functions.inc.php');
require_once('include/util.inc.php');

$lang = manageLangCookie();
$style = manageStyleCookie();
$pageTitle = "Page Itineraire";
$pageDescription = "Horaire et Itinéraire";
$body = "bodyIndex";

$apiKey = 'dabd7771-f8b6-4d4f-9255-bb3972fc1eb5'; // Utilisez votre clé API Navitia

$departNom = '';
$arriveeNom = '';
$date = ''; // Supprimez cette ligne pour que la valeur soit déterminée par les données du formulaire
$heure = '';
$journeyData = []; // Assurez-vous d'initialiser cette variable pour stocker les résultats de la recherche

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departNom = htmlspecialchars($_POST['depart']);
    $arriveeNom = htmlspecialchars($_POST['arrivee']);
    $date = htmlspecialchars($_POST['date']);
    $heure = htmlspecialchars($_POST['heure']);

    $file = 'search_log.csv';
    $line = "Itineraire," . $departNom . "," . $arriveeNom . "\n";
    file_put_contents($file, $line, FILE_APPEND);

    $_SESSION['search'] = [
        'depart' => $departNom,
        'arrivee' => $arriveeNom,
        'date' => $date,
        'heure' => $heure
    ];

    $dateTimeString = $date . ' ' . $heure . ':00';
    try {
        $dateTime = new DateTime($dateTimeString);
        $formattedDateTime = $dateTime->format('Ymd\THis');

        $departId = getStationId($departNom, $apiKey);
        $arrivalId = getStationId($arriveeNom, $apiKey);

        // Avant d'appeler getStationNameById, assurez-vous que les IDs ne sont ni null ni vides
        if ($departId && $arrivalId) {
            $stationFullNameDepart = getStationNameById($departId, $apiKey);
            $stationFullNameArrival = getStationNameById($arrivalId, $apiKey);
            $journeyData = findJourney($departId, $arrivalId, $formattedDateTime, $apiKey);
        } else {
            echo "<p>Erreur : Impossible de trouver les stations avec les noms fournis.</p>";
        }
    } catch (Exception $e) {
        echo "Erreur lors de la création de l'objet DateTime : " . $e->getMessage();
    }
} else {
    // Tentative de pré-remplissage du formulaire avec les données de la session si disponibles
    if (isset($_SESSION['search'])) {
        $departNom = $_SESSION['search']['depart'];
        $arriveeNom = $_SESSION['search']['arrivee'];
        $date = $_SESSION['search']['date'];
        $heure = $_SESSION['search']['heure'];
    }
}

require_once("include/{$lang}header.inc.php");
require_once("include/{$lang}.inc.rechercheItineraire.php");
require_once("include/{$lang}footer.inc.php"); ?>
