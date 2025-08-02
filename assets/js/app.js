/**
 * Application JavaScript pour l'affichage des données KoboCollect
 */

class KoboCollectViewer {
    constructor() {
        this.currentData = [];
        this.allData = []; // Stocker toutes les données pour la génération de certificats
        this.currentHeaders = [];
        this.currentPage = 1;
        this.pageSize = 10;
        this.searchTerm = '';

        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadData();
    }
    
    bindEvents() {
        // Recherche
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.searchTerm = e.target.value;
                this.debounce(() => this.loadData(), 500);
            });
        }
        
        // Taille de page
        const pageSizeSelect = document.getElementById('pageSize');
        if (pageSizeSelect) {
            pageSizeSelect.addEventListener('change', (e) => {
                this.pageSize = parseInt(e.target.value);
                this.currentPage = 1;
                this.loadData();
            });
        }
    }
    
    async loadData() {
        this.showLoading();
        
        try {
            const params = new URLSearchParams({
                limit: this.pageSize,
                offset: (this.currentPage - 1) * this.pageSize
            });
            
            if (this.searchTerm) {
                params.append('search', this.searchTerm);
            }
            
            const response = await fetch(`api/get_data.php?${params}`);
            const result = await response.json();
            
            if (result.success) {
                this.currentData = result.data;
                this.displayData();
                this.updateTotalRecords(result.total);
                this.generatePagination(result.total);
            } else {
                this.showError(result.error || 'Erreur lors du chargement des données');
            }
        } catch (error) {
            this.showError('Erreur de connexion au serveur');
            console.error('Erreur:', error);
        }

        // Charger toutes les données pour la génération de certificats (en arrière-plan)
        this.loadAllData();
    }

    async loadAllData() {
        try {
            // Charger toutes les données sans pagination pour les certificats
            const params = new URLSearchParams({
                limit: 10000, // Limite élevée pour récupérer toutes les données
                offset: 0
            });

            const response = await fetch(`api/get_data.php?${params}`);
            const result = await response.json();

            if (result.success) {
                this.allData = result.data;
                console.log(`Toutes les données chargées: ${this.allData.length} enregistrements`);
            } else {
                console.warn('Impossible de charger toutes les données pour les certificats');
            }
        } catch (error) {
            console.warn('Erreur lors du chargement de toutes les données:', error);
        }
    }
    
    displayData() {
        if (this.currentData.length === 0) {
            this.showNoData();
            return;
        }
        
        this.generateHeaders();
        this.generateTableRows();
        this.showDataContainer();
    }
    
    generateHeaders() {
        const tableHeader = document.getElementById('tableHeader');
        if (!tableHeader) return;
        
        // Générer les en-têtes à partir des données
        const firstRecord = this.currentData[0];
        this.currentHeaders = Object.keys(firstRecord);
        
        let headerHTML = '';
        this.currentHeaders.forEach(header => {
            const displayName = this.formatHeaderName(header);
            headerHTML += `<th>${displayName}</th>`;
        });
        // Ajouter l'en-tête pour la colonne Actions
        headerHTML += '<th class="text-center">Actions</th>';
        
        tableHeader.innerHTML = headerHTML;
    }
    
    generateTableRows() {
        const tableBody = document.getElementById('tableBody');
        if (!tableBody) return;
        
        let rowsHTML = '';
        
        this.currentData.forEach((record, index) => {
            rowsHTML += '<tr>';
            this.currentHeaders.forEach(header => {
                const value = record[header];
                const formattedValue = this.formatCellValue(value);
                rowsHTML += `<td>${formattedValue}</td>`;
            });
            // Ajouter les boutons d'action avec lien vers details.php
            const recordId = record['_id'];
            if (recordId) {
                rowsHTML += `
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="details.php?id=${recordId}" class="btn btn-sm btn-outline-info" title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-secondary" onclick="window.koboViewer.showDetails(${index})" title="Voir les détails (modal)">
                                <i class="fas fa-expand"></i>
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" title="Générer certificat">
                                    <i class="fas fa-certificate"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="generate_certificate.php?id=${recordId}">
                                        <i class="fas fa-file-pdf me-2"></i>Certificat PDF
                                    </a></li>
                                    <li><a class="dropdown-item" href="generate_certificate_simple.php?id=${recordId}">
                                        <i class="fas fa-image me-2"></i>Certificat Image
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                `;
            } else {
                rowsHTML += `
                    <td class="text-center">
                        <span class="text-muted">ID manquant</span>
                        <button class="btn btn-sm btn-outline-secondary ms-1" onclick="window.koboViewer.showDetails(${index})" title="Voir les détails (modal)">
                            <i class="fas fa-expand"></i>
                        </button>
                    </td>
                `;
            }
            rowsHTML += '</tr>';
        });
        
        tableBody.innerHTML = rowsHTML;
    }
    
    formatHeaderName(header) {
        // Utiliser les noms d'affichage personnalisés si disponibles
        const displayNames = {
            'A_1_Nom_de_l_entrepreneur': 'Nom',
            'A_2_Post_nom_de_l_entrepreneur': 'Post-nom',
            'A_3_Prenom': 'Prénom',
            'A_4_Genre': 'Genre',
            'Age_entrepreneur': 'Âge',
            'A_6_Etat_Civil': 'État civil',
            'A_7_Niveau_etude': 'Niveau d\'étude',
            'A_9_1_Num_phone_1': 'Téléphone',
            'A_10_Adresse_mail_de_l_entrepreneur': 'Email',
            'B_1_Nom_de_l_entreprise': 'Nom entreprise',
            'age_entreprises': 'Âge entreprise',
            'secteur_activite': 'Secteur',
            'Activit': 'Activité',
            'situation_actuelle': 'Situation',
            'Niveau_formalisation': 'Formalisation',
            'C_1_D_crivez_bri_vement_votre_activit': 'Description',
            'Date_interview': 'Date interview',
            'identifiant': 'Identifiant',
            'ville': 'Ville',
            'commune': 'Commune',
            'Quartiers': 'Quartier',
            'Avenue': 'Avenue',
            'Num_menage': 'N° ménage',
            'Type_piece_identite': 'Type pièce',
            'num_piece_identite': 'N° pièce',
            'B_2_Depuis_quand_votre_activit_existe': 'Date création',
            'nbre_associe': 'Nbre associés',
            'B_3_appartenance_entreprise': 'Appartenance',
            'C_2_outils_utiliser': 'Outils utilisés',
            'Comment_allez_vous_justifier_l': 'Justification',
            'Preuve_1': 'Preuve 1',
            'Preuve_2': 'Preuve 2',
            'Preuve_3': 'Preuve 3'
        };
        
        return displayNames[header] || header
            .replace(/_/g, ' ')
            .replace(/\b\w/g, l => l.toUpperCase())
            .trim();
    }
    
    formatCellValue(value) {
        if (value === null || value === undefined || value === '') {
            return '<span class="text-muted">-</span>';
        }
        
        if (typeof value === 'boolean') {
            return value ? 
                '<span class="badge bg-success">Oui</span>' : 
                '<span class="badge bg-secondary">Non</span>';
        }
        
        if (typeof value === 'object') {
            return `<pre class="mb-0 small"><code>${JSON.stringify(value, null, 2)}</code></pre>`;
        }
        
        const stringValue = String(value);
        
        // Traiter les emails
        if (stringValue.includes('@') && stringValue.includes('.')) {
            return `<a href="mailto:${stringValue}" class="text-primary">${stringValue}</a>`;
        }
        
        // Traiter les téléphones
        if (stringValue.match(/^\d{9,}$/)) {
            return `<a href="tel:${stringValue}" class="text-primary">${stringValue}</a>`;
        }
        
        // Traiter les URLs
        if (stringValue.startsWith('http')) {
            return `<a href="${stringValue}" target="_blank" class="text-primary">${stringValue}</a>`;
        }
        
        // Traiter les textes longs
        if (stringValue.length > 100) {
            return `<span title="${this.escapeHtml(stringValue)}" class="text-truncate d-inline-block" style="max-width: 200px;">${this.escapeHtml(stringValue.substring(0, 100))}...</span>`;
        }
        
        // Traiter les textes moyens
        if (stringValue.length > 50) {
            return `<span class="text-wrap">${this.escapeHtml(stringValue)}</span>`;
        }
        
        return `<span>${this.escapeHtml(stringValue)}</span>`;
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    showLoading() {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('data-container').style.display = 'none';
        document.getElementById('error-container').style.display = 'none';
    }
    
    showDataContainer() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('data-container').style.display = 'block';
        document.getElementById('error-container').style.display = 'none';
    }
    
    showError(message) {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('data-container').style.display = 'none';
        document.getElementById('error-container').style.display = 'block';
        document.getElementById('error-message').textContent = message;
    }
    
    showNoData() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('data-container').style.display = 'block';
        document.getElementById('error-container').style.display = 'none';
        
        const tableBody = document.getElementById('tableBody');
        if (tableBody) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="${this.currentHeaders.length}" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <br>Aucune donnée trouvée
                    </td>
                </tr>
            `;
        }
    }
    
    updateTotalRecords(total) {
        const totalElement = document.getElementById('totalRecords');
        if (totalElement) {
            totalElement.textContent = `${total} enregistrement(s) au total`;
        }
    }
    
    generatePagination(totalRecords) {
        const paginationContainer = document.getElementById('pagination');
        if (!paginationContainer) return;
        
        const totalPages = Math.ceil(totalRecords / this.pageSize);
        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }
        
        let paginationHTML = '';
        
        // Bouton précédent
        if (this.currentPage > 1) {
            paginationHTML += `
                <button class="btn btn-outline-primary btn-sm me-1" onclick="window.koboViewer.goToPage(${this.currentPage - 1})">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;
        }
        
        // Pages
        const startPage = Math.max(1, this.currentPage - 2);
        const endPage = Math.min(totalPages, this.currentPage + 2);
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === this.currentPage) {
                paginationHTML += `<button class="btn btn-primary btn-sm me-1">${i}</button>`;
            } else {
                paginationHTML += `
                    <button class="btn btn-outline-primary btn-sm me-1" onclick="window.koboViewer.goToPage(${i})">
                        ${i}
                    </button>
                `;
            }
        }
        
        // Bouton suivant
        if (this.currentPage < totalPages) {
            paginationHTML += `
                <button class="btn btn-outline-primary btn-sm ms-1" onclick="window.koboViewer.goToPage(${this.currentPage + 1})">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;
        }
        
        paginationContainer.innerHTML = paginationHTML;
    }
    
    goToPage(page) {
        this.currentPage = page;
        this.loadData();
    }
    
    showDetails(index) {
        const record = this.currentData[index];
        if (!record) return;
        
        // Mettre à jour le titre de la modal
        const modalTitle = document.getElementById('detailsModalLabel');
        const nom = record['A_1_Nom_de_l_entrepreneur'] || '';
        const prenom = record['A_3_Prenom'] || '';
        const entreprise = record['B_1_Nom_de_l_entreprise'] || '';
        modalTitle.innerHTML = `<i class="fas fa-user me-2"></i>Détails de ${prenom} ${nom} - ${entreprise}`;
        
        // Générer le contenu de la modal
        const modalBody = document.getElementById('detailsModalBody');
        modalBody.innerHTML = this.generateDetailsContent(record);
        
        // Stocker l'index de l'enregistrement pour l'export
        this.currentDetailIndex = index;
        
        // Afficher la modal
        const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
        modal.show();
    }
    
    generateDetailsContent(record) {
        // Organiser les champs par sections
        const sections = {
            'Informations personnelles': [
                'A_1_Nom_de_l_entrepreneur',
                'A_2_Post_nom_de_l_entrepreneur',
                'A_3_Prenom',
                'A_4_Genre',
                'Age_entrepreneur',
                'A_6_Etat_Civil',
                'A_7_Niveau_etude',
                'A_8_Nombre_de_personnes_en_ch',
                'A_9_1_Num_phone_1',
                'A_9_2_Numero_phone_2',
                'A_9_3_Num_phone_3',
                'A_10_Adresse_mail_de_l_entrepreneur'
            ],
            'Informations de localisation': [
                'ville',
                'commune',
                'Quartiers',
                'Avenue',
                'Num_menage',
                'communes',
                'Quartier',
                'Avenue_001',
                'R_ference'
            ],
            'Informations de l\'entreprise': [
                'B_1_Nom_de_l_entreprise',
                'age_entreprises',
                'B_2_Depuis_quand_votre_activit_existe',
                'nbre_associe',
                'B_3_appartenance_entreprise',
                'secteur_activite',
                'Activit',
                'situation_actuelle',
                'Niveau_formalisation'
            ],
            'Description et outils': [
                'C_1_D_crivez_bri_vement_votre_activit',
                'C_2_outils_utiliser',
                'Comment_allez_vous_justifier_l',
                'Preuve_1',
                'Preuve_2',
                'Preuve_3'
            ],
            'Informations d\'identification': [
                'Type_piece_identite',
                'num_piece_identite',
                'identifiant',
                'Date_interview',
                'Mode_enregistrement'
            ]
        };
        
        let content = '';
        
        // Section spéciale pour l'image de la pièce d'identité
        const imagePieceIdentite = record['Image_piece_identite'];
        const attachments = record['_attachments'];
        
        if (imagePieceIdentite && attachments && attachments.length > 0) {
            // Trouver l'attachment correspondant à l'image de la pièce d'identité
            const imageAttachment = attachments.find(att => 
                att.question_xpath === 'Image_piece_identite' || 
                att.media_file_basename === imagePieceIdentite
            );
            
            if (imageAttachment) {
                // Utiliser directement les URLs des attachments en supprimant ?format=json
                const smallUrl = imageAttachment.download_small_url ? imageAttachment.download_small_url.replace('?format=json', '') : '';
                const mediumUrl = imageAttachment.download_medium_url ? imageAttachment.download_medium_url.replace('?format=json', '') : '';
                const largeUrl = imageAttachment.download_large_url ? imageAttachment.download_large_url.replace('?format=json', '') : '';
                
                content += `
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Image de la pièce d'identité</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Vue réduite</h6>
                                    <img src="${smallUrl}" 
                                         class="img-fluid rounded border" 
                                         style="max-height: 200px;"
                                         alt="Pièce d'identité - Vue réduite"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div class="alert alert-warning" style="display: none;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Image non accessible
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Vue moyenne</h6>
                                    <img src="${mediumUrl}" 
                                         class="img-fluid rounded border" 
                                         style="max-height: 200px;"
                                         alt="Pièce d'identité - Vue moyenne"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div class="alert alert-warning" style="display: none;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Image non accessible
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="${largeUrl}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Voir l'image en grand format
                                </a>
                                <button class="btn btn-outline-secondary btn-sm ms-2" 
                                        onclick="downloadImage('${largeUrl}', '${imageAttachment.media_file_basename}')">
                                    <i class="fas fa-download me-1"></i>
                                    Télécharger l'image
                                </button>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <strong>Nom du fichier:</strong> ${imageAttachment.media_file_basename}<br>
                                    <strong>Type:</strong> ${imageAttachment.mimetype}
                                </small>
                            </div>
                        </div>
                    </div>
                `;
            }
        }
        
        Object.entries(sections).forEach(([sectionTitle, fields]) => {
            const sectionFields = fields.filter(field => record[field] !== undefined && record[field] !== null && record[field] !== '');
            
            if (sectionFields.length > 0) {
                content += `
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-folder me-2"></i>${sectionTitle}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                `;
                
                sectionFields.forEach(field => {
                    const value = record[field];
                    const displayName = this.formatHeaderName(field);
                    const formattedValue = this.formatDetailValue(field, value);
                    
                    content += `
                        <div class="col-md-6 mb-2">
                            <div class="d-flex">
                                <strong class="text-muted me-2" style="min-width: 150px;">${displayName}:</strong>
                                <span>${formattedValue}</span>
                            </div>
                        </div>
                    `;
                });
                
                content += `
                            </div>
                        </div>
                    </div>
                `;
            }
        });
        
        return content;
    }
    
    formatDetailValue(field, value) {
        if (value === null || value === undefined || value === '') {
            return '<span class="text-muted">Non renseigné</span>';
        }
        
        const stringValue = String(value);
        
        // Traiter les emails
        if (stringValue.includes('@') && stringValue.includes('.')) {
            return `<a href="mailto:${stringValue}" class="text-primary">${stringValue}</a>`;
        }
        
        // Traiter les téléphones
        if (stringValue.match(/^\d{9,}$/)) {
            return `<a href="tel:${stringValue}" class="text-primary">${stringValue}</a>`;
        }
        
        // Traiter les URLs
        if (stringValue.startsWith('http')) {
            return `<a href="${stringValue}" target="_blank" class="text-primary">${stringValue}</a>`;
        }
        
        // Traiter les textes longs
        if (stringValue.length > 200) {
            return `<div class="text-wrap">${this.escapeHtml(stringValue)}</div>`;
        }
        
        return `<span>${this.escapeHtml(stringValue)}</span>`;
    }
    
    debounce(func, wait) {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(func, wait);
    }
}

