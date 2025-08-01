<?php
/**
 * Configuration KoboCollect
 * Modifiez ces paramètres selon votre serveur KoboCollect
 */

return [
    // URL de votre serveur KoboCollect
    'server_url' => 'https://kf.kobotoolbox.org/',
    
    // Token d'authentification (si nécessaire)
    'auth_token' => 'e50513b79d828ec86d98d664ac81c2d1f146ff30',
    
    // Nom d'utilisateur (si nécessaire)
    'username' => 'ke_transform',
    
    // Mot de passe (si nécessaire)
    'password' => 'KE@FOPE2024@_Goma',
    
    // ID du formulaire/projet KoboCollect
    'form_id' => 'amhG9dnsso7rZdja3Ncfsg',
    
    // Paramètres de l'API
    'api_settings' => [
        'timeout' => 30,
        'verify_ssl' => true,
        'user_agent' => 'KoboCollect-PHP-Client/1.0'
    ],
    
    // Configuration du cache (en secondes)
    'cache_duration' => 300, // 5 minutes
    
    // Limite de données par requête
    'limit_per_request' => 1000,
    
    // Champs à exclure de l'affichage (champs techniques)
    'excluded_fields' => [
        '_uuid',
        '_submission_time',
        '_tags',
        '_notes',
        '_status',
        '_geolocation',
        '_validation_status',
        '_submitted_by',
        '_xform_id_string',
        '__version__',
        'meta/instanceID',
        'meta/rootUuid',
        'formhub/uuid',
        'start',
        'end',
        'Coordonn_es_g_ographiques'
    ],
    
    // Champs à inclure dans la recherche (champs principaux)
    'searchable_fields' => [
        'A_1_Nom_de_l_entrepreneur',
        'A_2_Post_nom_de_l_entrepreneur', 
        'A_3_Prenom',
        'B_1_Nom_de_l_entreprise',
        'Activit',
        'C_1_D_crivez_bri_vement_votre_activit',
        'ville',
        'commune',
        'Quartiers',
        'Avenue',
        'identifiant'
    ],
    
    // Champs à afficher avec des noms personnalisés
    'field_display_names' => [
        '_id' => 'ID',
        'A_1_Nom_de_l_entrepreneur' => 'Nom',
        'A_2_Post_nom_de_l_entrepreneur' => 'Post-nom',
        'A_3_Prenom' => 'Prénom',
        'A_4_Genre' => 'Genre',
        'Age_entrepreneur' => 'Âge',
        'A_6_Etat_Civil' => 'État civil',
        'A_7_Niveau_etude' => 'Niveau d\'étude',
        'A_9_1_Num_phone_1' => 'Téléphone',
        'A_10_Adresse_mail_de_l_entrepreneur' => 'Email',
        'B_1_Nom_de_l_entreprise' => 'Nom entreprise',
        'age_entreprises' => 'Âge entreprise',
        'secteur_activite' => 'Secteur',
        'Activit' => 'Activité',
        'situation_actuelle' => 'Situation',
        'Niveau_formalisation' => 'Formalisation',
        'C_1_D_crivez_bri_vement_votre_activit' => 'Description',
        'Date_interview' => 'Date interview',
        'identifiant' => 'Identifiant'
    ]
]; 