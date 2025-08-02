<?php
/**
 * Script d'installation pour le générateur de certificats
 */

echo "<h1>Installation du Générateur de Certificats</h1>";

// Vérifier les prérequis
echo "<h2>Vérification des prérequis</h2>";

$requirements = [
    'PHP Version >= 7.4' => version_compare(PHP_VERSION, '7.4.0', '>='),
    'Extension GD' => extension_loaded('gd'),
    'Extension cURL' => extension_loaded('curl'),
    'Extension JSON' => extension_loaded('json'),
    'Dossier assets accessible' => is_dir('assets'),
    'Template de certificat' => file_exists('assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg'),
    'Logo MOVE' => file_exists('assets/logo-move.png'),
    'Logo Transforme' => file_exists('assets/logo-transforme.png'),
];

$allOk = true;
foreach ($requirements as $requirement => $status) {
    $icon = $status ? '✅' : '❌';
    $color = $status ? 'green' : 'red';
    echo "<p style='color: $color;'>$icon $requirement</p>";
    if (!$status) $allOk = false;
}

if (!$allOk) {
    echo "<div style='background: #ffebee; padding: 15px; border-left: 4px solid #f44336; margin: 20px 0;'>";
    echo "<strong>⚠️ Certains prérequis ne sont pas satisfaits.</strong><br>";
    echo "Veuillez corriger les problèmes ci-dessus avant de continuer.";
    echo "</div>";
} else {
    echo "<div style='background: #e8f5e8; padding: 15px; border-left: 4px solid #4caf50; margin: 20px 0;'>";
    echo "<strong>✅ Tous les prérequis sont satisfaits !</strong>";
    echo "</div>";
}

// Vérifier TCPDF
echo "<h2>Vérification de TCPDF (optionnel)</h2>";

$tcpdfAvailable = false;
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    $tcpdfAvailable = class_exists('TCPDF');
}

if ($tcpdfAvailable) {
    echo "<p style='color: green;'>✅ TCPDF est installé et disponible</p>";
} else {
    echo "<p style='color: orange;'>⚠️ TCPDF n'est pas installé</p>";
    echo "<p>Pour installer TCPDF, exécutez la commande suivante dans le terminal :</p>";
    echo "<code style='background: #f5f5f5; padding: 10px; display: block; margin: 10px 0;'>composer install</code>";
    echo "<p>Ou si Composer n'est pas configuré :</p>";
    echo "<code style='background: #f5f5f5; padding: 10px; display: block; margin: 10px 0;'>composer require tecnickcom/tcpdf</code>";
}

// Créer les dossiers nécessaires
echo "<h2>Création des dossiers</h2>";

$directories = [
    'assets/fonts',
    'certificates',
    'temp'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "<p style='color: green;'>✅ Dossier créé : $dir</p>";
        } else {
            echo "<p style='color: red;'>❌ Impossible de créer le dossier : $dir</p>";
        }
    } else {
        echo "<p style='color: blue;'>ℹ️ Dossier existe déjà : $dir</p>";
    }
}

// Télécharger une police par défaut
echo "<h2>Police par défaut</h2>";

$fontPath = 'assets/fonts/arial.ttf';
if (!file_exists($fontPath)) {
    echo "<p style='color: orange;'>⚠️ Police Arial non trouvée</p>";
    echo "<p>Pour une meilleure qualité de texte, vous pouvez :</p>";
    echo "<ul>";
    echo "<li>Télécharger une police TTF et la placer dans <code>assets/fonts/arial.ttf</code></li>";
    echo "<li>Ou utiliser la version simple qui fonctionne sans police externe</li>";
    echo "</ul>";
} else {
    echo "<p style='color: green;'>✅ Police trouvée : $fontPath</p>";
}

// Test de génération
echo "<h2>Test de génération</h2>";

if ($allOk) {
    echo "<p>Vous pouvez maintenant tester la génération de certificats :</p>";
    echo "<ul>";
    echo "<li><a href='test_certificate.php' target='_blank'>Tester la génération de certificat</a></li>";
    echo "<li><a href='index.php'>Retourner à la liste des participants</a></li>";
    echo "</ul>";
    
    echo "<h3>Utilisation</h3>";
    echo "<ol>";
    echo "<li>Allez sur la page de détails d'un participant</li>";
    echo "<li>Cliquez sur le bouton 'Certificat PDF' ou 'Certificat IMG'</li>";
    echo "<li>Le certificat sera généré et téléchargé automatiquement</li>";
    echo "</ol>";
} else {
    echo "<p style='color: red;'>Veuillez corriger les problèmes ci-dessus avant de tester.</p>";
}

echo "<h2>Informations techniques</h2>";
echo "<ul>";
echo "<li><strong>Version PHP :</strong> " . PHP_VERSION . "</li>";
echo "<li><strong>Extensions chargées :</strong> " . implode(', ', get_loaded_extensions()) . "</li>";
echo "<li><strong>Limite mémoire :</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>Temps d'exécution max :</strong> " . ini_get('max_execution_time') . "s</li>";
echo "</ul>";

?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    line-height: 1.6;
}

h1, h2, h3 {
    color: #333;
}

code {
    background: #f5f5f5;
    padding: 2px 4px;
    border-radius: 3px;
    font-family: monospace;
}

ul, ol {
    margin: 10px 0;
    padding-left: 30px;
}

a {
    color: #0066cc;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
