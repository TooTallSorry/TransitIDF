<main>
    <a href="#" class="btn">
        <img src="./images/arrow.png" class="icone" alt="Up Arrow"/>
    </a>
    <h1>Itinerary Search</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display: flex; align-items: center;">
        <label for="depart">Departure Station:</label>
        <input type="text" id="depart" name="depart" placeholder="Departure station" value="<?php echo isset($_POST['depart']) ? htmlspecialchars($_POST['depart']) : ''; ?>"/>

        <label for="arrivee">Arrival Station:</label>
        <input type="text" id="arrivee" name="arrivee" placeholder="Arrival station" value="<?php echo isset($_POST['depart']) ? htmlspecialchars($_POST['depart']) : ''; ?>"/>

        <label for="date">Date of Departure:</label>
        <input type="date" id="date" name="date" style="margin-right: 10px;" value="<?= isset($date) ? htmlspecialchars($date) : ''; ?>" />

        <label for="heure">Time of Departure:</label>
        <input type="time" id="heure" name="heure" style="margin-right: 10px;" value="<?= isset($heure) ? htmlspecialchars($heure) : ''; ?>" />

        <input type="submit" value="Search" />
    </form>

    <?php if (isset($journeyData) && !empty($journeyData['journeys'])): ?>
    <section id="itinerary">
        <?php if (!empty($stationFullNameDepart) && !empty($stationFullNameArrival)): ?>
            <h2>Travel Details from <?= htmlspecialchars($stationFullNameDepart) ?> to <?= htmlspecialchars($stationFullNameArrival) ?></h2>
        <?php endif; ?>
        <?php
        // Simulate fetching travel details (replace with your actual API call)
        if (!empty($journeyData['journeys'])) {
            $journey = $journeyData['journeys'][0]; // Take the first proposed itinerary
            $totalDuration = gmdate("H:i", $journey['duration']);

            echo "<p>Beginning of the itinerary with the earliest departure time (Total duration: $totalDuration).</p>";

            foreach ($journey['sections'] as $section) {
                // Convert timestamps into readable local times
                $departureTime = date("H:i", strtotime($section['departure_date_time']));
                $arrivalTime = date("H:i", strtotime($section['arrival_date_time']));

                // Get the name of the departure and arrival stops if available
                $departureStop = $section['from']['name'] ?? '';
                $arrivalStop = $section['to']['name'] ?? '';

                if ($departureTime == $arrivalTime && ($section['type'] == 'crow_fly' || $section['type'] == 'waiting')) {
                    // If the departure and arrival times are the same for walking or waiting sections, display nothing
                    continue; // Skip to the next section without displaying a message
                }
                // Display the departure time for each type of transport
                if ($section['type'] == 'public_transport') {
                    $line = $section['display_informations']['label'];
                    $code = $section['display_informations']['code'];
                    $network = $section['display_informations']['network'];
                    $direction = $section['display_informations']['direction'];
                    echo "<p><strong>Departure at $departureTime from the stop $departureStop:</strong> Take the $network $line towards $direction. Expected arrival at $arrivalTime at the stop $arrivalStop.</p>";
                } elseif ($section['type'] == 'transfer') {
                    echo "<p><strong>Transfer planned from $departureTime at the stop $departureStop:</strong> Arrival at the transfer point $arrivalStop at $arrivalTime.</p>";
                } elseif ($section['type'] == 'waiting') {
                    echo "<p><strong>Waiting at the stop $departureStop:</strong> From $departureTime to $arrivalTime.</p>";
                } elseif ($section['type'] == 'crow_fly' || $section['type'] == 'street_network') {
                    // Walking journey between transport sections
                    echo "<p><strong>Walking from the stop $departureStop to the stop $arrivalStop:</strong> From $departureTime to $arrivalTime.</p>";
                } else {
                    // Other types of sections
                    $mode = ucfirst($section['type']);
                    echo "<p><strong>$mode from the stop $departureStop to the stop $arrivalStop:</strong> From $departureTime to $arrivalTime.</p>";
                }
            }
            echo "<p>End of the itinerary at the stop $arrivalStop at $arrivalTime.</p>";
        } else {
            echo "<p>No itinerary found for the specified parameters or please check your input (Press the Search button to recheck).</p>";
        }
        ?>
    </section>
<?php elseif (isset($journeyData)): ?>
    <p>No itinerary found for the specified parameters.</p>
<?php endif; ?>
</main>