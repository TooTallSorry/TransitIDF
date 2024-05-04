<?php
declare(strict_types=1);

/**
 * Affiche les informations PHP si le paramètre 'info' est présent dans la requête avec une valeur sécurisée.
 */
if (isset($_GET['info']) && $_GET['info'] === 'secret') {
    phpinfo();
    exit; // Assurez-vous que rien d'autre ne s'exécute après phpinfo
}

/**
 * Détecte le navigateur utilisé par l'utilisateur en se basant sur l'agent utilisateur (User-Agent).
 *
 * @return string Le nom du navigateur détecté, ou "Navigateur inconnu" si non détecté.
 */
function get_navigateur(): string {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    if (strpos($userAgent, 'Edg') !== false) {
        return 'Microsoft Edge';
    } elseif (strpos($userAgent, 'Chrome') !== false) {
        return 'Chrome';
    } elseif (strpos($userAgent, 'Firefox') !== false) {
        return 'Firefox';
    } elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
        return 'Safari';
    } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
        return 'Internet Explorer';
    } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
        return 'Opera';
    }

    return 'Navigateur inconnu';
}
?>
