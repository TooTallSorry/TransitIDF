<?php
require_once('include/util.inc.php');
require_once('include/functions.inc.php');

$lang = manageLangCookie();
$style = manageStyleCookie();
$pageTitle = "Plan du site";
$pageDescription = "plan du site";
$body = "bodyIndex";

if (isset($_GET['style']) && !empty($_GET['style'])) {
    $style = $_GET['style'];
    $img = "zhtmlBlack";
    $imgFac = "cergyBlack";
}

require_once("include/{$lang}header.inc.php");
require_once("include/{$lang}.inc.sitemap.php");
require_once("include/{$lang}footer.inc.php"); ?>
