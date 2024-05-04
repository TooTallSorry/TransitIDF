<main>
    <a href="#" class="btn">
        <img src="./images/arrow.png" class="icone" alt="Up Arrow"/>
    </a>
    <nav class="sidebar">
            <ul>
                <li><a href="#apod">APOD</a></li>
                <li><a href="#locationXML">Location XML</a></li>
                <li><a href="#locationJSON">Location JSON</a></li>
            </ul>
    </nav>
    <h1> Tech Page </h1>
        <section>
            <h2>Exercise 1: APOD</h2>
            <a class="anchor" id="apod"></a>
            <?php echo $explanationAPOD ; ?>
        </section>
        <section>
            <h2>Exercise 2: Location XML</h2>
            <a class="anchor" id="locationXML"></a>
            <p><?php echo getLocationXML($ip); ?></p>
        </section>
        <section>
            <h2>Exercise 3: Location JSON</h2>
            <a class="anchor" id="locationJSON"></a>
            <p><?php echo getLocationJSON($ip); ?></p>
        </section>
</main>