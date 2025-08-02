<?php
/**
 * Générateur de certificat simple utilisant GD Library
 * Alternative si TCPDF n'est pas disponible
 */

// Désactiver l'affichage des erreurs pour éviter les problèmes avec l'image
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

// Inclure les fichiers nécessaires
require_once 'includes/KoboCollectAPI.php';
$config = require_once 'config/kobocollect.php';

// Vérifier que la configuration est valide
if (!is_array($config)) {
    die('Erreur de configuration : fichier de configuration invalide');
}

// Vérifier que GD est installé
if (!extension_loaded('gd')) {
    die('L\'extension GD n\'est pas installée. Veuillez l\'activer dans PHP.');
}

// Récupérer l'ID de l'enregistrement
$recordId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$recordId) {
    header('Location: index.php');
    exit;
}

// Créer l'instance de l'API
try {
    $api = new KoboCollectAPI($config);
} catch (Exception $e) {
    die('Erreur de configuration API : ' . $e->getMessage());
}

// Récupérer les données
$data = $api->getData(1000, 0);

if (isset($data['error'])) {
    die('Erreur lors de la récupération des données : ' . $data['error']);
}

// Filtrer les données
$data = $api->filterData($data);

// Trouver l'enregistrement par ID
$record = null;
if (isset($data['results'])) {
    foreach ($data['results'] as $result) {
        if (isset($result['_id']) && $result['_id'] == $recordId) {
            $record = $result;
            break;
        }
    }
}

if (!$record) {
    die('Enregistrement non trouvé');
}

// Fonction pour créer le certificat
function generateCertificateImage($participantData, $api = null) {
    // Dimensions du certificat (A4 portrait en pixels à 300 DPI)
    $width = 2480;  // 210mm à 300 DPI
    $height = 3508; // 297mm à 300 DPI
    
    // Créer l'image
    $image = imagecreatetruecolor($width, $height);
    
    // Couleurs
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    $blue = imagecolorallocate($image, 0, 102, 204);
    $red = imagecolorallocate($image, 204, 0, 0);
    $lightBlue = imagecolorallocate($image, 0, 153, 255);
    
    // Remplir le fond en blanc
    imagefill($image, 0, 0, $white);
    
    // Charger le template s'il existe (version JPG)
    $templatePath = 'assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg';
    if (file_exists($templatePath)) {
        $template = imagecreatefromjpeg($templatePath);
        if ($template) {
            // Redimensionner le template pour qu'il corresponde à nos dimensions
            imagecopyresampled($image, $template, 0, 0, 0, 0, $width, $height, imagesx($template), imagesy($template));
            imagedestroy($template);
        }
    } else {
        // Créer un arrière-plan simple si pas de template (format portrait)
        // Bordure verte comme sur le modèle
        $green = imagecolorallocate($image, 76, 175, 80);
        imagesetthickness($image, 12);
        imagerectangle($image, 20, 20, $width-20, $height-20, $green);

        imagesetthickness($image, 4);
        $lightGray = imagecolorallocate($image, 200, 200, 200);
        imagerectangle($image, 40, 40, $width-40, $height-40, $lightGray);
    }
    
    // Charger et ajouter les logos
    addLogos($image, $width, $height);
    
    // Ajouter le texte du certificat
    addCertificateText($image, $participantData, $width, $height, $black, $blue, $red, $api);
    
    return $image;
}

function addLogos($image, $width, $height) {
    // Logo MOVE (en haut à gauche) - format portrait
    $logoMovePath = 'assets/logo-move.png';
    if (file_exists($logoMovePath)) {
        $logoMove = imagecreatefrompng($logoMovePath);
        if ($logoMove) {
            $logoWidth = 300;
            $logoHeight = imagesy($logoMove) * ($logoWidth / imagesx($logoMove));
            imagecopyresampled($image, $logoMove, 100, 120, 0, 0, $logoWidth, $logoHeight, imagesx($logoMove), imagesy($logoMove));
            imagedestroy($logoMove);
        }
    }

    // Logo Transforme (en haut à droite) - format portrait
    $logoTransformePath = 'assets/logo-transforme.png';
    if (file_exists($logoTransformePath)) {
        $logoTransforme = imagecreatefrompng($logoTransformePath);
        if ($logoTransforme) {
            $logoWidth = 300;
            $logoHeight = imagesy($logoTransforme) * ($logoWidth / imagesx($logoTransforme));
            imagecopyresampled($image, $logoTransforme, $width - 400, 120, 0, 0, $logoWidth, $logoHeight, imagesx($logoTransforme), imagesy($logoTransforme));
            imagedestroy($logoTransforme);
        }
    }
}

