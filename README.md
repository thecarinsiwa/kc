# KoboCollect Data Viewer

Une application web PHP moderne pour afficher et gérer les données de votre serveur KoboCollect.

## 🚀 Fonctionnalités

- **Interface moderne** avec Bootstrap 5 et Font Awesome
- **Recherche en temps réel** dans les données
- **Pagination** et filtrage des résultats
- **Affichage responsive** pour mobile et desktop
- **Gestion d'erreurs** robuste
- **Cache configurable** pour optimiser les performances

## 📋 Prérequis

- PHP 7.4 ou supérieur
- Extension cURL activée
- Serveur web (Apache, Nginx, etc.)
- Accès à votre serveur KoboCollect

## 🔧 Installation

1. **Cloner ou télécharger** ce projet dans votre répertoire web
2. **Configurer** les paramètres de connexion dans `config/kobocollect.php`
3. **Vérifier** les permissions des dossiers
4. **Accéder** à l'application via votre navigateur

## ⚙️ Configuration

### 1. Configuration KoboCollect

Modifiez le fichier `config/kobocollect.php` avec vos paramètres :

```php
return [
    // URL de votre serveur KoboCollect
    'server_url' => 'https://your-kobocollect-server.com',
    
    // Token d'authentification (si nécessaire)
    'auth_token' => 'your-auth-token-here',
    
    // ID du formulaire/projet KoboCollect
    'form_id' => 'your-form-id',
    
    // Autres paramètres...
];
```

### 2. Paramètres importants

- **server_url** : L'URL de votre serveur KoboCollect
- **auth_token** : Votre token d'authentification (si requis)
- **form_id** : L'ID du formulaire/projet que vous voulez afficher
- **excluded_fields** : Champs à exclure de l'affichage
- **searchable_fields** : Champs dans lesquels effectuer la recherche

### 3. Authentification

#### Avec Token (recommandé)
```php
'auth_token' => 'your-auth-token-here',
```

#### Avec nom d'utilisateur/mot de passe
```php
'username' => 'your-username',
'password' => 'your-password',
```

## 📁 Structure du projet

```
kc/
├── index.php                 # Page principale
├── config/
│   └── kobocollect.php      # Configuration KoboCollect
├── includes/
│   └── KoboCollectAPI.php   # Classe API KoboCollect
├── api/
│   └── get_data.php         # Endpoint API
├── assets/
│   ├── css/
│   │   └── style.css        # Styles personnalisés
│   └── js/
│       └── app.js           # JavaScript de l'application
└── README.md                # Ce fichier
```

## 🔍 Utilisation

1. **Accédez** à `index.php` dans votre navigateur
2. **Recherchez** dans vos données avec la barre de recherche
3. **Filtrez** les résultats avec le sélecteur de taille de page
4. **Actualisez** les données avec le bouton "Actualiser"

## 🛠️ Personnalisation

### Modifier l'apparence

Éditez `assets/css/style.css` pour personnaliser les styles.

### Ajouter des fonctionnalités

Modifiez `assets/js/app.js` pour ajouter de nouvelles fonctionnalités JavaScript.

### Changer la configuration

Modifiez `config/kobocollect.php` pour ajuster les paramètres de connexion.

## 🔒 Sécurité

- **Protégez** votre fichier de configuration
- **Utilisez HTTPS** en production
- **Limitez** l'accès aux fichiers sensibles
- **Validez** les entrées utilisateur

## 🐛 Dépannage

### Erreur de connexion
- Vérifiez l'URL du serveur KoboCollect
- Vérifiez vos credentials d'authentification
- Vérifiez que l'extension cURL est activée

### Données non affichées
- Vérifiez l'ID du formulaire
- Vérifiez les permissions d'accès
- Consultez les logs d'erreur PHP

### Problèmes d'affichage
- Vérifiez que Bootstrap et Font Awesome sont chargés
- Vérifiez la console JavaScript pour les erreurs
- Vérifiez les permissions des fichiers CSS/JS

## 📞 Support

Pour obtenir de l'aide :
1. Vérifiez la documentation de votre serveur KoboCollect
2. Consultez les logs d'erreur PHP
3. Vérifiez la console du navigateur pour les erreurs JavaScript

## 📄 Licence

Ce projet est fourni "tel quel" sans garantie. Utilisez-le à vos propres risques.

## 🔄 Mises à jour

Pour mettre à jour l'application :
1. Sauvegardez votre configuration
2. Remplacez les fichiers de l'application
3. Restaurez votre configuration
4. Testez l'application

---

**Note** : Assurez-vous que votre serveur KoboCollect est accessible et que vous avez les permissions nécessaires pour accéder aux données. # kc
