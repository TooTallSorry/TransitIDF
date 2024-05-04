<?php 
declare(strict_types=1);

/**
 * Traduit un texte de l'anglais vers le français en utilisant l'API DeepL.
 *
 * @param string $text Le texte à traduire.
 * @param string $authKey La clé d'authentification API DeepL.
 * @return string Le texte traduit ou un message d'erreur si la traduction échoue.
 */
function translateTextWithDeepL(string $text, string $authKey): string {
    $url = "https://api-free.deepl.com/v2/translate";
    $data = http_build_query([
        'auth_key' => $authKey,
        'text' => $text,
        'source_lang' => 'EN',
        'target_lang' => 'FR',
    ]);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        curl_close($curl);
        return "Erreur lors de la connexion à l'API DeepL: " . curl_error($curl);
    }

    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($statusCode != 200) {
        return "Erreur lors de la traduction avec l'API DeepL. Code de statut: " . $statusCode;
    }

    $result = json_decode($response, true);
    if (!isset($result['translations'][0]['text'])) {
        return "Erreur lors de la récupération de la traduction.";
    }

    return $result['translations'][0]['text'];
}

/**
 * Récupère l'image astronomique du jour (APOD) depuis l'API de la NASA.
 *
 * @param string $apiKey La clé API de la NASA.
 * @return string Le code HTML pour afficher l'APOD ou un message d'erreur.
 */

 function getApod(string $apiKey): string {
    // Définir le fuseau horaire à Eastern Standard Time (heure de l'Est)
    date_default_timezone_set('America/New_York');

    // Construction de l'URL avec la date actuelle en heure de l'Est
    $url = "https://api.nasa.gov/planetary/apod?api_key=" . urlencode($apiKey) . "&date=" . date("Y-m-d");

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($result === false || $httpCode != 200) {
        curl_close($curl);
        return "Impossible de récupérer l'APOD. Veuillez réessayer plus tard.";
    }

    curl_close($curl);

    $data = json_decode($result, true);
    if ($data === null) {
        return "Erreur lors du décodage des données de l'APOD.";
    }

    // Gérer le type de média
    if ($data['media_type'] === 'video') {
        $html = "<iframe width=\"560\" height=\"315\" src=\"" . htmlspecialchars($data['url']) . "\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
    } else {
        $html = "<img src=\"" . htmlspecialchars($data['url']) . "\" alt=\"APOD\"/>";
    }

    // Ajout de l'explication sous l'image ou la vidéo
    $html .= "<p>" . htmlspecialchars($data['explanation']) . "</p>";
    return $html;
}

/**
 * Récupère la localisation approximative d'une adresse IP en format JSON.
 *
 * @param string $ip L'adresse IP à localiser.
 * @return string La localisation approximative (ville, pays).
 */
function getLocalisationJSON(string $ip): string {
    $response = file_get_contents("https://ipinfo.io/{$ip}/geo");
    $data = json_decode($response);
    return "Votre localisation approximative est : " . $data->city . ", " . $data->country;
}
/**
 * Récupère la localisation approximative d'une adresse IP en format JSON.
 *
 * @param string $ip L'adresse IP à localiser.
 * @return string La localisation approximative (ville, pays).
 */
function getLocationJSON(string $ip): string {
    $response = file_get_contents("https://ipinfo.io/{$ip}/geo");
    $data = json_decode($response);
    return "Your location is : " . $data->city . ", " . $data->country;
}
/**
 * Récupère la localisation approximative d'une adresse IP en format XML.
 *
 * @param string $ip L'adresse IP à localiser.
 * @return string La localisation approximative (ville, pays).
 */

function getLocationXML(string $ip): string {
    $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip={$ip}");
    $city = $xml->geoplugin_city;
    $country = $xml->geoplugin_countryName;
    return "Your location is " . $city . ", " . $country;
}
/**
 * Récupère la localisation approximative d'une adresse IP en format XML.
 *
 * @param string $ip L'adresse IP à localiser.
 * @return string La localisation approximative (ville, pays).
 */

 function getLocalisationXML(string $ip): string {
    $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip={$ip}");
    $city = $xml->geoplugin_city;
    $country = $xml->geoplugin_countryName;
    return "Votre localisation approximative est : " . $city . ", " . $country;
}
/**
 * Recherche un itinéraire entre deux gares en utilisant l'API Navitia.
 *
 * @param string $departureId Identifiant de la gare de départ.
 * @param string $arrivalId Identifiant de la gare d'arrivée.
 * @param string $dateTime Date et heure du départ.
 * @param string $apiKey Clé API pour Navitia.
 * @return array Les données de l'itinéraire ou un message d'erreur.
 */

