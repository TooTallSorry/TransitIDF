<main>
<a href="#" class="btn">
    <img src="./images/arrow.png" class="icone" alt="Fleche vers le haut"/>
</a>
    <h1>Recherche de Départs</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display: flex; align-items: center;">
        <label for="stationName">Nom de la gare : </label>
        <!-- Assurez-vous que la valeur entrée reste après la soumission -->
        <input type="text" id="stationName" name="stationName" value="<?= isset($_POST['stationName']) ? htmlspecialchars($_POST['stationName']) : '' ?>"  />
        <label for="lineName">Nom/Code de la ligne (facultatif) :</label>
        <!-- Conserver la valeur entrée pour le nom de la ligne si disponible -->
        <input type="text" id="lineName" name="lineName" value="<?= isset($_POST['lineName']) ? htmlspecialchars($_POST['lineName']) : '' ?>"/>
        <input type="submit" value="Rechercher les départs"/>
        <!-- Bouton pour rafraîchir la page -->
        <button type="button" onclick="window.location.reload();">Rafraîchir</button>
    </form>

    <?php if (!empty($error)): ?>
    <p><?= htmlspecialchars($error) ?></p>
<?php elseif (!empty($departures)): ?>
    <h2>Départs de <?= htmlspecialchars($stationFullName) ?> </h2>
    <?php foreach ($departures as $vehicleType => $vehicleDepartures): ?>
        <h3><?= htmlspecialchars($vehicleType) ?></h3>
        <ul>
            <?php foreach ($vehicleDepartures as $departure): ?>
                <li><?= htmlspecialchars($departure['lineCode']) . " vers " . htmlspecialchars($departure['direction']) . " à " . htmlspecialchars($departure['departureTime']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
<?php endif; ?>
</main>