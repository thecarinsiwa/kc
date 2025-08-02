<?php
/**
 * Générateur de tous les certificats PDF en un seul fichier
 */

// Désactiver l'affichage des erreurs pour éviter les problèmes avec le PDF
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

// Augmenter les limites pour traiter plusieurs certificats
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300); // 5 minutes

// Inclure les fichiers nécessaires
require_once 'includes/KoboCollectAPI.php';
$config = require_once 'config/kobocollect.php';

// Vérifier que la configuration est valide
if (!is_array($config)) {
    die('Erreur de configuration : fichier de configuration invalide');
}

// Inclure l'autoloader de Composer pour TCPDF
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
} else {
    die('Composer autoloader non trouvé. Veuillez exécuter : composer install');
}

// Vérifier que TCPDF est disponible
if (!class_exists('TCPDF')) {
    die('TCPDF n\'est pas installé. Veuillez installer TCPDF via Composer : composer require tecnickcom/tcpdf');
}

// Récupérer les paramètres
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 1000;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Créer l'instance de l'API
try {
    $api = new KoboCollectAPI($config);
} catch (Exception $e) {
    die('Erreur de configuration API : ' . $e->getMessage());
}

// Récupérer les données
$data = $api->getData($limit, 0);

if (isset($data['error'])) {
    die('Erreur lors de la récupération des données : ' . $data['error']);
}

// Filtrer les données
$data = $api->filterData($data);

// Appliquer la recherche si spécifiée
if (!empty($search)) {
    $data = $api->searchData($data, $search);
}

if (!isset($data['results']) || empty($data['results'])) {
    die('Aucune donnée trouvée pour générer les certificats');
}

// Classe personnalisée pour les certificats multiples
class AllCertificatesPDF extends \setasign\Fpdi\Tcpdf\Fpdi {
    
    private $templatePath;
    private $logoMovePath;
    private $logoTransformePath;
    private $api;
    
    public function __construct($templatePath, $logoMovePath, $logoTransformePath, $api) {
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);
        
        $this->templatePath = $templatePath;
        $this->logoMovePath = $logoMovePath;
        $this->logoTransformePath = $logoTransformePath;
        $this->api = $api;
        
        // Configuration du PDF
        $this->SetCreator('KoboCollect Certificate Generator');
        $this->SetAuthor('MOVE/Transforme');
        $this->SetTitle('Certificats de Formation - Lot');
        $this->SetSubject('Certificats de Formation FIP - Génération en lot');
        
