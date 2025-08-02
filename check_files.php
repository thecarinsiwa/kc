<?php
/**
 * Script pour vérifier les fichiers avec caractères spéciaux
 */

echo "<h1>Vérification des fichiers</h1>";

$files = [
    'Template JPG' => 'assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg',
    'Template PDF' => 'assets/Modèle_Certificats Participant-e-s_FIP_complet.pdf',
    'Logo MOVE' => 'assets/logo-move.png',
    'Logo Transforme' => 'assets/logo-transforme.png'
];

foreach ($files as $name => $path) {
    echo "<h3>$name</h3>";
    echo "<p><strong>Chemin :</strong> $path</p>";
    echo "<p><strong>Existe :</strong> " . (file_exists($path) ? '✅ Oui' : '❌ Non') . "</p>";
    
    if (file_exists($path)) {
        echo "<p><strong>Taille :</strong> " . number_format(filesize($path) / 1024, 1) . " KB</p>";
        echo "<p><strong>Type MIME :</strong> " . mime_content_type($path) . "</p>";
        echo "<p><strong>Permissions :</strong> " . substr(sprintf('%o', fileperms($path)), -4) . "</p>";
        
        // Test de lecture
        $handle = fopen($path, 'r');
        if ($handle) {
            echo "<p><strong>Lecture :</strong> ✅ OK</p>";
            fclose($handle);
        } else {
            echo "<p><strong>Lecture :</strong> ❌ Erreur</p>";
        }
    }
    
    echo "<hr>";
}

// Test de création d'image
echo "<h2>Test de création d'image</h2>";

if (extension_loaded('gd')) {
    echo "<p>✅ Extension GD disponible</p>";
    
    // Test de création d'une image simple
    $testImage = imagecreatetruecolor(100, 100);
    if ($testImage) {
        echo "<p>✅ Création d'image réussie</p>";
        imagedestroy($testImage);
    } else {
        echo "<p>❌ Erreur de création d'image</p>";
    }
    
    // Test de chargement du template JPG
    $templatePath = 'assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg';
    if (file_exists($templatePath)) {
        $template = imagecreatefromjpeg($templatePath);
        if ($template) {
            echo "<p>✅ Chargement du template JPG réussi</p>";
            echo "<p><strong>Dimensions :</strong> " . imagesx($template) . " x " . imagesy($template) . " pixels</p>";
            imagedestroy($template);
        } else {
            echo "<p>❌ Erreur de chargement du template JPG</p>";
        }
    }
} else {
    echo "<p>❌ Extension GD non disponible</p>";
}

// Test TCPDF
echo "<h2>Test TCPDF</h2>";

if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    
    if (class_exists('TCPDF')) {
        echo "<p>✅ TCPDF disponible</p>";
        
        try {
            $pdf = new TCPDF();
            echo "<p>✅ Création d'instance TCPDF réussie</p>";
        } catch (Exception $e) {
            echo "<p>❌ Erreur TCPDF : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>❌ Classe TCPDF non trouvée</p>";
    }
    
    if (class_exists('setasign\Fpdi\Tcpdf\Fpdi')) {
        echo "<p>✅ FPDI disponible</p>";
        
        // Test de chargement du template PDF
        $templatePdfPath = 'assets/Modèle_Certificats Participant-e-s_FIP_complet.pdf';
        if (file_exists($templatePdfPath)) {
            try {
                $fpdi = new \setasign\Fpdi\Tcpdf\Fpdi();
                $fpdi->setSourceFile($templatePdfPath);
                echo "<p>✅ Chargement du template PDF réussi</p>";
                
                $pageCount = $fpdi->setSourceFile($templatePdfPath);
                echo "<p><strong>Nombre de pages :</strong> $pageCount</p>";
            } catch (Exception $e) {
                echo "<p>❌ Erreur de chargement du template PDF : " . $e->getMessage() . "</p>";
            }
        }
    } else {
        echo "<p>❌ FPDI non disponible</p>";
    }
} else {
    echo "<p>❌ Autoloader Composer non trouvé</p>";
}

echo "<h2>Informations système</h2>";
echo "<ul>";
echo "<li><strong>PHP Version :</strong> " . PHP_VERSION . "</li>";
echo "<li><strong>OS :</strong> " . PHP_OS . "</li>";
echo "<li><strong>Répertoire de travail :</strong> " . getcwd() . "</li>";
echo "<li><strong>Limite mémoire :</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>Extensions chargées :</strong> " . count(get_loaded_extensions()) . "</li>";
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

hr {
    margin: 20px 0;
    border: none;
    border-top: 1px solid #ddd;
}
</style>
