<?php
/**
 * Exemple de configuration KoboCollect
 * Copiez ce fichier vers kobocollect.php et modifiez les valeurs
 */

return [
    // URL de votre serveur KoboCollect
    // Exemple: 'https://kf.kobotoolbox.org' ou 'https://your-server.com'
    'server_url' => 'https://your-kobocollect-server.com',
    
    // Token d'authentification (recommandé)
    // Vous pouvez obtenir ce token depuis votre interface KoboCollect
    'auth_token' => 'your-auth-token-here',
    
    // Nom d'utilisateur (alternative au token)
    'username' => 'your-username',
    
    // Mot de passe (alternative au token)
    'password' => 'your-password',
    
    // ID du formulaire/projet KoboCollect
    // Vous pouvez trouver cet ID dans l'URL de votre formulaire
    // Exemple: si l'URL est https://kf.kobotoolbox.org/#/forms/aBcDeFgHiJkLmNoPqRsT
    // alors form_id = 'aBcDeFgHiJkLmNoPqRsT'
    'form_id' => 'your-form-id',
    
    // Paramètres de l'API
    'api_settings' => [
        'timeout' => 30,           // Timeout en secondes
        'verify_ssl' => true,      // Vérifier les certificats SSL
        'user_agent' => 'KoboCollect-PHP-Client/1.0'
    ],
    
    // Configuration du cache (en secondes)
    'cache_duration' => 300, // 5 minutes
    
    // Limite de données par requête
    'limit_per_request' => 1000,
    
    // Champs à exclure de l'affichage (optionnel)
    // Ces champs ne seront pas affichés dans le tableau
    'excluded_fields' => [
        '_id',
        '_uuid',
        '_submission_time',
        '_tags',
        '_notes',
        '_validation_status',
        '_submitted_by'
    ],
    
    // Champs à inclure dans la recherche
    // La recherche se fera dans ces champs uniquement
    'searchable_fields' => [
        'name',
        'description',
        'location',
        'notes',
        'comment',
        'address'
    ]
]; 