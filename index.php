<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données KoboCollect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-database me-2"></i>
                KoboCollect Data Viewer
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Données KoboCollect
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="loading" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement des données...</p>
                        </div>
                        
                        <div id="data-container" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="searchInput" placeholder="Rechercher dans les données...">
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button class="btn btn-outline-primary" onclick="refreshData()">
                                        <i class="fas fa-sync-alt me-1"></i>
                                        Actualiser
                                    </button>
                                    <button class="btn btn-outline-success ms-2" onclick="exportData()">
                                        <i class="fas fa-download me-1"></i>
                                        Exporter
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm" id="dataTable">
                                    <thead class="table-dark">
                                        <tr id="tableHeader">
                                            <!-- Les en-têtes seront générés dynamiquement -->
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        <!-- Les données seront générées dynamiquement -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Modal pour les détails -->
                            <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailsModalLabel">
                                                <i class="fas fa-user me-2"></i>
                                                Détails de l'entrepreneur
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="detailsModalBody">
                                            <!-- Le contenu sera généré dynamiquement -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            <button type="button" class="btn btn-primary" onclick="exportSingleRecord()">
                                                <i class="fas fa-download me-1"></i>
                                                Exporter cet enregistrement
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span id="totalRecords" class="text-muted"></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label class="me-2">Affichage:</label>
                                    <select class="form-select form-select-sm me-3" id="pageSize" style="width: auto;">
                                        <option value="10">10 par page</option>
                                        <option value="25">25 par page</option>
                                        <option value="50">50 par page</option>
                                        <option value="100">100 par page</option>
                                    </select>
                                    <div id="pagination" class="d-flex align-items-center">
                                        <!-- Pagination sera générée dynamiquement -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="error-container" style="display: none;" class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="error-message"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html> 