function addCertificateText($image, $data, $width, $height, $black, $blue, $red, $api = null) {
    // Chemin vers une police (vous pouvez télécharger une police TTF)
    $fontPath = __DIR__ . '/assets/fonts/arial.ttf';
    $useFont = file_exists($fontPath);

    // Couleurs supplémentaires
    $green = imagecolorallocate($image, 76, 175, 80);
    $white = imagecolorallocate($image, 255, 255, 255);

    // Texte "Il est certifié que" (centré, en haut)
    $mainText = 'Il est certifie que';
    if ($useFont) {
        imagettftext($image, 36, 0, $width/2 - 200, 400, $black, $fontPath, $mainText);
    } else {
        imagestring($image, 5, $width/2 - 120, 350, $mainText, $black);
    }

    // Nom de l'entrepreneur (en gras, centré)
    $nom = trim(($data['A_3_Prenom'] ?? '') . ' ' . ($data['A_1_Nom_de_l_entrepreneur'] ?? ''));
    if (empty($nom)) {
        $nom = 'NOM NON RENSEIGNE';
    }
    $nom = strtoupper($nom);
    if ($useFont) {
        imagettftext($image, 40, 0, $width/2 - strlen($nom) * 12, 450, $black, $fontPath, $nom);
    } else {
        imagestring($image, 5, $width/2 - strlen($nom) * 6, 380, $nom, $black);
    }

    // Ligne horizontale verte
    imagesetthickness($image, 8);
    imageline($image, 100, 500, $width - 100, 500, $green);

    // Zone bleue pour la photo
    $blueZone = imagecolorallocate($image, 41, 128, 185);
    imagefilledrectangle($image, 100, 540, 350, 820, $blueZone);

    // Ajouter la photo de l'entrepreneur
    addParticipantPhoto($image, $data, 110, 550, 230, 280, $api);

    // Texte "photo de candidat-e" en blanc dans la zone bleue
    if ($useFont) {
        imagettftext($image, 20, 0, 150, 870, $white, $fontPath, 'photo de');
        imagettftext($image, 20, 0, 140, 900, $white, $fontPath, 'candidat-e');
    } else {
        imagestring($image, 3, 150, 840, 'photo de', $white);
        imagestring($image, 3, 140, 860, 'candidat-e', $white);
    }

    // ID et Numéro de carte d'identité
    $idText = 'ID : ' . ($data['_id'] ?? 'N/A');
    if ($useFont) {
        imagettftext($image, 24, 0, 400, 600, $black, $fontPath, $idText);
    } else {
        imagestring($image, 4, 400, 570, $idText, $black);
    }

    // Ligne sous ID
    imageline($image, 400, 620, $width - 100, 620, $black);

    // Numéro de carte d'identité
    $cardText = 'Numero de la carte d\'identite :';
    $cardNumber = $data['num_piece_identite'] ?? 'N/A';
    if ($useFont) {
        imagettftext($image, 20, 0, 400, 670, $black, $fontPath, $cardText);
        imagettftext($image, 20, 0, 400, 700, $black, $fontPath, $cardNumber);
    } else {
        imagestring($image, 3, 400, 640, $cardText, $black);
        imagestring($image, 3, 400, 660, $cardNumber, $black);
    }

    // Ligne sous numéro de carte
    imageline($image, 400, 740, $width - 100, 740, $black);

    // Texte principal de certification
    $certText1 = 'a completement acheve';
    $certText2 = '(12 modules) la';
    if ($useFont) {
        imagettftext($image, 32, 0, $width/2 - 250, 970, $black, $fontPath, $certText1);
        imagettftext($image, 32, 0, $width/2 - 200, 1020, $black, $fontPath, $certText2);
    } else {
        imagestring($image, 4, $width/2 - 150, 920, $certText1, $black);
        imagestring($image, 4, $width/2 - 120, 950, $certText2, $black);
    }

    // Titre de la formation en vert
    $formationTitle = '« Formation a l\'Initiative Personnelle »';
    if ($useFont) {
        imagettftext($image, 32, 0, $width/2 - 350, 1120, $green, $fontPath, $formationTitle);
    } else {
        imagestring($image, 4, $width/2 - 200, 1070, $formationTitle, $green);
    }

    // Dates et lieu
    $dateDebut = $data['Date_interview'] ?? date('Y-m-d');
    $dateFin = date('Y-m-d', strtotime($dateDebut . ' +3 months'));
    $ville = $data['ville'] ?? 'Goma';

    $dateText = 'du [' . date('d/m/Y', strtotime($dateDebut)) . '] au [' . date('d/m/Y', strtotime($dateFin)) . ']';
    $lieuText = 'a [' . $ville . ']';

    if ($useFont) {
        imagettftext($image, 28, 0, $width/2 - 300, 1220, $black, $fontPath, $dateText);
        imagettftext($image, 28, 0, $width/2 - 100, 1270, $black, $fontPath, $lieuText);
    } else {
        imagestring($image, 3, $width/2 - 200, 1170, $dateText, $black);
        imagestring($image, 3, $width/2 - 80, 1200, $lieuText, $black);
    }

    // Signatures
    $sig1 = '[nom formateur-trice]';
    $sig2 = 'M Alexis Mangala Coordonnateur';
    $sig3 = 'National TRANSFORME';

    if ($useFont) {
        imagettftext($image, 20, 0, 200, 1370, $black, $fontPath, $sig1);
        imagettftext($image, 20, 0, $width - 600, 1370, $black, $fontPath, $sig2);
        imagettftext($image, 20, 0, $width - 600, 1400, $black, $fontPath, $sig3);
    } else {
        imagestring($image, 2, 200, 1340, $sig1, $black);
        imagestring($image, 2, $width - 400, 1340, $sig2, $black);
        imagestring($image, 2, $width - 400, 1360, $sig3, $black);
    }

    // Date de certification
    $certDate = '[date certification]';
    if ($useFont) {
        imagettftext($image, 20, 0, $width/2 - 100, 1470, $black, $fontPath, $certDate);
    } else {
        imagestring($image, 2, $width/2 - 80, 1440, $certDate, $black);
    }
}

