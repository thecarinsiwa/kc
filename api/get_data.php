<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Inclure les fichiers nécessaires
require_once '../includes/KoboCollectAPI.php';

// Charger la configuration
$config = require_once '../config/kobocollect.php';

// Créer l'instance de l'API
$api = new KoboCollectAPI($config);

// Récupérer les paramètres
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    // Récupérer les données
    $data = $api->getData($limit, $offset);
    
    if (isset($data['error'])) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $data['error']
        ]);
        exit;
    }
    
    // Filtrer les données selon la configuration
    $data = $api->filterData($data);
    
    // Appliquer la recherche si demandée
    if (!empty($search)) {
        $data = $api->searchData($data, $search);
    }
    
    // Préparer la réponse
    $response = [
        'success' => true,
        'data' => $data['results'] ?? [],
        'total' => $data['count'] ?? count($data['results'] ?? []),
        'limit' => $limit,
        'offset' => $offset
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur serveur: ' . $e->getMessage()
    ]);
} 
catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur critique: ' . $e->getMessage()
    ]);
}

