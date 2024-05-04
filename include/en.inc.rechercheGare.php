<main>
    <a href="#" class="btn">
        <img src="./images/arrow.png" class="icone" alt="Up Arrow"/>
    </a>
    <h1>Departures Search</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display: flex; align-items: center;">
        <label for="stationName">Station name:</label>
        <input type="text" id="stationName" name="stationName" value="<?= isset($_POST['stationName']) ? htmlspecialchars($_POST['stationName']) : '' ?>"/>
        <label for="lineName">Line name/code (optional):</label>
        <input type="text" id="lineName" name="lineName" value="<?= isset($_POST['lineName']) ? htmlspecialchars($_POST['lineName']) : '' ?>"/>
        <input type="submit" value="Search departures"/>
        <button type="button" onclick="window.location.reload();">Refresh</button>
    </form>

    <?php if (!empty($error)): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php elseif (!empty($departures)): ?>
    <h2>Departures from <?= htmlspecialchars($stationFullName) ?></h2>
    <?php foreach ($departures as $vehicleType => $vehicleDepartures): ?>
        <h3><?= htmlspecialchars($vehicleType) ?></h3>
        <ul>
            <?php foreach ($vehicleDepartures as $departure): ?>
                <li><?= htmlspecialchars($departure['lineCode']) . " towards " . htmlspecialchars($departure['direction']) . " at " . htmlspecialchars($departure['departureTime']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
<?php endif; ?>

</main>