function findJourney(string $departureId, string $arrivalId, string $dateTime, string $apiKey): array {

    $url = "https://api.navitia.io/v1/coverage/fr-idf/journeys?from=" . $departureId . "&to=" . $arrivalId . "&datetime=" . $dateTime;

    // Initialisation de cURL et configuration de la requête
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: " . $apiKey]);
    $response = curl_exec($ch);
    curl_close($ch);

    // Traitement de la réponse
    if ($response) {
        return json_decode($response, true);
    } else {
        return ['error' => 'Erreur lors de la récupération des données.'];
    }
}

/**
 * Charge les données des stations à partir d'un fichier CSV.
 *
 * @param string $csvFilePath Chemin vers le fichier CSV.
 * @return array Tableau des données des stations.
 */

function loadStationData($csvFilePath) {
    $stationData = [];
    if (($handle = fopen($csvFilePath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Supposons que le nom de la gare est dans la première colonne, monitoringRef dans la seconde et lineRef dans la troisième
            $stationName = $data[0];
            $monitoringRef = $data[1];
            $lineRef = $data[2];

            $stationData[$stationName] = [
                'monitoringRef' => $monitoringRef,
                'lineRef' => $lineRef
            ];
        }
        fclose($handle);
    }
    return $stationData;
}

/**
 * Récupère les informations d'une station spécifique.
 *
 * @param string $stationName Nom de la station.
 * @param array $stationData Tableau contenant les données des stations.
 * @return array|null Les données de la station ou null si la station n'est pas trouvée.
 */
function getStationInfo($stationName, $stationData) {
    if (array_key_exists($stationName, $stationData)) {
        return $stationData[$stationName];
    } else {
        return null; // ou retourner une valeur indiquant que la gare n'a pas été trouvée
    }
}

/**
 * Normalise une chaîne de caractères en la convertissant en minuscules et en supprimant les espaces et tirets superflus.
 *
 * @param string $string La chaîne à normaliser.
 * @return string La chaîne normalisée.
 */
function normalizeString($string) {
    // Convertit en minuscules et supprime les tirets et les espaces excédentaires
    $string = strtolower($string);
    $string = str_replace('-', ' ', $string); // Remplace les tirets par des espaces
    $string = preg_replace('/\s+/', ' ', $string); // Remplace plusieurs espaces par un seul
    return trim($string); // Supprime les espaces au début et à la fin
}

/**
 * Filtre les lignes de transport en fonction d'une requête sur leur numéro.
 * 
 * @param array $stationData Les données des stations contenant les informations sur les lignes.
 * @param string $lineQuery La requête de filtrage par numéro de ligne.
 * @return array Un tableau contenant les lignes filtrées organisées par nom de station.
 */
function filterLines($stationData, $lineQuery) {
    $filteredData = [];
    foreach ($stationData as $stationName => $entries) {
        foreach ($entries as $info) {
            if (stripos($info['lineNumber'], $lineQuery) !== false) {
                // Si la ligne correspond à la requête, ajoutez-la au tableau filtré
                if (!isset($filteredData[$stationName])) {
                    $filteredData[$stationName] = [];
                }
                $filteredData[$stationName][] = $info;
            }
        }
    }
    return $filteredData;
}

/**
 * Gère le cookie de style pour le site, définissant le style selon le choix de l'utilisateur.
 *
 * @return string Le style actuel défini par le cookie ou la valeur par défaut.
 */
function manageStyleCookie(): string {
    // Vérifier si un style est spécifié dans l'URL et le valider
    if (isset($_GET['style']) && in_array($_GET['style'], ['standard', 'alternatif'])) {
        $style = $_GET['style'];
        // Définir ou mettre à jour le cookie avec le style choisi
        setcookie('style', $style, time() + (86400 * 30), "/"); // 86400 = 1 jour
        // Rediriger vers la même page pour enlever le paramètre de l'URL
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit;
    }

    // Si le cookie 'style' existe, utiliser sa valeur sinon utiliser un style par défaut
    return $_COOKIE['style'] ?? 'standard'; // 'jour' est le style par défaut
}
/**
 * Gère le cookie de lang pour le site, définissant la langue selon le choix de l'utilisateur.
 *
 * @return string La langue actuel défini par le cookie ou la valeur par défaut.
 */
function manageLangCookie(): string {
    // Vérifier si une langue est spécifiée dans l'URL et la valider
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
        $lang = $_GET['lang'];
        // Définir ou mettre à jour le cookie avec la langue choisie
        setcookie('lang', $lang, time() + (86400 * 30), "/"); // 86400 = 1 jour

        // Rediriger vers la même page pour enlever le paramètre de l'URL
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit;
    }

    // Si le cookie 'lang' existe, utiliser sa valeur sinon utiliser 'fr' comme langue par défaut
    return $_COOKIE['lang'] ?? 'fr';
}
/**
 * Récupère l'identifiant unique d'une station à partir de son nom en utilisant l'API Navitia.
 *
 * @param string $stationName Le nom de la station à rechercher.
 * @param string $apiKey La clé API pour accéder à l'API Navitia.
 * @return string|null L'identifiant de la station si trouvé, sinon null.
 */
