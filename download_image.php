<?php
/**
 * Script de téléchargement d'images KoboToolbox
 * Ce fichier permet de télécharger les images avec un nom de fichier approprié
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
$filename = $_GET['filename'] ?? 'image.jpg';

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

// Nettoyer le nom de fichier
$filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

// Définir les headers pour le téléchargement
header('Content-Type: ' . $imageResult['content_type']);
header('Content-Length: ' . $imageResult['size']);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

// Envoyer l'image
echo $imageResult['data'];
?>