        // Supprimer les headers et footers par défaut
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        
        // Marges
        $this->SetMargins(0, 0, 0);
        $this->SetAutoPageBreak(false, 0);
    }
    
    public function generateAllCertificates($participantsData) {
        $totalParticipants = count($participantsData);
        $currentParticipant = 0;
        
        foreach ($participantsData as $participantData) {
            $currentParticipant++;
            
            // Ajouter une nouvelle page pour chaque certificat
            $this->AddPage();
            
            // Utiliser le template PDF comme arrière-plan si disponible
            if (file_exists($this->templatePath) && pathinfo($this->templatePath, PATHINFO_EXTENSION) === 'pdf') {
                try {
                    $this->setSourceFile($this->templatePath);
                    $tplId = $this->importPage(1);
                    $this->useTemplate($tplId, 0, 0, 210, 297);
                } catch (Exception $e) {
                    $this->createSimpleBackground();
                }
            } else {
                $this->createSimpleBackground();
            }
            
            // Ajouter les logos
            $this->addLogos();
            
            // Ajouter le contenu du certificat
            $this->addCertificateContent($participantData);
            
            // Ajouter un numéro de page en bas à droite (discret)
            $this->SetFont('helvetica', '', 8);
            $this->SetTextColor(150, 150, 150);
            $this->SetXY(180, 290);
            $this->Cell(20, 5, "$currentParticipant/$totalParticipants", 0, 0, 'R');
        }
    }
    
    private function addLogos() {
        // Logo MOVE (en haut à gauche)
        if (file_exists($this->logoMovePath)) {
            $this->Image($this->logoMovePath, 20, 25, 60, 0, '', '', '', false, 300, '', false, false, 0);
        }
        
        // Logo Transforme (en haut à droite)
        if (file_exists($this->logoTransformePath)) {
            $this->Image($this->logoTransformePath, 130, 25, 60, 0, '', '', '', false, 300, '', false, false, 0);
        }
    }
    
    private function addCertificateContent($data) {
        // Texte "Il est certifié que" (centré, en haut)
        $this->SetFont('helvetica', '', 18);
        $this->SetTextColor(0, 0, 0);
        $this->SetXY(20, 80);
        $this->Cell(170, 10, 'Il est certifié que', 0, 1, 'C');
        
        // Nom de l'entrepreneur (en gras, centré)
        $nom = trim(($data['A_3_Prenom'] ?? '') . ' ' . ($data['A_1_Nom_de_l_entrepreneur'] ?? ''));
        if (empty($nom)) {
            $nom = 'NOM NON RENSEIGNÉ';
        }
        $this->SetFont('helvetica', 'B', 20);
        $this->SetTextColor(0, 0, 0);
        $this->SetXY(20, 95);
        $this->Cell(170, 12, strtoupper($nom), 0, 1, 'C');

        // Ligne horizontale verte
        $this->SetDrawColor(76, 175, 80);
        $this->SetLineWidth(2);
        $this->Line(20, 115, 190, 115);
        
        // Zone bleue pour la photo + informations
        $this->SetFillColor(41, 128, 185);
        $this->Rect(20, 125, 50, 60, 'F');
        
        // Ajouter la photo de l'entrepreneur dans la zone bleue
        $this->addParticipantPhoto($data, 22, 127, 46, 56);
        
        // Texte "photo de candidat-e" en blanc dans la zone bleue
        $this->SetFont('helvetica', 'B', 10);
        $this->SetTextColor(255, 255, 255);
        $this->SetXY(22, 175);
        $this->Cell(46, 8, 'photo de', 0, 1, 'C');
        $this->SetXY(22, 183);
        $this->Cell(46, 8, 'candidat-e', 0, 1, 'C');
        
        // ID et Numéro de carte d'identité
        $this->SetFont('helvetica', '', 12);
        $this->SetTextColor(0, 0, 0);
        
        // ID
        $this->SetXY(80, 130);
        $this->Cell(20, 8, 'ID :', 0, 0, 'L');
        $this->SetXY(100, 130);
        $this->Cell(90, 8, $data['_id'] ?? 'N/A', 0, 1, 'L');
        
        // Ligne sous ID
        $this->Line(100, 140, 190, 140);
        
        // Numéro de carte d'identité
        $this->SetXY(80, 150);
        $this->Cell(110, 8, 'Numéro de la carte d\'identité :', 0, 1, 'L');
        $this->SetXY(80, 160);
        $this->Cell(110, 8, $data['num_piece_identite'] ?? 'N/A', 0, 1, 'L');
        
        // Ligne sous numéro de carte
        $this->Line(80, 170, 190, 170);
        
        // Texte principal de certification
        $this->SetFont('helvetica', '', 16);
        $this->SetXY(20, 200);
        $this->Cell(170, 10, 'a complètement achevé', 0, 1, 'C');
        $this->SetXY(20, 215);
        $this->Cell(170, 10, '(12 modules) la', 0, 1, 'C');
        
        // Titre de la formation en vert
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(76, 175, 80);
        $this->SetXY(20, 235);
        $this->Cell(170, 10, '« Formation à l\'Initiative Personnelle »', 0, 1, 'C');
        
        // Dates et lieu
        $this->SetFont('helvetica', '', 14);
        $this->SetTextColor(0, 0, 0);
        $dateDebut = $data['Date_interview'] ?? date('Y-m-d');
        $dateFin = date('Y-m-d', strtotime($dateDebut . ' +3 months'));
        $ville = $data['ville'] ?? 'Goma';
        
        $this->SetXY(20, 255);
        $this->Cell(170, 8, 'du [' . date('d/m/Y', strtotime($dateDebut)) . '] au [' . date('d/m/Y', strtotime($dateFin)) . ']', 0, 1, 'C');
        $this->SetXY(20, 265);
        $this->Cell(170, 8, 'à [' . $ville . ']', 0, 1, 'C');
        
        // Signatures
        $this->SetFont('helvetica', '', 10);
        $this->SetXY(20, 280);
        $this->Cell(70, 5, '[nom formateur-trice]', 0, 0, 'C');
        $this->SetXY(120, 280);
        $this->Cell(70, 5, 'M Alexis Mangala Coordonnateur', 0, 1, 'C');
        $this->SetXY(120, 287);
        $this->Cell(70, 5, 'National TRANSFORME', 0, 1, 'C');
        
        // Date de certification
        $this->SetXY(20, 295);
        $this->Cell(170, 5, '[date certification]', 0, 1, 'C');
    }
    
    private function createSimpleBackground() {
        // Créer un arrière-plan simple (format portrait)
        $this->SetFillColor(255, 255, 255);
        $this->Rect(0, 0, 210, 297, 'F');
        
        // Bordure verte comme sur le modèle
        $this->SetLineWidth(3);
        $this->SetDrawColor(76, 175, 80);
        $this->Rect(5, 5, 200, 287);
        
        $this->SetLineWidth(1);
        $this->SetDrawColor(200, 200, 200);
        $this->Rect(10, 10, 190, 277);
    }
    
    private function addParticipantPhoto($data, $x, $y, $width, $height) {
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
        
        if ($imageUrl && isset($this->api)) {
            try {
                $imageResult = $this->api->downloadImage($imageUrl);
                
                if ($imageResult !== false) {
                    // Sauvegarder temporairement l'image
                    $tempFile = tempnam(sys_get_temp_dir(), 'cert_photo_') . '.jpg';
                    file_put_contents($tempFile, $imageResult['data']);
                    
                    // Ajouter l'image au PDF
                    $this->Image($tempFile, $x, $y, $width, $height, '', '', '', false, 300, '', false, false, 0);
                    
                    // Supprimer le fichier temporaire
                    unlink($tempFile);
                } else {
                    $this->addPhotoPlaceholder($x, $y, $width, $height);
                }
            } catch (Exception $e) {
                $this->addPhotoPlaceholder($x, $y, $width, $height);
            }
        } else {
            $this->addPhotoPlaceholder($x, $y, $width, $height);
        }
    }
    
    private function addPhotoPlaceholder($x, $y, $width, $height) {
        // Créer un placeholder pour la photo
        $this->SetFillColor(200, 200, 200);
        $this->Rect($x, $y, $width, $height, 'F');
        
        // Ajouter du texte "Photo"
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY($x, $y + $height/2 - 2);
        $this->Cell($width, 4, 'Photo', 0, 1, 'C');
        $this->SetXY($x, $y + $height/2 + 2);
        $this->Cell($width, 4, 'non disponible', 0, 1, 'C');
    }
}

// Générer tous les certificats
try {
    $templatePath = 'assets/Modèle_Certificats Participant-e-s_FIP_complet.pdf';
    $logoMovePath = 'assets/logo-move.png';
    $logoTransformePath = 'assets/logo-transforme.png';
    
    $pdf = new AllCertificatesPDF($templatePath, $logoMovePath, $logoTransformePath, $api);
    $pdf->generateAllCertificates($data['results']);
    
    // Nom du fichier
    $totalCertificats = count($data['results']);
    $filename = 'Certificats_Lot_' . $totalCertificats . '_participants_' . date('Y-m-d_H-i-s') . '.pdf';
    
    // Sortie du PDF
    $pdf->Output($filename, 'D'); // 'D' pour téléchargement
    
} catch (Exception $e) {
    die('Erreur lors de la génération des certificats : ' . $e->getMessage());
}
?>
