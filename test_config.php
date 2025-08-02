<?php
/**
 * Script de test pour vérifier la configuration
 */

echo "<h1>Test de Configuration</h1>";

// Test 1: Inclusion du fichier de configuration
echo "<h2>Test 1: Fichier de configuration</h2>";
try {
    $config = require_once 'config/kobocollect.php';
    
    if (is_array($config)) {
        echo "<p style='color: green;'>✅ Configuration chargée avec succès</p>";
        echo "<p><strong>Type:</strong> " . gettype($config) . "</p>";
        echo "<p><strong>Nombre d'éléments:</strong> " . count($config) . "</p>";
        
        // Vérifier les clés importantes
        $requiredKeys = ['server_url', 'auth_token', 'form_id', 'api_settings'];
        foreach ($requiredKeys as $key) {
            if (isset($config[$key])) {
                echo "<p style='color: green;'>✅ Clé '$key' présente</p>";
            } else {
                echo "<p style='color: red;'>❌ Clé '$key' manquante</p>";
            }
        }
    } else {
        echo "<p style='color: red;'>❌ Configuration invalide (type: " . gettype($config) . ")</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur lors du chargement: " . $e->getMessage() . "</p>";
}

// Test 2: Création de l'API
echo "<h2>Test 2: Création de l'API</h2>";
try {
    require_once 'includes/KoboCollectAPI.php';
    $api = new KoboCollectAPI($config);
    echo "<p style='color: green;'>✅ API créée avec succès</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur lors de la création de l'API: " . $e->getMessage() . "</p>";
}

// Test 3: Test de récupération de données (sans affichage complet)
echo "<h2>Test 3: Test de récupération de données</h2>";
try {
    $data = $api->getData(1, 0); // Récupérer seulement 1 enregistrement
    
    if (isset($data['error'])) {
        echo "<p style='color: red;'>❌ Erreur API: " . $data['error'] . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Données récupérées avec succès</p>";
        echo "<p><strong>Type de réponse:</strong> " . gettype($data) . "</p>";
        
        if (isset($data['results'])) {
            echo "<p><strong>Nombre d'enregistrements:</strong> " . count($data['results']) . "</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur lors de la récupération: " . $e->getMessage() . "</p>";
}

// Test 4: Vérification des extensions PHP
echo "<h2>Test 4: Extensions PHP</h2>";
$extensions = ['gd', 'curl', 'json'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ Extension $ext chargée</p>";
    } else {
        echo "<p style='color: red;'>❌ Extension $ext manquante</p>";
    }
}

// Test 5: Vérification TCPDF
echo "<h2>Test 5: TCPDF</h2>";
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    
    if (class_exists('TCPDF')) {
        echo "<p style='color: green;'>✅ TCPDF disponible</p>";
    } else {
        echo "<p style='color: red;'>❌ TCPDF non trouvé</p>";
    }
    
    if (class_exists('setasign\Fpdi\Tcpdf\Fpdi')) {
        echo "<p style='color: green;'>✅ FPDI disponible</p>";
    } else {
        echo "<p style='color: red;'>❌ FPDI non trouvé</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Autoloader Composer non trouvé</p>";
}

// Test 6: Vérification des fichiers assets
echo "<h2>Test 6: Fichiers Assets</h2>";
$assets = [
    'Template JPG' => 'assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg',
    'Template PDF' => 'assets/Modèle_Certificats Participant-e-s_FIP_complet.pdf',
    'Logo MOVE' => 'assets/logo-move.png',
    'Logo Transforme' => 'assets/logo-transforme.png'
];

foreach ($assets as $name => $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✅ $name trouvé</p>";
    } else {
        echo "<p style='color: red;'>❌ $name manquant ($path)</p>";
    }
}

echo "<h2>Résumé</h2>";
echo "<p>Si tous les tests sont verts, vous pouvez maintenant tester la génération de certificats.</p>";
echo "<div style='margin-top: 20px;'>";
echo "<a href='demo_certificate.php' style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>Voir la démo</a>";
echo "<a href='test_certificate.php' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>Tester avec vraies données</a>";
echo "<a href='index.php' style='background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>Retour à la liste</a>";
echo "</div>";

?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    line-height: 1.6;
}

h1, h2 {
    color: #333;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
}

p {
    margin: 5px 0;
}
</style>