function getStationId($stationName, $apiKey) {
    $url = "https://api.navitia.io/v1/coverage/fr-idf/places?q=" . urlencode($stationName);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: ' . $apiKey]);
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response) {
        $data = json_decode($response, true);
        if (!empty($data['places'][0]['id'])) {
            return $data['places'][0]['id'];
        }
    }
    return null;
}

/**
 * Extrait l'identifiant spécifique d'une chaîne d'identifiant complète.
 *
 * @param string $fullId L'identifiant complet contenant des préfixes.
 * @return string L'identifiant extrait après le dernier deux-points.
 */
function extractIdFromFullId(string $fullId): string {
    $parts = explode(':', $fullId);
    return end($parts);
}

/**
 * Convertit une date et une heure au format utilisé par l'API Navitia.
 *
 * @param string $date La date au format 'd-m-Y'.
 * @param string $heure L'heure au format 'H:i'.
 * @return string La date et l'heure formatées selon le standard de Navitia ('YmdTHis').
 */
function convertDateTimeToNavitiaFormat(string $date, string $heure): string {
    $dateTime = DateTime::createFromFormat('d-m-Y H:i', $date . ' ' . $heure);
    if (!$dateTime) {
        return '0';
    }
    return $dateTime->format('Ymd\THis');
}

/**
 * Récupère les informations de départ pour une station spécifique en utilisant l'API Navitia.
 *
 * @param string $stationId L'identifiant de la station.
 * @param string $lineName Le nom de la ligne pour filtrer les départs, facultatif.
 * @param string $apiKey Clé API pour Navitia.
 * @return array Un tableau contenant les départs filtrés ou un message d'erreur.
 */
function getDeparturesFromStation(string $stationId, string $lineName, string $apiKey): array {
    $url = "https://api.navitia.io/v1/coverage/fr-idf/stop_areas/" . $stationId . "/departures";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: " . $apiKey]);
    $response = curl_exec($ch);
    curl_close($ch);
    if (!$response) {
        return ['error' => 'Erreur lors de la récupération des données.'];
    }

    $departuresData = json_decode($response, true);
    if (isset($departuresData['error'])) {
        return ['error' => 'Erreur lors de la récupération des données.'];
    }

    $departuresFiltered = [];
    foreach ($departuresData['departures'] as $departure) {
        $vehicleType = $departure['display_informations']['commercial_mode']; // Exemple : bus, train...
        $lineCode = $departure['display_informations']['code'];
        $direction = $departure['display_informations']['direction'];
        $departureTime = date('H:i', strtotime($departure['stop_date_time']['departure_date_time']));

        // Si aucun nom de ligne n'est spécifié ou si le nom de ligne correspond
        if (empty($lineName) || stripos($lineCode, $lineName) !== false) {
            if (!array_key_exists($vehicleType, $departuresFiltered)) {
                $departuresFiltered[$vehicleType] = [];
            }
            $departuresFiltered[$vehicleType][] = [
                'lineCode' => $lineCode,
                'direction' => $direction,
                'departureTime' => $departureTime
            ];
        }
    }

    // Vérifie si des départs ont été trouvés
    if (empty($departuresFiltered)) {
        return ['error' => 'Aucun départ trouvé pour les critères spécifiés.'];
    }

    return $departuresFiltered;
}

