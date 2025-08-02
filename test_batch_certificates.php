<?php
/**
 * Script de test pour la génération de certificats en lot
 */

// Inclure les fichiers nécessaires
require_once 'includes/KoboCollectAPI.php';
$config = require_once 'config/kobocollect.php';

echo "<h1>Test de Génération de Certificats en Lot</h1>";

// Créer l'instance de l'API
try {
    $api = new KoboCollectAPI($config);
} catch (Exception $e) {
    die('Erreur de configuration API : ' . $e->getMessage());
}

// Récupérer quelques données pour tester
echo "<h2>Récupération des données...</h2>";
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$data = $api->getData($limit, 0);

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

$totalRecords = count($data['results']);

echo "<h2>Données disponibles pour les certificats</h2>";
echo "<p><strong>Nombre d'enregistrements :</strong> $totalRecords</p>";

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th>ID</th>";
echo "<th>Nom Complet</th>";
echo "<th>Entreprise</th>";
echo "<th>Date</th>";
echo "<th>Statut Photo</th>";
echo "</tr>";

$recordsWithPhotos = 0;
$recordsWithNames = 0;

foreach ($data['results'] as $record) {
    $id = $record['_id'] ?? 'N/A';
    $prenom = $record['A_3_Prenom'] ?? '';
    $nom = $record['A_1_Nom_de_l_entrepreneur'] ?? '';
    $entreprise = $record['B_1_Nom_de_l_entreprise'] ?? '';
    $date = $record['Date_interview'] ?? '';
    
    $nomComplet = trim($prenom . ' ' . $nom);
    if (!empty($nomComplet)) {
        $recordsWithNames++;
    } else {
        $nomComplet = 'NOM NON RENSEIGNÉ';
    }
    
    // Vérifier la présence d'une photo
    $hasPhoto = false;
    $attachments = $record['_attachments'] ?? [];
    foreach ($attachments as $attachment) {
        if (isset($attachment['question_xpath']) && 
            $attachment['question_xpath'] === 'Image_piece_identite') {
            $hasPhoto = true;
            $recordsWithPhotos++;
            break;
        }
    }
    
    echo "<tr>";
    echo "<td>" . htmlspecialchars($id) . "</td>";
    echo "<td><strong>" . htmlspecialchars($nomComplet) . "</strong></td>";
    echo "<td>" . htmlspecialchars($entreprise) . "</td>";
    echo "<td>" . htmlspecialchars($date) . "</td>";
    echo "<td>" . ($hasPhoto ? '<span style="color: green;">✅ Photo disponible</span>' : '<span style="color: orange;">⚠️ Pas de photo</span>') . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Statistiques</h2>";
echo "<ul>";
echo "<li><strong>Total d'enregistrements :</strong> $totalRecords</li>";
echo "<li><strong>Avec nom complet :</strong> $recordsWithNames (" . round($recordsWithNames/$totalRecords*100, 1) . "%)</li>";
echo "<li><strong>Avec photo :</strong> $recordsWithPhotos (" . round($recordsWithPhotos/$totalRecords*100, 1) . "%)</li>";
echo "</ul>";

echo "<h2>Test de génération</h2>";

// Vérifier TCPDF
$tcpdfAvailable = false;
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    $tcpdfAvailable = class_exists('TCPDF');
}

if ($tcpdfAvailable) {
    echo "<p style='color: green;'>✅ TCPDF disponible - Génération PDF possible</p>";
} else {
    echo "<p style='color: red;'>❌ TCPDF non disponible - Génération PDF impossible</p>";
}

// Vérifier les assets
$assets = [
    'Template PDF' => 'assets/Modèle_Certificats Participant-e-s_FIP_complet.pdf',
    'Logo MOVE' => 'assets/logo-move.png',
    'Logo Transforme' => 'assets/logo-transforme.png'
];

