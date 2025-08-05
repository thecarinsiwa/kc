<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Démonstration DataTable - Design Amélioré</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-table me-2"></i>
                Démonstration DataTable
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
                    <i class="fas fa-palette me-2"></i>
                    Nouveau Design du DataTable
                </h1>
                
                <!-- Statistiques en temps réel -->
                <div class="live-stats">
                    <div class="stat-card">
                        <div class="stat-number">247</div>
                        <div class="stat-label">Total Participants</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">25</div>
                        <div class="stat-label">Affichés</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">189</div>
                        <div class="stat-label">Avec Photos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Aujourd'hui</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">
                                    <i class="fas fa-table me-2"></i>
                                    Données KoboCollect
                                    <span class="badge badge-info ms-2">25</span>
                                </h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-sync-alt me-1"></i>
                                        Actualiser
                                    </button>
                                    <button class="btn btn-outline-success">
                                        <i class="fas fa-download me-1"></i>
                                        Exporter
                                    </button>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="fas fa-certificate me-1"></i>
                                            Certificats
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">
                                                <i class="fas fa-file-pdf me-2"></i>Tous les certificats PDF
                                            </a></li>
                                            <li><a class="dropdown-item" href="#">
                                                <i class="fas fa-filter me-2"></i>Certificats filtrés
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Barre de recherche -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Rechercher dans les données...">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tableau avec le nouveau design -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="sortable highlight" data-column="nom">Nom <i class="fas fa-sort ms-1"></i></th>
                                        <th class="sortable highlight" data-column="prenom">Prénom <i class="fas fa-sort ms-1"></i></th>
                                        <th class="sortable highlight" data-column="entreprise">Nom entreprise <i class="fas fa-sort ms-1"></i></th>
                                        <th class="sortable" data-column="ville">Ville <i class="fas fa-sort ms-1"></i></th>
                                        <th class="sortable" data-column="date">Date interview <i class="fas fa-sort ms-1"></i></th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="highlight"><strong class="text-primary">SAFARI</strong></td>
                                        <td class="highlight"><strong class="text-primary">Jean</strong></td>
                                        <td class="highlight"><span class="badge badge-info">SALON DE COIFFURE</span></td>
                                        <td><span class="badge badge-secondary">Goma</span></td>
                                        <td><span class="text-muted">15/12/2024</span></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-info" title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Voir en modal">
                                                    <i class="fas fa-expand"></i>
                                                </button>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" title="Générer certificat">
                                                        <i class="fas fa-certificate"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">
                                                            <i class="fas fa-file-pdf me-2"></i>Certificat PDF
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#">
                                                            <i class="fas fa-image me-2"></i>Certificat Image
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <span class="badge badge-success ms-1" title="Photo disponible"><i class="fas fa-camera"></i></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="highlight"><strong class="text-primary">MUKAMANA</strong></td>
                                        <td class="highlight"><strong class="text-primary">Marie</strong></td>
                                        <td class="highlight"><span class="badge badge-info">BOUTIQUE</span></td>
                                        <td><span class="badge badge-secondary">Bukavu</span></td>
                                        <td><span class="text-muted">14/12/2024</span></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-info" title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Voir en modal">
                                                    <i class="fas fa-expand"></i>
                                                </button>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" title="Générer certificat">
                                                        <i class="fas fa-certificate"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">
                                                            <i class="fas fa-file-pdf me-2"></i>Certificat PDF
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#">
                                                            <i class="fas fa-image me-2"></i>Certificat Image
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="highlight"><strong class="text-primary">KABILA</strong></td>
                                        <td class="highlight"><strong class="text-primary">Joseph</strong></td>
                                        <td class="highlight"><span class="badge badge-info">RESTAURANT</span></td>
                                        <td><span class="badge badge-secondary">Kinshasa</span></td>
                                        <td><span class="text-muted">13/12/2024</span></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-info" title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary" title="Voir en modal">
                                                    <i class="fas fa-expand"></i>
                                                </button>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" title="Générer certificat">
                                                        <i class="fas fa-certificate"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#">
                                                            <i class="fas fa-file-pdf me-2"></i>Certificat PDF
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#">
                                                            <i class="fas fa-image me-2"></i>Certificat Image
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <span class="badge badge-success ms-1" title="Photo disponible"><i class="fas fa-camera"></i></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="text-muted">Affichage de 1 à 25 sur 247 enregistrements</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <label class="me-2">Affichage:</label>
                                <select class="form-select form-select-sm me-3" style="width: auto;">
                                    <option value="10">10 par page</option>
                                    <option value="25" selected>25 par page</option>
                                    <option value="50">50 par page</option>
                                    <option value="100">100 par page</option>
                                </select>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-primary btn-sm me-1">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm me-1">1</button>
                                    <button class="btn btn-outline-primary btn-sm me-1">2</button>
                                    <button class="btn btn-outline-primary btn-sm me-1">3</button>
                                    <button class="btn btn-outline-primary btn-sm ms-1">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informations sur les améliorations -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2"></i>
                            Améliorations du Design
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-palette me-2"></i>Design Visuel</h6>
                                <ul>
                                    <li>Dégradés modernes et couleurs harmonieuses</li>
                                    <li>Effets de transparence et blur</li>
                                    <li>Animations fluides et transitions</li>
                                    <li>Police Inter pour une meilleure lisibilité</li>
                                    <li>Ombres et effets 3D subtils</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-cogs me-2"></i>Fonctionnalités</h6>
                                <ul>
                                    <li>Statistiques en temps réel animées</li>
                                    <li>Tri interactif sur les colonnes</li>
                                    <li>Mise en évidence des données importantes</li>
                                    <li>Badges colorés pour les catégories</li>
                                    <li>Boutons d'action groupés et intuitifs</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>
                                Voir le DataTable en action
                            </a>
                            <a href="test_certificate.php" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-vial me-2"></i>
                                Tester les certificats
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des statistiques au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const targetValue = parseInt(stat.textContent);
                stat.textContent = '0';
                
                let currentValue = 0;
                const increment = targetValue / 50;
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= targetValue) {
                        stat.textContent = targetValue;
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(currentValue);
                    }
                }, 30);
            });
        });
    </script>
</body>
</html>