// Fonction globale pour actualiser les données
function refreshData() {
    if (window.koboViewer) {
        window.koboViewer.currentPage = 1;
        window.koboViewer.loadData();
    }
}

// Fonction globale pour exporter les données
function exportData() {
    if (window.koboViewer && window.koboViewer.currentData.length > 0) {
        // Créer le contenu CSV
        const headers = window.koboViewer.currentHeaders.map(header => 
            window.koboViewer.formatHeaderName(header)
        );
        
        const csvContent = [
            headers.join(','),
            ...window.koboViewer.currentData.map(record => 
                window.koboViewer.currentHeaders.map(header => {
                    const value = record[header];
                    // Échapper les virgules et guillemets pour CSV
                    const stringValue = String(value || '').replace(/"/g, '""');
                    return `"${stringValue}"`;
                }).join(',')
            )
        ].join('\n');
        
        // Créer et télécharger le fichier
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `kobocollect_data_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Fonction globale pour exporter un enregistrement individuel
function exportSingleRecord() {
    if (window.koboViewer && window.koboViewer.currentDetailIndex !== undefined) {
        const record = window.koboViewer.currentData[window.koboViewer.currentDetailIndex];
        if (!record) return;
        
        // Créer le contenu JSON
        const jsonContent = JSON.stringify(record, null, 2);
        
        // Créer et télécharger le fichier
        const blob = new Blob([jsonContent], { type: 'application/json;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        
        const nom = record['A_1_Nom_de_l_entrepreneur'] || 'unknown';
        const prenom = record['A_3_Prenom'] || 'unknown';
        const identifiant = record['identifiant'] || 'unknown';
        
        link.setAttribute('download', `entrepreneur_${prenom}_${nom}_${identifiant}.json`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Fonction globale pour télécharger une image
function downloadImage(imageUrl, filename) {
    fetch(imageUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors du téléchargement');
            }
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = filename || 'image.jpg';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du téléchargement de l\'image');
        });
}

// Fonction globale pour générer tous les certificats
function generateAllCertificates() {
    if (!window.koboViewer || !window.koboViewer.allData || window.koboViewer.allData.length === 0) {
        alert('Aucune donnée disponible pour générer les certificats');
        return;
    }

    const totalRecords = window.koboViewer.allData.length;
    const confirmMessage = `Voulez-vous générer ${totalRecords} certificats ?\n\nCela peut prendre quelques minutes selon le nombre de participants.`;

    if (confirm(confirmMessage)) {
        // Afficher un indicateur de chargement
        showCertificateProgress('Génération de tous les certificats en cours...', totalRecords);

        // Construire l'URL avec tous les paramètres
        const url = `generate_all_certificates.php?limit=${totalRecords}`;

        // Ouvrir dans une nouvelle fenêtre pour téléchargement
        window.open(url, '_blank');

        // Masquer l'indicateur après un délai
        setTimeout(() => {
            hideCertificateProgress();
        }, 3000);
    }
}

// Fonction globale pour générer les certificats filtrés
function generateFilteredCertificates() {
    if (!window.koboViewer || !window.koboViewer.currentData || window.koboViewer.currentData.length === 0) {
        alert('Aucune donnée filtrée disponible pour générer les certificats');
        return;
    }

    const totalRecords = window.koboViewer.currentData.length;
    const searchTerm = document.getElementById('searchInput').value;

    let confirmMessage = `Voulez-vous générer ${totalRecords} certificats`;
    if (searchTerm) {
        confirmMessage += ` pour la recherche "${searchTerm}"`;
    }
    confirmMessage += ' ?\n\nCela peut prendre quelques minutes selon le nombre de participants.';

    if (confirm(confirmMessage)) {
        // Afficher un indicateur de chargement
        showCertificateProgress('Génération des certificats filtrés en cours...', totalRecords);

        // Construire l'URL avec les paramètres de filtrage
        let url = `generate_all_certificates.php?limit=${totalRecords}`;
        if (searchTerm) {
            url += `&search=${encodeURIComponent(searchTerm)}`;
        }

        // Ouvrir dans une nouvelle fenêtre pour téléchargement
        window.open(url, '_blank');

        // Masquer l'indicateur après un délai
        setTimeout(() => {
            hideCertificateProgress();
        }, 3000);
    }
}

// Fonction pour afficher l'indicateur de progression
function showCertificateProgress(message, totalRecords) {
    // Créer ou mettre à jour l'indicateur de progression
    let progressDiv = document.getElementById('certificateProgress');
    if (!progressDiv) {
        progressDiv = document.createElement('div');
        progressDiv.id = 'certificateProgress';
        progressDiv.className = 'alert alert-info position-fixed';
        progressDiv.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        `;
        document.body.appendChild(progressDiv);
    }

    progressDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-3" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <div>
                <strong>Génération de certificats</strong><br>
                <small>${message}<br>
                ${totalRecords} participant(s) à traiter</small>
            </div>
        </div>
    `;

    progressDiv.style.display = 'block';
}

// Fonction pour masquer l'indicateur de progression
function hideCertificateProgress() {
    const progressDiv = document.getElementById('certificateProgress');
    if (progressDiv) {
        progressDiv.style.display = 'none';
    }
}

// Initialiser l'application quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    window.koboViewer = new KoboCollectViewer();
});