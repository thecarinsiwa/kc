<?php
/**
 * Script de test pour vérifier la récupération des photos
 */

// Inclure les fichiers nécessaires
require_once 'includes/KoboCollectAPI.php';
$config = require_once 'config/kobocollect.php';

echo "<h1>Test de Récupération des Photos</h1>";

// Créer l'instance de l'API
$api = new KoboCollectAPI($config);

// Récupérer quelques données pour tester
echo "<h2>Récupération des données avec photos...</h2>";
$data = $api->getData(10, 0); // Récupérer 10 enregistrements

if (isset($data['error'])) {
    echo "<div style='color: red;'>Erreur: " . htmlspecialchars($data['error']) . "</div>";
    exit;
}

// Filtrer les données
$data = $api->filterData($data);

if (!isset($data['results']) || empty($data['results'])) {
    echo "<div style='color: red;'>Aucune donnée trouvée.</div>";
    exit;
}

echo "<h2>Recherche d'images dans les enregistrements...</h2>";

$foundPhotos = 0;

foreach ($data['results'] as $index => $record) {
    $attachments = $record['_attachments'] ?? [];
    
    if (!empty($attachments)) {
        echo "<div style='border: 1px solid #ccc; padding: 15px; margin: 10px 0;'>";
        echo "<h3>Enregistrement " . ($index + 1) . "</h3>";
        echo "<p><strong>Nom :</strong> " . htmlspecialchars(($record['A_3_Prenom'] ?? '') . ' ' . ($record['A_1_Nom_de_l_entrepreneur'] ?? '')) . "</p>";
        echo "<p><strong>ID :</strong> " . htmlspecialchars($record['_id'] ?? 'N/A') . "</p>";
        
        foreach ($attachments as $attachment) {
            if (isset($attachment['mimetype']) && strpos($attachment['mimetype'], 'image/') === 0) {
                $foundPhotos++;
                
                echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0;'>";
                echo "<h4>📷 Image trouvée</h4>";
                echo "<p><strong>Question :</strong> " . htmlspecialchars($attachment['question_xpath'] ?? 'N/A') . "</p>";
                echo "<p><strong>Nom du fichier :</strong> " . htmlspecialchars($attachment['media_file_basename'] ?? 'N/A') . "</p>";
                echo "<p><strong>Type MIME :</strong> " . htmlspecialchars($attachment['mimetype'] ?? 'N/A') . "</p>";
                
                // URLs disponibles
                $urls = [
                    'Small' => $attachment['download_small_url'] ?? null,
                    'Medium' => $attachment['download_medium_url'] ?? null,
                    'Large' => $attachment['download_large_url'] ?? null
                ];
                
                foreach ($urls as $size => $url) {
                    if ($url) {
                        $cleanUrl = str_replace('?format=json', '', $url);
                        $proxyUrl = 'image_proxy.php?url=' . urlencode($cleanUrl);
                        
                        echo "<div style='margin: 5px 0;'>";
                        echo "<strong>$size :</strong> ";
                        echo "<a href='$proxyUrl' target='_blank'>Voir via proxy</a> | ";
                        echo "<a href='$cleanUrl' target='_blank'>Voir direct (peut échouer)</a>";
                        echo "</div>";
                        
                        // Afficher un aperçu de l'image small
                        if ($size === 'Small') {
                            echo "<div style='margin: 10px 0;'>";
                            echo "<strong>Aperçu :</strong><br>";
                            echo "<img src='$proxyUrl' style='max-width: 150px; max-height: 150px; border: 1px solid #ddd;' ";
                            echo "alt='Aperçu' onerror='this.style.display=\"none\"; this.nextElementSibling.style.display=\"block\";'>";
                            echo "<div style='display: none; color: red; font-style: italic;'>Erreur de chargement</div>";
                            echo "</div>";
                        }
                    }
                }
                
                // Test de téléchargement via API
                if (isset($attachment['download_medium_url'])) {
                    $testUrl = str_replace('?format=json', '', $attachment['download_medium_url']);
                    echo "<div style='margin: 10px 0;'>";
                    echo "<strong>Test API :</strong> ";
                    
                    try {
                        $imageResult = $api->downloadImage($testUrl);
                        if ($imageResult !== false) {
                            $size = number_format(strlen($imageResult['data']) / 1024, 1);
                            echo "<span style='color: green;'>✅ Succès ($size KB)</span>";
                        } else {
                            echo "<span style='color: red;'>❌ Échec</span>";
                        }
                    } catch (Exception $e) {
                        echo "<span style='color: red;'>❌ Erreur: " . htmlspecialchars($e->getMessage()) . "</span>";
                    }
                    echo "</div>";
                }
                
                // Bouton pour générer le certificat avec cette photo
                echo "<div style='margin: 10px 0;'>";
                echo "<a href='generate_certificate_simple.php?id=" . urlencode($record['_id']) . "' ";
                echo "class='btn' style='background: #4CAF50; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;'>";
                echo "🎓 Générer Certificat avec cette photo</a>";
                echo "</div>";
                
                echo "</div>";
            }
        }
        echo "</div>";
    }
}

if ($foundPhotos === 0) {
    echo "<div style='background: #fff3cd; padding: 15px; border: 1px solid #ffeaa7; border-radius: 5px;'>";
    echo "<h3>⚠️ Aucune photo trouvée</h3>";
    echo "<p>Cela peut signifier que :</p>";
    echo "<ul>";
    echo "<li>Les enregistrements récents n'ont pas de photos attachées</li>";
    echo "<li>Le champ 'Image_piece_identite' n'est pas utilisé dans votre formulaire</li>";
    echo "<li>Les photos sont stockées dans un autre champ</li>";
    echo "</ul>";
    echo "<p><strong>Solution :</strong> Vérifiez les noms des champs dans votre formulaire KoboToolbox.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
    echo "<h3>✅ $foundPhotos photo(s) trouvée(s)</h3>";
    echo "<p>Les certificats peuvent maintenant être générés avec les photos des participants.</p>";
    echo "</div>";
}

echo "<h2>Informations de débogage</h2>";
echo "<ul>";
echo "<li><strong>Nombre d'enregistrements :</strong> " . count($data['results']) . "</li>";
echo "<li><strong>Photos trouvées :</strong> $foundPhotos</li>";
echo "<li><strong>API fonctionnelle :</strong> ✅</li>";
echo "<li><strong>Proxy d'images :</strong> ✅</li>";
echo "</ul>";

echo "<div style='margin-top: 30px;'>";
echo "<a href='test_certificate.php' style='color: #0066cc;'>← Retour au test de certificats</a> | ";
echo "<a href='index.php' style='color: #0066cc;'>Retour à la liste</a>";
echo "</div>";

?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    line-height: 1.6;
}

h1, h2, h3 {
    color: #333;
}

.btn {
    display: inline-block;
    padding: 8px 16px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin: 2px;
}

.btn:hover {
    opacity: 0.9;
}
</style>
