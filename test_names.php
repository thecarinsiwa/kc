<?php
/**
 * Script de test pour vérifier les noms des entrepreneurs
 */

// Inclure les fichiers nécessaires
require_once 'includes/KoboCollectAPI.php';
$config = require_once 'config/kobocollect.php';

echo "<h1>Test des Noms d'Entrepreneurs</h1>";

// Créer l'instance de l'API
try {
    $api = new KoboCollectAPI($config);
} catch (Exception $e) {
    die('Erreur de configuration API : ' . $e->getMessage());
}

// Récupérer quelques données pour tester
echo "<h2>Récupération des données...</h2>";
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

echo "<h2>Analyse des noms dans les enregistrements...</h2>";

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th>ID</th>";
echo "<th>Prénom (A_3_Prenom)</th>";
echo "<th>Nom (A_1_Nom_de_l_entrepreneur)</th>";
echo "<th>Nom Complet</th>";
echo "<th>Entreprise</th>";
echo "<th>Actions</th>";
echo "</tr>";

foreach ($data['results'] as $index => $record) {
    $id = $record['_id'] ?? 'N/A';
    $prenom = $record['A_3_Prenom'] ?? '';
    $nom = $record['A_1_Nom_de_l_entrepreneur'] ?? '';
    $entreprise = $record['B_1_Nom_de_l_entreprise'] ?? '';
    
    // Construire le nom complet comme dans le certificat
    $nomComplet = trim($prenom . ' ' . $nom);
    if (empty($nomComplet)) {
        $nomComplet = 'NOM NON RENSEIGNÉ';
    }
    
    echo "<tr>";
    echo "<td>" . htmlspecialchars($id) . "</td>";
    echo "<td>" . htmlspecialchars($prenom) . "</td>";
    echo "<td>" . htmlspecialchars($nom) . "</td>";
    echo "<td><strong>" . htmlspecialchars(strtoupper($nomComplet)) . "</strong></td>";
    echo "<td>" . htmlspecialchars($entreprise) . "</td>";
    echo "<td>";
    echo "<a href='generate_certificate_simple.php?id=" . urlencode($id) . "' target='_blank' style='color: #28a745; text-decoration: none; margin: 2px;'>📄 IMG</a> ";
    echo "<a href='generate_certificate.php?id=" . urlencode($id) . "' target='_blank' style='color: #007bff; text-decoration: none; margin: 2px;'>📋 PDF</a> ";
    echo "<a href='details.php?id=" . urlencode($id) . "' target='_blank' style='color: #6c757d; text-decoration: none; margin: 2px;'>👁️ Détails</a>";
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

// Statistiques
$totalRecords = count($data['results']);
$recordsWithNames = 0;
$recordsWithFirstName = 0;
$recordsWithLastName = 0;

foreach ($data['results'] as $record) {
    $prenom = $record['A_3_Prenom'] ?? '';
    $nom = $record['A_1_Nom_de_l_entrepreneur'] ?? '';
    
    if (!empty($prenom)) $recordsWithFirstName++;
    if (!empty($nom)) $recordsWithLastName++;
    if (!empty(trim($prenom . ' ' . $nom))) $recordsWithNames++;
}

echo "<h2>Statistiques</h2>";
echo "<ul>";
echo "<li><strong>Total d'enregistrements :</strong> $totalRecords</li>";
echo "<li><strong>Avec prénom :</strong> $recordsWithFirstName (" . round($recordsWithFirstName/$totalRecords*100, 1) . "%)</li>";
echo "<li><strong>Avec nom :</strong> $recordsWithLastName (" . round($recordsWithLastName/$totalRecords*100, 1) . "%)</li>";
echo "<li><strong>Avec nom complet :</strong> $recordsWithNames (" . round($recordsWithNames/$totalRecords*100, 1) . "%)</li>";
echo "</ul>";

if ($recordsWithNames < $totalRecords) {
    echo "<div style='background: #fff3cd; padding: 15px; border: 1px solid #ffeaa7; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>⚠️ Attention</h3>";
    echo "<p>Certains enregistrements n'ont pas de nom complet. Sur le certificat, ils apparaîtront comme 'NOM NON RENSEIGNÉ'.</p>";
    echo "<p><strong>Vérifiez :</strong></p>";
    echo "<ul>";
    echo "<li>Que les champs 'A_3_Prenom' et 'A_1_Nom_de_l_entrepreneur' sont bien remplis dans KoboToolbox</li>";
    echo "<li>Que les noms des champs correspondent à votre formulaire</li>";
    echo "</ul>";
    echo "</div>";
}

echo "<h2>Test avec un enregistrement spécifique</h2>";
if (!empty($data['results'])) {
    $testRecord = $data['results'][0];
    $testId = $testRecord['_id'] ?? 'N/A';
    
    echo "<p>Test avec l'enregistrement ID: <strong>" . htmlspecialchars($testId) . "</strong></p>";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h4>Données brutes :</h4>";
    echo "<pre>";
    echo "A_3_Prenom: '" . ($testRecord['A_3_Prenom'] ?? 'NULL') . "'\n";
    echo "A_1_Nom_de_l_entrepreneur: '" . ($testRecord['A_1_Nom_de_l_entrepreneur'] ?? 'NULL') . "'\n";
    echo "B_1_Nom_de_l_entreprise: '" . ($testRecord['B_1_Nom_de_l_entreprise'] ?? 'NULL') . "'\n";
    echo "</pre>";
    
    $nomComplet = trim(($testRecord['A_3_Prenom'] ?? '') . ' ' . ($testRecord['A_1_Nom_de_l_entrepreneur'] ?? ''));
    if (empty($nomComplet)) {
        $nomComplet = 'NOM NON RENSEIGNÉ';
    }
    
    echo "<h4>Nom qui apparaîtra sur le certificat :</h4>";
    echo "<p style='font-size: 1.2rem; font-weight: bold; color: #007bff;'>" . htmlspecialchars(strtoupper($nomComplet)) . "</p>";
    echo "</div>";
    
    echo "<div style='margin: 20px 0;'>";
    echo "<a href='generate_certificate_simple.php?id=" . urlencode($testId) . "' target='_blank' ";
    echo "style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>";
    echo "🎓 Générer Certificat Image</a>";
    
    echo "<a href='generate_certificate.php?id=" . urlencode($testId) . "' target='_blank' ";
    echo "style='background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin: 5px;'>";
    echo "🎓 Générer Certificat PDF</a>";
    echo "</div>";
}

echo "<div style='margin-top: 30px;'>";
echo "<a href='test_certificate.php' style='color: #0066cc;'>← Test général des certificats</a> | ";
echo "<a href='index.php' style='color: #0066cc;'>Retour à la liste</a>";
echo "</div>";

?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
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

pre {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 3px;
    font-size: 0.9rem;
}
</style>