$allAssetsOk = true;
foreach ($assets as $name => $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✅ $name disponible</p>";
    } else {
        echo "<p style='color: red;'>❌ $name manquant</p>";
        $allAssetsOk = false;
    }
}

echo "<h2>Options de test</h2>";

if ($tcpdfAvailable && $allAssetsOk) {
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>✅ Prêt pour la génération</h3>";
    echo "<p>Tous les prérequis sont satisfaits. Vous pouvez tester la génération de certificats.</p>";
    
    echo "<div style='margin: 20px 0;'>";
    echo "<a href='generate_all_certificates.php?limit=$totalRecords' target='_blank' ";
    echo "style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>";
    echo "🎓 Générer $totalRecords certificats (PDF)</a>";
    
    echo "<a href='generate_all_certificates.php?limit=3' target='_blank' ";
    echo "style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>";
    echo "🎓 Test avec 3 certificats</a>";
    
    echo "<a href='generate_all_certificates.php?limit=1' target='_blank' ";
    echo "style='background: #ffc107; color: black; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>";
    echo "🎓 Test avec 1 certificat</a>";
    echo "</div>";
    
    echo "</div>";
} else {
    echo "<div style='background: #ffebee; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>❌ Prérequis manquants</h3>";
    echo "<p>Certains éléments sont manquants pour la génération de certificats :</p>";
    
    if (!$tcpdfAvailable) {
        echo "<p>• Installez TCPDF : <code>composer require tecnickcom/tcpdf</code></p>";
    }
    
    if (!$allAssetsOk) {
        echo "<p>• Vérifiez que tous les fichiers assets sont présents</p>";
    }
    
    echo "</div>";
}

echo "<h2>Simulation du bouton de l'interface</h2>";
echo "<p>Voici comment le bouton fonctionnera dans l'interface principale :</p>";

echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #dee2e6;'>";
echo "<h4>Interface utilisateur</h4>";
echo "<p><strong>Étapes :</strong></p>";
echo "<ol>";
echo "<li>L'utilisateur clique sur le bouton 'Certificats' dans index.php</li>";
echo "<li>Un menu déroulant apparaît avec les options :</li>";
echo "<ul>";
echo "<li>'Tous les certificats PDF' - Génère tous les certificats</li>";
echo "<li>'Certificats filtrés' - Génère seulement les certificats des résultats de recherche</li>";
echo "<li>'Tester les certificats' - Lien vers la page de test</li>";
echo "</ul>";
echo "<li>Une confirmation demande à l'utilisateur s'il veut continuer</li>";
echo "<li>Un indicateur de progression s'affiche</li>";
echo "<li>Le fichier PDF se télécharge automatiquement</li>";
echo "</ol>";
echo "</div>";

echo "<h2>Informations techniques</h2>";
echo "<ul>";
echo "<li><strong>Limite mémoire PHP :</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>Temps d'exécution max :</strong> " . ini_get('max_execution_time') . "s</li>";
echo "<li><strong>Taille max upload :</strong> " . ini_get('upload_max_filesize') . "</li>";
echo "<li><strong>Extensions chargées :</strong> " . (extension_loaded('gd') ? 'GD ✅' : 'GD ❌') . ", " . (extension_loaded('curl') ? 'cURL ✅' : 'cURL ❌') . "</li>";
echo "</ul>";

echo "<div style='margin-top: 30px;'>";
echo "<a href='index.php' style='color: #0066cc;'>← Retour à l'interface principale</a> | ";
echo "<a href='test_certificate.php' style='color: #0066cc;'>Test certificat individuel</a> | ";
echo "<a href='demo_certificate.php' style='color: #0066cc;'>Voir la démo</a>";
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

table {
    margin: 15px 0;
}

th, td {
    padding: 8px 12px;
    text-align: left;
    border: 1px solid #ddd;
}

th {
    background: #f0f0f0;
    font-weight: bold;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

code {
    background: #f5f5f5;
    padding: 2px 4px;
    border-radius: 3px;
    font-family: monospace;
}
</style>
