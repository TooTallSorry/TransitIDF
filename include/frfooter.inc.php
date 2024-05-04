<footer>
    <span>L2-I Groupe D | <span id="auteur">Cheballah Jawed | Makuisa Andrew</span></span>
    <span>| Dernière mise à jour : 28/04/2024</span>
    <span style="display: block;">Plan du site : <a href="sitemap.php" class="site-link">ICI</a></span>
    <a href="https://www.cyu.fr/" target="_blank">
        <img src="./images/<?php echo $imgFac ?? "cergy1"; ?>.png" alt="Logo de l'Universite de Cergy-Pontoise" id="image-pied-page" title="Site de l'université" />
    </a>
    <span>Nombre de visites : <?php echo updateVisitCounter(); ?> <br /></span>
    <span>Navigateur utilisé : <?php echo get_navigateur(); ?></span>
</footer>
</body>
</html>
