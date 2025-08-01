<?php
/**
 * Proxy pour les images KoboToolbox
 * Ce fichier permet d'afficher les images avec authentification
 */

// Inclure les fichiers nécessaires
require_once 'includes/KoboCollectAPI.php';
$config = require_once 'config/kobocollect.php';

// Vérifier que l'URL de l'image est fournie
if (!isset($_GET['url']) || empty($_GET['url'])) {
    http_response_code(400);
    die('URL de l\'image manquante');
}

$imageUrl = $_GET['url'];

// Vérifier que l'URL provient bien de KoboToolbox pour des raisons de sécurité
$allowedDomains = [
    'kf.kobotoolbox.org',
    'kc.kobotoolbox.org',
    'kobotoolbox.org'
];

$parsedUrl = parse_url($imageUrl);
if (!$parsedUrl || !isset($parsedUrl['host'])) {
    http_response_code(400);
    die('URL invalide');
}

$isAllowed = false;
foreach ($allowedDomains as $domain) {
    if (strpos($parsedUrl['host'], $domain) !== false) {
        $isAllowed = true;
        break;
    }
}

if (!$isAllowed) {
    http_response_code(403);
    die('Domaine non autorisé');
}

// Créer l'instance de l'API
$api = new KoboCollectAPI($config);

// Télécharger l'image
$imageResult = $api->downloadImage($imageUrl);

if ($imageResult === false) {
    http_response_code(404);
    die('Impossible de récupérer l\'image');
}

// Définir les headers appropriés
header('Content-Type: ' . $imageResult['content_type']);
header('Content-Length: ' . $imageResult['size']);
header('Cache-Control: public, max-age=3600'); // Cache pendant 1 heure
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

// Afficher l'image
echo $imageResult['data'];
?>
