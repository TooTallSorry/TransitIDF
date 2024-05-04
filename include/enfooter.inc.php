<footer>
    <span>L2-I Group D | <span id="author">Cheballah Jawed | Makuisa Andrew</span></span>
    <span>| Last update: 28/04/2024</span>
    <span style="display: block;">Site map: <a href="sitemap.php">HERE</a></span>
    <a href="https://www.cyu.fr/" target="_blank">
        <img src="./images/<?php echo $imgFac ?? "cergy1"; ?>.png" alt="Logo de l'Universite de Cergy-Pontoise" id="footer-image" title="University website" />
    </a>
    <span>User visits: <?php echo updateVisitCounter(); ?> <br /></span>
    <span>Browser used: <?php echo get_navigateur(); ?></span>
</footer>
</body>
</html>
