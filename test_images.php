<?php
/**
 * Script de test pour vérifier l'accès aux images KoboToolbox
 */

// Inclure les fichiers nécessaires
require_once 'includes/KoboCollectAPI.php';
$config = require_once 'config/kobocollect.php';

echo "<h1>Test d'accès aux images KoboToolbox</h1>";

// Créer l'instance de l'API
$api = new KoboCollectAPI($config);

// Récupérer quelques données pour tester
echo "<h2>Récupération des données...</h2>";
$data = $api->getData(5, 0); // Récupérer 5 enregistrements

if (isset($data['error'])) {
    echo "<div style='color: red;'>Erreur: " . htmlspecialchars($data['error']) . "</div>";
    exit;
}

// Filtrer les données
$data = $api->filterData($data);

echo "<h2>Recherche d'images dans les enregistrements...</h2>";

$foundImages = false;

if (isset($data['results'])) {
    foreach ($data['results'] as $index => $record) {
        $attachments = $record['_attachments'] ?? [];
        
        if (!empty($attachments)) {
            echo "<h3>Enregistrement " . ($index + 1) . " - ID: " . htmlspecialchars($record['_id'] ?? 'N/A') . "</h3>";
            
            foreach ($attachments as $attachment) {
                if (isset($attachment['mimetype']) && strpos($attachment['mimetype'], 'image/') === 0) {
                    $foundImages = true;
                    
                    echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
                    echo "<h4>Image trouvée:</h4>";
                    echo "<p><strong>Question:</strong> " . htmlspecialchars($attachment['question_xpath'] ?? 'N/A') . "</p>";
                    echo "<p><strong>Nom du fichier:</strong> " . htmlspecialchars($attachment['media_file_basename'] ?? 'N/A') . "</p>";
                    echo "<p><strong>Type MIME:</strong> " . htmlspecialchars($attachment['mimetype'] ?? 'N/A') . "</p>";
                    
                    // Tester l'URL directe
                    $directUrl = str_replace('?format=json', '', $attachment['download_small_url'] ?? '');
                    echo "<p><strong>URL directe:</strong> <a href='" . htmlspecialchars($directUrl) . "' target='_blank'>Tester (peut échouer)</a></p>";
                    
                    // Tester l'URL via proxy
                    $proxyUrl = 'image_proxy.php?url=' . urlencode($directUrl);
                    echo "<p><strong>URL via proxy:</strong> <a href='" . htmlspecialchars($proxyUrl) . "' target='_blank'>Tester (devrait fonctionner)</a></p>";
                    
                    // Afficher l'image via proxy
                    echo "<p><strong>Aperçu:</strong></p>";
                    echo "<img src='" . htmlspecialchars($proxyUrl) . "' style='max-width: 200px; max-height: 200px; border: 1px solid #ddd;' alt='Test image' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
                    echo "<div style='display: none; color: red; font-style: italic;'>Erreur lors du chargement de l'image</div>";
                    
                    echo "</div>";
                }
            }
        }
    }
}

if (!$foundImages) {
    echo "<p>Aucune image trouvée dans les 5 premiers enregistrements.</p>";
    echo "<p>Cela peut signifier que:</p>";
    echo "<ul>";
    echo "<li>Les enregistrements récents n'ont pas d'images attachées</li>";
    echo "<li>Les images sont stockées différemment dans votre formulaire</li>";
    echo "<li>Il faut vérifier plus d'enregistrements</li>";
    echo "</ul>";
}

echo "<h2>Test de configuration</h2>";
echo "<p><strong>Serveur:</strong> " . htmlspecialchars($config['server_url']) . "</p>";
echo "<p><strong>Form ID:</strong> " . htmlspecialchars($config['form_id']) . "</p>";
echo "<p><strong>Authentification:</strong> " . (!empty($config['auth_token']) ? 'Token configuré' : 'Nom d\'utilisateur/mot de passe') . "</p>";

?>
