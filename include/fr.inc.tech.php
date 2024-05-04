<main>
<a href="#" class="btn">
    <img src="./images/arrow.png" class="icone" alt="Fleche vers le haut"/>
</a>
    <nav class="sidebar">
            <ul>
                <li><a href="#apod">APOD</a></li>
                <li><a href="#localisationXML">Localisation XML</a></li>
                <li><a href="#localisationJSON">Localisation JSON</a></li>
            </ul>
    </nav>
    <h1> Page Tech </h1>
        <section>
            <h2>Exercice 1 : APOD</h2>
            <a class="anchor" id="apod"></a>
            <?php echo translateTextWithDeepL($explanationAPOD, $apiKeyDeepL); ?> </p>
        </section>
        <section>
            <h2>Exercice 2 : Localisation XML</h2>
            <a class="anchor" id="localisationXML"></a>
            <p><?php echo getLocalisationXML($ip); ?></p>
        </section>
        <section>
            <h2>Exercice 3 : Localisation JSON</h2>
            <a class="anchor" id="localisationJSON"></a>
            <p><?php echo getLocalisationJSON($ip); ?></p>
        </section>
</main>