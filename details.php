<?php
// Inclure les fichiers nécessaires
require_once 'includes/KoboCollectAPI.php';
$config = require_once 'config/kobocollect.php';

// Récupérer l'ID de l'enregistrement depuis l'URL
$recordId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$recordId) {
    header('Location: index.php');
    exit;
}

// Créer l'instance de l'API
$api = new KoboCollectAPI($config);

// Récupérer les données
$data = $api->getData(1000, 0); // Récupérer tous les enregistrements pour trouver l'ID

if (isset($data['error'])) {
    $error = $data['error'];
    $record = null;
} else {
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
        $error = "Enregistrement non trouvé";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Entrepreneur - KoboCollect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-database me-2"></i>
                KoboCollect Data Viewer
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="test_certificate.php">
                    <i class="fas fa-vial me-1"></i>
                    Test Certificats
                </a>
                <a class="nav-link" href="index.php">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <div class="text-center">
                <a href="index.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour à la liste
                </a>
            </div>
        <?php elseif ($record): ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h1 class="card-title mb-1">
                                            <i class="fas fa-user me-2"></i>
                                            <?php echo htmlspecialchars($record['A_3_Prenom'] ?? '') . ' ' . htmlspecialchars($record['A_1_Nom_de_l_entrepreneur'] ?? ''); ?>
                                        </h1>
                                        <h5 class="text-muted mb-3">
                                            <i class="fas fa-building me-2"></i>
                                            <?php echo htmlspecialchars($record['B_1_Nom_de_l_entreprise'] ?? 'Entreprise non renseignée'); ?>
                                        </h5>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="mb-2">
                                            <span class="badge bg-secondary fs-6">
                                                <i class="fas fa-hashtag me-1"></i>
                                                ID: <?php echo htmlspecialchars($record['_id'] ?? 'N/A'); ?>
                                            </span>
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a href="generate_certificate.php?id=<?php echo urlencode($record['_id']); ?>"
                                               class="btn btn-success btn-sm"
                                               title="Générer certificat PDF (nécessite TCPDF)">
                                                <i class="fas fa-certificate me-1"></i>
                                                Certificat PDF
                                            </a>
                                            <a href="generate_certificate_simple.php?id=<?php echo urlencode($record['_id']); ?>"
                                               class="btn btn-outline-success btn-sm"
                                               title="Générer certificat image (simple)">
                                                <i class="fas fa-image me-1"></i>
                                                Certificat IMG
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Images attachées -->
                <?php
                $attachments = $record['_attachments'] ?? [];
                $imageAttachments = [];

                // Filtrer les attachments pour ne garder que les images
                if (!empty($attachments)) {
                    foreach ($attachments as $attachment) {
                        if (isset($attachment['mimetype']) && strpos($attachment['mimetype'], 'image/') === 0) {
                            $imageAttachments[] = $attachment;
                        }
                    }
                }
                ?>

                <?php if (!empty($imageAttachments)): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-images me-2"></i>
                                    Images attachées (<?php echo count($imageAttachments); ?>)
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php foreach ($imageAttachments as $index => $imageAttachment): ?>
                                    <div class="mb-4 <?php echo $index > 0 ? 'border-top pt-4' : ''; ?>">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-image me-2"></i>
                                            <?php
                                            $questionName = $imageAttachment['question_xpath'] ?? 'Image';
                                            $displayName = $questionName === 'Image_piece_identite' ? 'Pièce d\'identité' : $questionName;
                                            echo htmlspecialchars($displayName);
                                            ?>
                                        </h6>
                                        <div class="text-center">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Vue réduite</h6>
                                                    <?php
                                                    $smallUrl = str_replace('?format=json', '', $imageAttachment['download_small_url'] ?? '');
                                                    $proxySmallUrl = 'image_proxy.php?url=' . urlencode($smallUrl);
                                                    ?>
                                                    <img src="<?php echo $proxySmallUrl; ?>"
                                                         class="img-fluid rounded border shadow-sm"
                                                         style="max-height: 250px;"
                                                         alt="<?php echo htmlspecialchars($displayName); ?> - Vue réduite"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                    <div class="alert alert-warning" style="display: none;">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        Image non accessible
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Vue moyenne</h6>
                                                    <?php
                                                    $mediumUrl = str_replace('?format=json', '', $imageAttachment['download_medium_url'] ?? '');
                                                    $proxyMediumUrl = 'image_proxy.php?url=' . urlencode($mediumUrl);
                                                    ?>
                                                    <img src="<?php echo $proxyMediumUrl; ?>"
                                                         class="img-fluid rounded border shadow-sm"
                                                         style="max-height: 250px;"
                                                         alt="<?php echo htmlspecialchars($displayName); ?> - Vue moyenne"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                    <div class="alert alert-warning" style="display: none;">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        Image non accessible
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <?php
                                                $largeUrl = str_replace('?format=json', '', $imageAttachment['download_large_url'] ?? '');
                                                $proxyLargeUrl = 'image_proxy.php?url=' . urlencode($largeUrl);
                                                ?>
                                                <a href="<?php echo $proxyLargeUrl; ?>"
                                                   target="_blank"
                                                   class="btn btn-outline-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    Voir en grand format
                                                </a>
                                                <a href="download_image.php?url=<?php echo urlencode($largeUrl); ?>&filename=<?php echo urlencode($imageAttachment['media_file_basename'] ?? 'image.jpg'); ?>"
                                                   class="btn btn-outline-secondary ms-2"
                                                   download>
                                                    <i class="fas fa-download me-1"></i>
                                                    Télécharger
                                                </a>
                                            </div>
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <strong>Nom du fichier:</strong> <?php echo htmlspecialchars($imageAttachment['media_file_basename'] ?? 'N/A'); ?><br>
                                                    <strong>Type:</strong> <?php echo htmlspecialchars($imageAttachment['mimetype'] ?? 'N/A'); ?><br>
                                                    <strong>Taille:</strong> <?php echo isset($imageAttachment['file_size']) ? number_format($imageAttachment['file_size'] / 1024, 1) . ' KB' : 'N/A'; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Sections des informations -->
                <div class="row">
                    <div class="col-12">
                        <?php
                        // Organiser les champs par sections
                        $sections = [
                            'Informations personnelles' => [
                                'A_1_Nom_de_l_entrepreneur' => 'Nom',
                                'A_2_Post_nom_de_l_entrepreneur' => 'Post-nom',
                                'A_3_Prenom' => 'Prénom',
                                'A_4_Genre' => 'Genre',
                                'Age_entrepreneur' => 'Âge',
                                'A_6_Etat_Civil' => 'État civil',
                                'A_7_Niveau_etude' => 'Niveau d\'étude',
                                'A_8_Nombre_de_personnes_en_ch' => 'Nombre de personnes en charge',
                                'A_9_1_Num_phone_1' => 'Téléphone principal',
                                'A_9_2_Numero_phone_2' => 'Téléphone secondaire',
                                'A_9_3_Num_phone_3' => 'Téléphone tertiaire',
                                'A_10_Adresse_mail_de_l_entrepreneur' => 'Adresse email'
                            ],
                            'Informations de localisation' => [
                                'ville' => 'Ville',
                                'commune' => 'Commune',
                                'Quartiers' => 'Quartier',
                                'Avenue' => 'Avenue',
                                'Num_menage' => 'Numéro de ménage',
                                'communes' => 'Commune (détail)',
                                'Quartier' => 'Quartier (détail)',
                                'Avenue_001' => 'Avenue (détail)',
                                'R_ference' => 'Référence'
                            ],
                            'Informations de l\'entreprise' => [
                                'B_1_Nom_de_l_entreprise' => 'Nom de l\'entreprise',
                                'age_entreprises' => 'Âge de l\'entreprise',
                                'B_2_Depuis_quand_votre_activit_existe' => 'Date de création',
                                'nbre_associe' => 'Nombre d\'associés',
                                'B_3_appartenance_entreprise' => 'Appartenance de l\'entreprise',
                                'secteur_activite' => 'Secteur d\'activité',
                                'Activit' => 'Activité principale',
                                'situation_actuelle' => 'Situation actuelle',
                                'Niveau_formalisation' => 'Niveau de formalisation'
                            ],
                            'Description et outils' => [
                                'C_1_D_crivez_bri_vement_votre_activit' => 'Description de l\'activité',
                                'C_2_outils_utiliser' => 'Outils utilisés',
                                'Comment_allez_vous_justifier_l' => 'Type de justification',
                                'Preuve_1' => 'Preuve 1',
                                'Preuve_2' => 'Preuve 2',
                                'Preuve_3' => 'Preuve 3'
                            ],
                            'Informations d\'identification' => [
                                'Type_piece_identite' => 'Type de pièce d\'identité',
                                'num_piece_identite' => 'Numéro de pièce d\'identité',
                                'identifiant' => 'Identifiant unique',
                                'Date_interview' => 'Date d\'interview',
                                'Mode_enregistrement' => 'Mode d\'enregistrement'
                            ]
                        ];

                        foreach ($sections as $sectionTitle => $fields):
                            $sectionFields = [];
                            foreach ($fields as $field => $label) {
                                if (isset($record[$field]) && $record[$field] !== null && $record[$field] !== '') {
                                    $sectionFields[$field] = $label;
                                }
                            }
                            
                            if (!empty($sectionFields)):
                        ?>
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-folder me-2"></i>
                                        <?php echo htmlspecialchars($sectionTitle); ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($sectionFields as $field => $label): ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex">
                                                    <strong class="text-muted me-2" style="min-width: 200px;">
                                                        <?php echo htmlspecialchars($label); ?>:
                                                    </strong>
                                                    <span class="flex-grow-1">
                                                        <?php
                                                        $value = $record[$field];
                                                        $stringValue = (string)$value;
                                                        
                                                        // Traiter les emails
                                                        if (strpos($stringValue, '@') !== false && strpos($stringValue, '.') !== false) {
                                                            echo '<a href="mailto:' . htmlspecialchars($stringValue) . '" class="text-primary">' . htmlspecialchars($stringValue) . '</a>';
                                                        }
                                                        // Traiter les téléphones
                                                        elseif (preg_match('/^\d{9,}$/', $stringValue)) {
                                                            echo '<a href="tel:' . htmlspecialchars($stringValue) . '" class="text-primary">' . htmlspecialchars($stringValue) . '</a>';
                                                        }
                                                        // Traiter les URLs
                                                        elseif (strpos($stringValue, 'http') === 0) {
                                                            echo '<a href="' . htmlspecialchars($stringValue) . '" target="_blank" class="text-primary">' . htmlspecialchars($stringValue) . '</a>';
                                                        }
                                                        // Traiter les textes longs
                                                        elseif (strlen($stringValue) > 100) {
                                                            echo '<span title="' . htmlspecialchars($stringValue) . '">' . htmlspecialchars(substr($stringValue, 0, 100)) . '...</span>';
                                                        }
                                                        else {
                                                            echo htmlspecialchars($stringValue);
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportRecord() {
            const recordData = <?php echo json_encode($record ?? null); ?>;
            if (!recordData) return;
            
            // Créer le contenu JSON
            const jsonContent = JSON.stringify(recordData, null, 2);
            
            // Créer et télécharger le fichier
            const blob = new Blob([jsonContent], { type: 'application/json;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            
            const nom = recordData['A_1_Nom_de_l_entrepreneur'] || 'unknown';
            const prenom = recordData['A_3_Prenom'] || 'unknown';
            const identifiant = recordData['identifiant'] || 'unknown';
            
            link.setAttribute('download', `entrepreneur_${prenom}_${nom}_${identifiant}.json`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

    </script>
</body>
</html> 