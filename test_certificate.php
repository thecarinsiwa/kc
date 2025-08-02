<?php
/**
 * Script de test pour la génération de certificats
 */

// Inclure les fichiers nécessaires
require_once 'includes/KoboCollectAPI.php';
$config = require_once 'config/kobocollect.php';

echo "<h1>Test de Génération de Certificat</h1>";

// Créer l'instance de l'API
$api = new KoboCollectAPI($config);

// Récupérer quelques données pour tester
echo "<h2>Récupération des données de test...</h2>";
$data = $api->getData(5, 0);

if (isset($data['error'])) {
    echo "<div style='color: red;'>Erreur: " . htmlspecialchars($data['error']) . "</div>";
    exit;
}

// Filtrer les données
$data = $api->filterData($data);

if (!isset($data['results']) || empty($data['results'])) {
    echo "<div style='color: red;'>Aucune donnée trouvée pour le test.</div>";
    exit;
}

// Prendre le premier enregistrement pour le test
$testRecord = $data['results'][0];

echo "<h2>Données de test</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Champ</th><th>Valeur</th></tr>";

$fieldsToShow = [
    '_id' => 'ID',
    'A_1_Nom_de_l_entrepreneur' => 'Nom',
    'A_3_Prenom' => 'Prénom',
    'B_1_Nom_de_l_entreprise' => 'Entreprise',
    'Date_interview' => 'Date interview'
];

foreach ($fieldsToShow as $field => $label) {
    $value = $testRecord[$field] ?? 'N/A';
    echo "<tr><td><strong>$label</strong></td><td>" . htmlspecialchars($value) . "</td></tr>";
}
echo "</table>";

echo "<h2>Options de génération</h2>";

$recordId = $testRecord['_id'] ?? '';
if ($recordId) {
    echo "<div style='margin: 20px 0;'>";
    
    // Bouton pour certificat simple (image)
    echo "<a href='generate_certificate_simple.php?id=" . urlencode($recordId) . "' ";
    echo "class='btn' style='background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; margin: 5px; display: inline-block; border-radius: 5px;'>";
    echo "📄 Générer Certificat Image (Simple)</a>";
    
    // Bouton pour certificat PDF (si TCPDF disponible)
    $tcpdfAvailable = false;
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        $tcpdfAvailable = class_exists('TCPDF');
    }
    
    if ($tcpdfAvailable) {
        echo "<a href='generate_certificate.php?id=" . urlencode($recordId) . "' ";
        echo "class='btn' style='background: #2196F3; color: white; padding: 10px 20px; text-decoration: none; margin: 5px; display: inline-block; border-radius: 5px;'>";
        echo "📋 Générer Certificat PDF (TCPDF)</a>";
    } else {
        echo "<span style='background: #ccc; color: #666; padding: 10px 20px; margin: 5px; display: inline-block; border-radius: 5px;'>";
        echo "📋 Certificat PDF (TCPDF non installé)</span>";
    }
    
    echo "</div>";
} else {
    echo "<div style='color: red;'>Impossible de récupérer l'ID de l'enregistrement de test.</div>";
}

echo "<h2>Vérification des fichiers</h2>";

$files = [
    'Template JPG' => 'assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg',
    'Template PDF' => 'assets/Modèle_Certificats Participant-e-s_FIP_complet.pdf',
    'Logo MOVE' => 'assets/logo-move.png',
    'Logo Transforme' => 'assets/logo-transforme.png',
    'Police Arial' => 'assets/fonts/arial.ttf'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Fichier</th><th>Statut</th><th>Taille</th></tr>";

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    $size = $exists ? filesize($path) : 0;
    $status = $exists ? '✅ Trouvé' : '❌ Manquant';
    $sizeFormatted = $exists ? number_format($size / 1024, 1) . ' KB' : '-';
    
    echo "<tr>";
    echo "<td><strong>$name</strong><br><small>$path</small></td>";
    echo "<td>$status</td>";
    echo "<td>$sizeFormatted</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>Aperçu du template</h2>";

$templateJpg = 'assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg';
if (file_exists($templateJpg)) {
    echo "<img src='$templateJpg' style='max-width: 100%; height: auto; border: 1px solid #ccc;' alt='Template de certificat'>";
} else {
    echo "<div style='background: #f5f5f5; padding: 20px; text-align: center; border: 1px solid #ccc;'>";
    echo "Template non trouvé : $templateJpg";
    echo "</div>";
}

echo "<h2>Informations système</h2>";
echo "<ul>";
echo "<li><strong>Extension GD :</strong> " . (extension_loaded('gd') ? '✅ Installée' : '❌ Manquante') . "</li>";
echo "<li><strong>Extension cURL :</strong> " . (extension_loaded('curl') ? '✅ Installée' : '❌ Manquante') . "</li>";
echo "<li><strong>TCPDF :</strong> " . ($tcpdfAvailable ? '✅ Disponible' : '❌ Non installé') . "</li>";
echo "<li><strong>Limite mémoire :</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>Temps d'exécution max :</strong> " . ini_get('max_execution_time') . "s</li>";
echo "</ul>";

echo "<div style='margin-top: 30px; padding: 15px; background: #e3f2fd; border-left: 4px solid #2196f3;'>";
echo "<h3>Instructions</h3>";
echo "<ol>";
echo "<li>Cliquez sur un des boutons ci-dessus pour tester la génération</li>";
echo "<li>Le certificat devrait se télécharger automatiquement</li>";
echo "<li>Si vous rencontrez des erreurs, vérifiez les fichiers manquants ci-dessus</li>";
echo "<li>Pour une meilleure qualité, installez TCPDF avec : <code>composer require tecnickcom/tcpdf</code></li>";
echo "</ol>";
echo "</div>";

echo "<div style='margin-top: 20px;'>";
echo "<a href='test_batch_certificates.php' style='background: #17a2b8; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; margin: 5px;'>🎓 Test génération en lot</a>";
echo "<a href='index.php' style='color: #0066cc; margin-left: 15px;'>← Retour à la liste des participants</a>";
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
}

th {
    background: #f5f5f5;
}

code {
    background: #f5f5f5;
    padding: 2px 4px;
    border-radius: 3px;
    font-family: monospace;
}

.btn {
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    margin: 5px;
    padding: 10px 20px;
}

.btn:hover {
    opacity: 0.9;
}
</style>
