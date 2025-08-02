<?php
/**
 * Démonstration du certificat avec données d'exemple
 */

// Données d'exemple
$demoData = [
    '_id' => 'DEMO123',
    'A_1_Nom_de_l_entrepreneur' => 'SAFARI',
    'A_3_Prenom' => 'IKAKA',
    'B_1_Nom_de_l_entreprise' => 'SALON AIGLE',
    'Date_interview' => '2024-11-18'
];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Démonstration Certificat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .certificate-preview {
            background: white;
            border: 4px solid #4CAF50;
            border-radius: 10px;
            padding: 40px;
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            position: relative;
            min-height: 700px;
            width: 600px;
            margin: 20px auto;
        }
        
        .certificate-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .certificate-title {
            color: #0066cc;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .certificate-subtitle {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        .participant-name {
            color: #cc0000;
            font-size: 2rem;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0;
        }
        
        .logo-container {
            position: absolute;
            top: 20px;
        }
        
        .logo-left {
            left: 20px;
        }
        
        .logo-right {
            right: 20px;
        }
        
        .logo-container img {
            max-width: 80px;
            height: auto;
        }
        
        .certificate-text {
            font-size: 1.1rem;
            line-height: 1.6;
            text-align: center;
            margin: 20px 0;
        }
        
        .signature-area {
            margin-top: 50px;
            text-align: right;
            font-style: italic;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-certificate me-2"></i>
                Démonstration Certificat
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
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">
                    <i class="fas fa-certificate me-2"></i>
                    Aperçu du Certificat
                </h1>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Ceci est un aperçu de ce à quoi ressemblera le certificat généré. 
                    Les données utilisées sont celles de l'exemple : <strong><?php echo htmlspecialchars($demoData['A_3_Prenom'] . ' ' . $demoData['A_1_Nom_de_l_entrepreneur']); ?></strong>
                </div>
                
                <!-- Aperçu du certificat -->
                <div class="certificate-preview">
                    <!-- Logos -->
                    <div class="logo-container logo-left">
                        <?php if (file_exists('assets/logo-move.png')): ?>
                            <img src="assets/logo-move.png" alt="Logo MOVE">
                        <?php else: ?>
                            <div class="text-muted small">Logo MOVE</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="logo-container logo-right">
                        <?php if (file_exists('assets/logo-transforme.png')): ?>
                            <img src="assets/logo-transforme.png" alt="Logo Transforme">
                        <?php else: ?>
                            <div class="text-muted small">Logo Transforme</div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Contenu du certificat selon le nouveau modèle -->
                    <div class="certificate-text" style="text-align: center; font-size: 1.2rem; margin-top: 20px;">
                        Il est certifié que
                    </div>

                    <!-- Nom de l'entrepreneur -->
                    <div style="text-align: center; font-size: 1.5rem; font-weight: bold; margin: 15px 0; text-transform: uppercase;">
                        <?php echo htmlspecialchars($demoData['A_3_Prenom'] . ' ' . $demoData['A_1_Nom_de_l_entrepreneur']); ?>
                    </div>

                    <!-- Ligne verte -->
                    <hr style="border: 2px solid #4CAF50; width: 80%; margin: 20px auto;">

                    <!-- Zone avec photo et informations -->
                    <div style="display: flex; margin: 30px 0; align-items: flex-start;">
                        <!-- Zone bleue pour la photo -->
                        <div style="background: #2980b9; color: white; padding: 20px; width: 120px; height: 150px; display: flex; flex-direction: column; justify-content: center; align-items: center; margin-right: 20px;">
                            <div style="background: #ccc; width: 80px; height: 100px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; font-size: 0.8rem; color: #666;">
                                Photo
                            </div>
                            <div style="font-size: 0.9rem; text-align: center;">
                                photo de<br>candidat-e
                            </div>
                        </div>

                        <!-- Informations -->
                        <div style="flex: 1;">
                            <div style="margin-bottom: 15px;">
                                <strong>ID :</strong> <?php echo htmlspecialchars($demoData['_id']); ?>
                                <hr style="border: 1px solid #ccc; margin: 5px 0;">
                            </div>
                            <div>
                                <strong>Numéro de la carte d'identité :</strong><br>
                                [Numéro de carte]
                                <hr style="border: 1px solid #ccc; margin: 5px 0;">
                            </div>
                        </div>
                    </div>

                    <!-- Texte de certification -->
                    <div class="certificate-text" style="text-align: center; margin: 30px 0;">
                        a complètement achevé<br>
                        (12 modules) la
                    </div>

                    <!-- Titre de la formation en vert -->
                    <div style="text-align: center; color: #4CAF50; font-weight: bold; font-size: 1.3rem; margin: 20px 0;">
                        « Formation à l'Initiative Personnelle »
                    </div>

                    <!-- Dates et lieu -->
                    <div class="certificate-text" style="text-align: center; margin: 20px 0;">
                        du [<?php echo date('d/m/Y', strtotime($demoData['Date_interview'])); ?>] au [<?php echo date('d/m/Y', strtotime($demoData['Date_interview'] . ' +3 months')); ?>]<br>
                        à [Goma]
                    </div>

                    <!-- Signatures -->
                    <div style="display: flex; justify-content: space-between; margin-top: 40px; font-size: 0.9rem;">
                        <div style="text-align: center;">
                            [nom formateur-trice]
                        </div>
                        <div style="text-align: center;">
                            M Alexis Mangala Coordonnateur<br>
                            National TRANSFORME
                        </div>
                    </div>

                    <!-- Date de certification -->
                    <div style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
                        [date certification]
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="text-center mb-4">
                    <h3>Générer ce certificat</h3>
                    <div class="btn-group" role="group">
                        <a href="generate_certificate_simple.php?id=<?php echo urlencode($demoData['_id']); ?>" 
                           class="btn btn-success btn-lg">
                            <i class="fas fa-image me-2"></i>
                            Télécharger en Image (PNG)
                        </a>
                        <a href="generate_certificate.php?id=<?php echo urlencode($demoData['_id']); ?>" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-file-pdf me-2"></i>
                            Télécharger en PDF
                        </a>
                    </div>
                </div>
                
                <!-- Informations techniques -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-cog me-2"></i>
                                    Informations techniques
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><strong>Format PDF :</strong> Haute qualité, vectoriel</li>
                                    <li><strong>Format Image :</strong> PNG, compatible partout</li>
                                    <li><strong>Dimensions :</strong> A4 paysage (297x210mm)</li>
                                    <li><strong>Résolution :</strong> 300 DPI</li>
                                    <li><strong>Logos :</strong> Intégrés automatiquement</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-database me-2"></i>
                                    Données utilisées
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><strong>Nom :</strong> <?php echo htmlspecialchars($demoData['A_1_Nom_de_l_entrepreneur']); ?></li>
                                    <li><strong>Prénom :</strong> <?php echo htmlspecialchars($demoData['A_3_Prenom']); ?></li>
                                    <li><strong>Entreprise :</strong> <?php echo htmlspecialchars($demoData['B_1_Nom_de_l_entreprise']); ?></li>
                                    <li><strong>Date :</strong> <?php echo htmlspecialchars($demoData['Date_interview']); ?></li>
                                    <li><strong>ID :</strong> <?php echo htmlspecialchars($demoData['_id']); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Liens utiles -->
                <div class="text-center mt-4">
                    <div class="btn-group" role="group">
                        <a href="test_certificate.php" class="btn btn-outline-info">
                            <i class="fas fa-vial me-1"></i>
                            Tester avec vraies données
                        </a>
                        <a href="check_files.php" class="btn btn-outline-warning">
                            <i class="fas fa-search me-1"></i>
                            Vérifier les fichiers
                        </a>
                        <a href="install_certificate_generator.php" class="btn btn-outline-secondary">
                            <i class="fas fa-tools me-1"></i>
                            Diagnostic complet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
