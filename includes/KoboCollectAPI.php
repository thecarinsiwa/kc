<?php
// KoboCollectAPI.php
/**
 * Classe pour interagir avec l'API KoboCollect
 */
class KoboCollectAPI {
    private $config;
    private $baseUrl;
    private $headers;
    
    public function __construct($config) {
        $this->config = $config;
        $this->baseUrl = rtrim($config['server_url'], '/');
        $this->headers = [
            'Content-Type: application/json',
            'User-Agent: ' . $config['api_settings']['user_agent']
        ];
    }
    
    /**
     * Récupère les données depuis KoboCollect
     */
    public function getData($limit = null, $offset = 0) {
        $limit = $limit ?: $this->config['limit_per_request'];
        
        // Essayer d'abord l'API v1 (plus simple)
        $url = $this->baseUrl . '/api/v1/data/' . $this->config['form_id'] . '?format=json';
        
        $response = $this->makeRequest($url);
        
        if ($response === false) {
            // Si l'API v1 échoue, essayer l'API v2
            $url = $this->baseUrl . '/api/v2/assets/' . $this->config['form_id'] . '/data/';
            $params = [
                'limit' => $limit,
                'start' => $offset,
                'format' => 'json' // Assurez-vous que le format est JSON
            ];
            $url .= '?' . http_build_query($params);
            
            $response = $this->makeRequest($url);
            
            if ($response === false) {
                return ['error' => 'Impossible de se connecter au serveur KoboCollect'];
            }
        }
        
        return $this->processResponse($response);
    }
    
    /**
     * Récupère les métadonnées du formulaire
     */
    public function getFormMetadata() {
        $url = $this->baseUrl . '/api/v2/assets/' . $this->config['form_id'] . '/';
        
        $response = $this->makeRequest($url);
        
        if ($response === false) {
            return ['error' => 'Impossible de récupérer les métadonnées du formulaire'];
        }
        
        return $this->processResponse($response);
    }
    
    /**
     * Effectue une requête HTTP
     */
    private function makeRequest($url) {
        $ch = curl_init();
        
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->config['api_settings']['timeout'],
            CURLOPT_SSL_VERIFYPEER => $this->config['api_settings']['verify_ssl'],
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5
        ];
        
        // Ajouter l'authentification
        if (!empty($this->config['auth_token'])) {
            // Authentification par token
            $options[CURLOPT_HTTPHEADER][] = 'Authorization: Token ' . $this->config['auth_token'];
        } elseif (!empty($this->config['username']) && !empty($this->config['password'])) {
            // Authentification par nom d'utilisateur/mot de passe
            $options[CURLOPT_USERPWD] = $this->config['username'] . ':' . $this->config['password'];
        }
        
        curl_setopt_array($ch, $options);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            error_log("Erreur cURL: " . $error);
            return false;
        }
        
        if ($httpCode !== 200) {
            error_log("Erreur HTTP: " . $httpCode . " - URL: " . $url);
            error_log("Réponse: " . substr($response, 0, 1000));
            return false;
        }
        
        return $response;
    }
    
    /**
     * Traite la réponse de l'API
     */
    private function processResponse($response) {
        // Vérifier si la réponse est vide
        if (empty($response)) {
            return ['error' => 'Réponse vide du serveur'];
        }
        // Essayer de décoder le JSON
        file_put_contents(__DIR__ . '/debug_response.txt', $response); // Ajoutez cette ligne
        $data = json_decode($response, true);
        

        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Erreur JSON: " . json_last_error_msg());
            error_log("Réponse brute: " . substr($response, 0, 1000));
            return ['error' => 'Réponse invalide du serveur: ' . json_last_error_msg()];
        }
        
        // Si c'est un tableau simple (API v1), le convertir au format v2
        if (is_array($data) && !isset($data['results'])) {
            $data = ['results' => $data];
        }
        
        return $data;
    }
    
    /**
     * Filtre les données selon la configuration
     */
    public function filterData($data) {
        if (isset($data['results'])) {
            $filteredResults = [];
            
            foreach ($data['results'] as $result) {
                $filteredResult = [];
                
                foreach ($result as $key => $value) {
                    // Exclure les champs techniques
                    if (!in_array($key, $this->config['excluded_fields'])) {
                        // Traiter les valeurs spéciales
                        $processedValue = $this->processFieldValue($key, $value);
                        $filteredResult[$key] = $processedValue;
                    }
                }
                
                $filteredResults[] = $filteredResult;
            }
            
            $data['results'] = $filteredResults;
        }
        
        return $data;
    }
    
    /**
     * Traite les valeurs des champs pour un meilleur affichage
     */
    private function processFieldValue($fieldName, $value) {
        // Traiter les valeurs de genre
        if ($fieldName === 'A_4_Genre') {
            return $value === '1' ? 'Homme' : ($value === '2' ? 'Femme' : $value);
        }
        
        // Traiter les valeurs d'état civil
        if ($fieldName === 'A_6_Etat_Civil') {
            $etats = [
                '1' => 'Marié(e)',
                '2' => 'Célibataire',
                '3' => 'Divorcé(e)',
                '4' => 'Veuf/Veuve'
            ];
            return $etats[$value] ?? $value;
        }
        
        // Traiter les valeurs de niveau d'étude
        if ($fieldName === 'A_7_Niveau_etude') {
            $niveaux = [
                '1' => 'Primaire',
                '2' => 'Secondaire',
                '3' => 'Universitaire',
                '4' => 'Supérieur'
            ];
            return $niveaux[$value] ?? $value;
        }
        
        // Traiter les valeurs de situation
        if ($fieldName === 'situation_actuelle') {
            return $value === '1' ? 'En activité' : 'Inactif';
        }
        
        // Traiter les valeurs de niveau de formalisation
        if ($fieldName === 'Niveau_formalisation') {
            $niveaux = [
                '1' => 'Informel',
                '2' => 'Semi-formel',
                '3' => 'Formel'
            ];
            return $niveaux[$value] ?? $value;
        }
        
        // Traiter les valeurs de secteur d'activité
        if ($fieldName === 'secteur_activite') {
            $secteurs = [
                '1' => 'Agriculture',
                '2' => 'Industrie',
                '3' => 'Services',
                '96' => 'Commerce'
            ];
            return $secteurs[$value] ?? $value;
        }
        
        // Traiter les valeurs de commune
        if ($fieldName === 'commune') {
            $communes = [
                '1' => 'Goma',
                '2' => 'Karisimbi',
                '2_1' => 'Karisimbi'
            ];
            return $communes[$value] ?? $value;
        }
        
        // Traiter les valeurs de ville
        if ($fieldName === 'ville') {
            return $value === 'GOM' ? 'Goma' : $value;
        }
        
        return $value;
    }
    
    /**
     * Recherche dans les données
     */
    public function searchData($data, $searchTerm) {
        if (empty($searchTerm) || !isset($data['results'])) {
            return $data;
        }
        
        $searchTerm = strtolower($searchTerm);
        $filteredResults = [];
        
        foreach ($data['results'] as $result) {
            $found = false;
            
            foreach ($this->config['searchable_fields'] as $field) {
                if (isset($result[$field])) {
                    $fieldValue = is_array($result[$field]) ? 
                        json_encode($result[$field]) : 
                        strval($result[$field]);
                    
                    if (stripos($fieldValue, $searchTerm) !== false) {
                        $found = true;
                        break;
                    }
                }
            }
            
            if ($found) {
                $filteredResults[] = $result;
            }
        }
        
        $data['results'] = $filteredResults;
        return $data;
    }
}

?>