function addParticipantPhoto($image, $data, $x, $y, $width, $height, $api = null) {
    // Récupérer l'image de la pièce d'identité depuis les attachments
    $attachments = $data['_attachments'] ?? [];
    $imageUrl = null;

    // Chercher l'image de la pièce d'identité
    foreach ($attachments as $attachment) {
        if (isset($attachment['question_xpath']) &&
            $attachment['question_xpath'] === 'Image_piece_identite' &&
            isset($attachment['download_medium_url'])) {
            $imageUrl = str_replace('?format=json', '', $attachment['download_medium_url']);
            break;
        }
    }

    if ($imageUrl && $api) {
        try {
            // Utiliser l'API pour télécharger l'image avec authentification

            $imageResult = $api->downloadImage($imageUrl);

            if ($imageResult !== false) {
                // Créer une image depuis les données
                $photoImage = imagecreatefromstring($imageResult['data']);

                if ($photoImage) {
                    // Redimensionner et ajouter l'image
                    imagecopyresampled($image, $photoImage, $x, $y, 0, 0, $width, $height,
                                     imagesx($photoImage), imagesy($photoImage));
                    imagedestroy($photoImage);
                } else {
                    addPhotoPlaceholder($image, $x, $y, $width, $height);
                }
            } else {
                addPhotoPlaceholder($image, $x, $y, $width, $height);
            }
        } catch (Exception $e) {
            addPhotoPlaceholder($image, $x, $y, $width, $height);
        }
    } else {
        addPhotoPlaceholder($image, $x, $y, $width, $height);
    }
}

function addPhotoPlaceholder($image, $x, $y, $width, $height) {
    // Créer un placeholder pour la photo
    $gray = imagecolorallocate($image, 200, 200, 200);
    $darkGray = imagecolorallocate($image, 100, 100, 100);

    imagefilledrectangle($image, $x, $y, $x + $width, $y + $height, $gray);

    // Ajouter du texte "Photo non disponible"
    imagestring($image, 3, $x + 10, $y + $height/2 - 10, 'Photo', $darkGray);
    imagestring($image, 3, $x + 10, $y + $height/2 + 5, 'non disponible', $darkGray);
}

// Générer le certificat
try {
    $certificateImage = generateCertificateImage($record, $api);
    
    // Nom du fichier
    $nom = trim(($record['A_3_Prenom'] ?? '') . '_' . ($record['A_1_Nom_de_l_entrepreneur'] ?? ''));
    $nom = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nom);
    $filename = 'Certificat_' . $nom . '_' . date('Y-m-d') . '.png';
    
    // Headers pour le téléchargement
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, must-revalidate');
    
    // Sortie de l'image
    imagepng($certificateImage);
    
    // Libérer la mémoire
    imagedestroy($certificateImage);
    
} catch (Exception $e) {
    die('Erreur lors de la génération du certificat : ' . $e->getMessage());
}
?>
