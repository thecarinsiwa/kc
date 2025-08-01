<?php
// Inclure la configuration
$config = require_once '../config/kobocollect.php';

// Récupérer les paramètres
$imageUrl = $_GET['url'] ?? null;
$recordId = $_GET['record_id'] ?? null;
$attachmentId = $_GET['attachment_id'] ?? null;
$size = $_GET['size'] ?? 'large'; // small, medium, large

if (!$imageUrl && (!$recordId || !$attachmentId)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'URL ou paramètres manquants']);
    exit;
}

// Si on a un record_id et attachment_id, construire l'URL
if (!$imageUrl && $recordId && $attachmentId) {
    // Construire l'URL correcte pour KoboCollect (sans ?format=json)
    $baseUrl = "https://kf.kobotoolbox.org/api/v2/assets/{$config['form_id']}/data/{$recordId}/attachments/{$attachmentId}/";
    
    // Ajouter la taille si spécifiée
    if ($size === 'small') {
        $imageUrl = $baseUrl . "small/";
    } elseif ($size === 'medium') {
        $imageUrl = $baseUrl . "medium/";
    } else {
        $imageUrl = $baseUrl . "large/";
    }
}

// Supprimer le paramètre ?format=json s'il existe
if ($imageUrl && strpos($imageUrl, '?format=json') !== false) {
    $imageUrl = str_replace('?format=json', '', $imageUrl);
}

// Debug: Log l'URL construite
error_log("Image URL: " . $imageUrl);

// Configuration cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $imageUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => $config['api_settings']['verify_ssl'],
    CURLOPT_TIMEOUT => $config['api_settings']['timeout'],
    CURLOPT_USERAGENT => $config['api_settings']['user_agent'],
    CURLOPT_HTTPHEADER => [
        'Authorization: Token ' . $config['auth_token'],
        'Accept: image/*'
    ]
]);

// Exécuter la requête
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

// Debug: Log les informations de réponse
error_log("HTTP Code: " . $httpCode);
error_log("Content Type: " . $contentType);

if (curl_errno($ch)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erreur cURL: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Vérifier le code de réponse
if ($httpCode !== 200) {
    http_response_code($httpCode);
    header('Content-Type: application/json');
    echo json_encode(['error' => "Erreur HTTP: {$httpCode}", 'url' => $imageUrl]);
    exit;
}

// Vérifier que c'est bien une image
if (!strpos($contentType, 'image/')) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Le contenu n\'est pas une image', 'content_type' => $contentType]);
    exit;
}

// Retourner l'image
header('Content-Type: ' . $contentType);
header('Cache-Control: public, max-age=3600'); // Cache 1 heure
header('Access-Control-Allow-Origin: *'); // Permettre l'accès depuis le frontend
echo $response; 