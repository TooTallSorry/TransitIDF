<main>
<a href="#" class="btn">
    <img src="./images/arrow.png" class="icone" alt="Fleche vers le haut"/>
</a>
    <h1>Recherche d'Itinéraire</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display: flex; align-items: center;">
        <label for="depart">Gare de départ:</label>
        <input type="text" id="depart" name="depart" placeholder="Gare de départ" value="<?php echo isset($_POST['depart']) ? htmlspecialchars($_POST['depart']) : ''; ?>"/>

        <label for="arrivee">Gare d'arrivée:</label>
        <input type="text" id="arrivee" name="arrivee" placeholder="Gare d'arrivée" value="<?php echo isset($_POST['arrivee']) ? htmlspecialchars($_POST['arrivee']) : ''; ?>"/>

        <label for="date">Date de départ:</label>
        <input type="date" id="date" name="date" style="margin-right: 10px;" value="<?php echo isset($_POST['date']) ? $_POST['date'] : ''; ?>"/>

        <label for="heure">Heure de départ:</label>
        <input type="time" id="heure" name="heure" style="margin-right: 10px;" value="<?php echo isset($_POST['heure']) ? $_POST['heure'] : ''; ?>"/>

        <input type="submit" value="Rechercher"/>
    </form>

    <?php if (isset($journeyData) && !empty($journeyData['journeys'])): ?>
    <section id="itineraire">
        <?php if (!empty($stationFullNameDepart) && !empty($stationFullNameArrival)): ?>
            <h2>Détails du voyage de <?= htmlspecialchars($stationFullNameDepart) ?> à <?= htmlspecialchars($stationFullNameArrival) ?></h2>
        <?php endif; ?>
        <?php
        // Simule la récupération des détails du voyage (remplacez par votre appel API réel)
        if (!empty($journeyData['journeys'])) {
            $journey = $journeyData['journeys'][0]; // Prendre le premier itinéraire proposé
            $totalDuration = gmdate("H:i", $journey['duration']);

            echo "<p>Début de l'itinéraire avec l'heure de départ la plus tôt (Durée totale: $totalDuration).</p>";

            foreach ($journey['sections'] as $section) {
                // Convertir les timestamps en heures locales lisibles
                $departureTime = date("H:i", strtotime($section['departure_date_time']));
                $arrivalTime = date("H:i", strtotime($section['arrival_date_time']));

                // Obtenir le nom de l'arrêt de départ et d'arrivée si disponible
                $departureStop = $section['from']['name'] ?? '';
                $arrivalStop = $section['to']['name'] ?? '';

                if ($departureTime == $arrivalTime && ($section['type'] == 'crow_fly' || $section['type'] == 'waiting')) {
                    // Si le temps de départ et d'arrivée sont identiques pour les sections de marche ou d'attente, ne rien afficher
                    continue; // Passe à la section suivante sans afficher de message
                }
                // Afficher l'heure de départ pour chaque type de transport
                if ($section['type'] == 'public_transport') {
                    $line = $section['display_informations']['label'];
                    $code = $section['display_informations']['code'];
                    $network = $section['display_informations']['network'];
                    $direction = $section['display_informations']['direction'];
                    echo "<p><strong>Départ à $departureTime de l'arrêt $departureStop:</strong> Prendre le $network $line en direction de $direction. Arrivée prévue à $arrivalTime à l'arrêt $arrivalStop.</p>";
                } elseif ($section['type'] == 'transfer') {
                    echo "<p><strong>Correspondance prévu à partir de $departureTime à l'arrêt $departureStop :</strong> Arrivée au point de correspondance $arrivalStop à $arrivalTime.</p>";
                } elseif ($section['type'] == 'waiting') {
                    echo "<p><strong>Attente à l'arrêt $departureStop :</strong> De $departureTime à $arrivalTime.</p>";
                } elseif ($section['type'] == 'crow_fly' || $section['type'] == 'street_network') {
                    // Trajet à pied entre les sections de transport
                    echo "<p><strong>Marche de l'arrêt $departureStop à l'arrêt $arrivalStop :</strong> De $departureTime à $arrivalTime.</p>";
                } else {
                    // Autres types de sections
                    $mode = ucfirst($section['type']);
                    echo "<p><strong>$mode de l'arrêt $departureStop à l'arrêt $arrivalStop :</strong> De $departureTime à $arrivalTime.</p>";
                }
            }
            echo "<p>Fin de l'itinéraire à l'arrêt $arrivalStop à $arrivalTime.</p>";
        } else {
            echo "<p>Aucun itinéraire trouvé pour les paramètres spécifiés ou veuillez vérifier votre saisie (Appuyer sur le bouton Rechercher pour revérifier).</p>";
        }
        ?>
    </section>
<?php elseif (isset($journeyData)): ?>
    <p>Aucun itinéraire trouvé pour les paramètres spécifiés.</p>
<?php endif; ?>
</main>