/**
 * Récupère des images aléatoires de trains depuis l'API Unsplash.
 *
 * @param string $apiKey La clé API client pour Unsplash.
 * @return array Un tableau contenant les informations des images récupérées ou un message d'erreur.
 */
function getRandomTrainImages($apiKey) {
    $url = "https://api.unsplash.com/photos/random?client_id=" . $apiKey . "&count=4&query=train";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}
/**
 * Crée un histogramme SVG pour visualiser les données.
 *
 * @param array $data Les données à afficher.
 * @param array $labels Les étiquettes pour chaque barre de l'histogramme.
 * @param array $colors Les couleurs pour chaque barre.
 * @param string $title Le titre de l'histogramme.
 * @param bool $isItinerary Indique si les données concernent des itinéraires (ajuste l'espacement).
 * @return string Le code SVG de l'histogramme.
 */
function createHistogram($data, $labels, $colors, $title, $isItinerary = false) {
    $maxValue = max($data);
    $svgWidth = 1000; // Largeur totale du SVG
    $svgHeight = 500; // Hauteur ajustée pour les itinéraires
    $barWidth = 60; // Largeur des barres
    $gap = $isItinerary ? 100 : 50; // Espacement entre les barres
    $labelSpace = 100; // Espace réservé pour les légendes
    $initialGap = 40;  // Gap uniforme à gauche pour les deux histogrammes

    $svg = "<svg width='$svgWidth' height='$svgHeight' xmlns='http://www.w3.org/2000/svg'>";
    $svg .= "<text x='10' y='20' font-weight='bold'>$title</text>";

    for ($i = 0; $i < count($data); $i++) {
        $barHeight = ($data[$i] / $maxValue) * ($svgHeight - $labelSpace - 30);
        $x = $initialGap + ($i * ($barWidth + $gap));
        $y = $svgHeight - $barHeight - $labelSpace;
        $svg .= "<rect x='$x' y='$y' width='$barWidth' height='$barHeight' fill='{$colors[$i % count($colors)]}'/>";

        $text = (string)$data[$i];
        $textX = $x + $barWidth / 2;
        $textYInside = $y + 20; 
        $textYAbove = $y - 5; 

        $textY = ($barHeight > 30) ? $textYInside : $textYAbove;

        $svg .= "<text x='$textX' y='$textY' text-anchor='middle' font-size='12' fill='" . (($barHeight > 30) ? '#ffffff' : '#000000') . "'>$text</text>";

        // Gestion du texte pour les noms plus longs
        $labelX = $x + $barWidth / 2;
        $labelY = $svgHeight - 75; 
        $labelText = wordwrap($labels[$i], $isItinerary ? 20 : 10, "\n", true); 
        $lines = explode("\n", $labelText);
        foreach ($lines as $lineNumber => $line) {
            $svg .= "<text x='$labelX' y='" . ($labelY + 15 * $lineNumber) . "' text-anchor='middle' font-size='12'>$line</text>";
        }
    }

    $svg .= "</svg>";
    return $svg;
}

/**
 * Incrémente le compteur de visites et renvoie le nombre total de visites.
 *
 * @return int Le nombre total de visites.
 */
function updateVisitCounter() {
    // Chemin vers le fichier compteur
    $fileName = 'counter.txt';

    // Lire le nombre actuel de visites
    if (file_exists($fileName)) {
        $hits = file_get_contents($fileName);
        $hits = $hits ? $hits : 0;
    } else {
        $hits = 0;
    }

    // Incrémenter le compteur
    $hits++;

    // Sauvegarder le nouveau nombre de visites
    file_put_contents($fileName, $hits);

    // Retourner le nombre de visites pour affichage
    return $hits;
}

function getStationNameById(string $stationId, string $apiKey): ?string {
    $url = "https://api.navitia.io/v1/coverage/fr-idf/stop_areas/{$stationId}";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: " . $apiKey]);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        return null;
    }

    $stationData = json_decode($response, true);

    if (isset($stationData['error'])) {
        return null;
    }

    if (isset($stationData['stop_areas'][0]['label'])) {
        return $stationData['stop_areas'][0]['label'];
    }

    return null;
}


?>
