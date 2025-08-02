<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide - Génération de Certificats en Lot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-certificate me-2"></i>
                Aide - Certificats en Lot
            </a>
            <div class="navbar-nav ms-auto">
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
                    <i class="fas fa-question-circle me-2"></i>
                    Guide d'utilisation - Génération de Certificats en Lot
                </h1>
                
                <!-- Introduction -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Qu'est-ce que la génération en lot ?
                        </h5>
                    </div>
                    <div class="card-body">
                        <p>La génération de certificats en lot vous permet de créer <strong>tous les certificats de formation</strong> en une seule fois, dans un seul fichier PDF. Chaque participant aura sa propre page avec son nom, sa photo et ses informations.</p>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Avantage :</strong> Gain de temps considérable pour traiter de nombreux participants
                        </div>
                    </div>
                </div>

                <!-- Comment utiliser -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-play-circle me-2"></i>
                            Comment utiliser cette fonctionnalité
                        </h5>
                    </div>
                    <div class="card-body">
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item">
                                <strong>Accédez à la liste principale</strong><br>
                                <small class="text-muted">Allez sur la page d'accueil avec la liste des participants</small>
                            </li>
                            <li class="list-group-item">
                                <strong>Cliquez sur le bouton "Certificats"</strong><br>
                                <small class="text-muted">Vous le trouverez en haut à droite, à côté des boutons "Actualiser" et "Exporter"</small>
                            </li>
                            <li class="list-group-item">
                                <strong>Choisissez une option :</strong>
                                <ul class="mt-2">
                                    <li><strong>"Tous les certificats PDF"</strong> - Génère tous les certificats de tous les participants</li>
                                    <li><strong>"Certificats filtrés"</strong> - Génère seulement les certificats des participants affichés (après recherche)</li>
                                </ul>
                            </li>
                            <li class="list-group-item">
                                <strong>Confirmez la génération</strong><br>
                                <small class="text-muted">Une boîte de dialogue vous demandera confirmation</small>
                            </li>
                            <li class="list-group-item">
                                <strong>Attendez le téléchargement</strong><br>
                                <small class="text-muted">Le fichier PDF se téléchargera automatiquement</small>
                            </li>
                        </ol>
                    </div>
                </div>

                <!-- Options disponibles -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>
                            Options disponibles
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Tous les certificats PDF</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Utilisation :</strong> Génération complète</p>
                                        <p><strong>Contenu :</strong> Tous les participants de la base de données</p>
                                        <p><strong>Temps :</strong> Peut prendre plusieurs minutes selon le nombre</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">Certificats filtrés</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Utilisation :</strong> Génération sélective</p>
                                        <p><strong>Contenu :</strong> Seulement les participants affichés après recherche</p>
                                        <p><strong>Temps :</strong> Plus rapide, dépend du filtre</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conseils et bonnes pratiques -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            Conseils et bonnes pratiques
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-filter me-2"></i>Utilisation des filtres</h6>
                            <p>Avant de générer les certificats filtrés, utilisez la barre de recherche pour affiner votre sélection :</p>
                            <ul>
                                <li>Recherchez par nom : "SAFARI"</li>
                                <li>Recherchez par entreprise : "SALON"</li>
                                <li>Recherchez par ville : "Goma"</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-success">
                            <h6><i class="fas fa-clock me-2"></i>Optimisation du temps</h6>
                            <ul class="mb-0">
                                <li>Pour de gros volumes (>100 participants), lancez la génération en fin de journée</li>
                                <li>Testez d'abord avec un petit nombre via les filtres</li>
                                <li>Gardez l'onglet ouvert pendant la génération</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Points d'attention</h6>
                            <ul class="mb-0">
                                <li>Les participants sans nom apparaîtront comme "NOM NON RENSEIGNÉ"</li>
                                <li>Les participants sans photo auront un placeholder</li>
                                <li>Le fichier final peut être volumineux (plusieurs MB)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Dépannage -->
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tools me-2"></i>
                            Dépannage
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6>Problèmes courants et solutions :</h6>
                        
                        <div class="accordion" id="troubleshootingAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#problem1">
                                        Le téléchargement ne démarre pas
                                    </button>
                                </h2>
                                <div id="problem1" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>Vérifiez que les pop-ups ne sont pas bloquées</li>
                                            <li>Essayez avec un nombre réduit de certificats</li>
                                            <li>Actualisez la page et réessayez</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#problem2">
                                        Erreur "Temps d'exécution dépassé"
                                    </button>
                                </h2>
                                <div id="problem2" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>Réduisez le nombre de certificats en utilisant les filtres</li>
                                            <li>Générez par petits lots (ex: 50 à la fois)</li>
                                            <li>Contactez l'administrateur pour augmenter les limites PHP</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#problem3">
                                        Les images ne s'affichent pas
                                    </button>
                                </h2>
                                <div id="problem3" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>C'est normal si les participants n'ont pas uploadé de photo</li>
                                            <li>Un placeholder "Photo non disponible" apparaîtra</li>
                                            <li>Vérifiez la connexion à KoboToolbox</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liens utiles -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-link me-2"></i>
                            Liens utiles
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="test_batch_certificates.php" class="btn btn-info w-100 mb-2">
                                    <i class="fas fa-vial me-2"></i>
                                    Tester la génération
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="demo_certificate.php" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-eye me-2"></i>
                                    Voir un exemple
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="index.php" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-list me-2"></i>
                                    Liste des participants
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
