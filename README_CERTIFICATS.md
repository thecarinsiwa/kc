# Générateur de Certificats KoboCollect

## 📋 Description

Ce module permet de générer automatiquement des certificats de formation pour les participants enregistrés dans KoboCollect. Il utilise les données des entrepreneurs pour créer des certificats personnalisés au format PDF ou image.

## 🎯 Fonctionnalités

- **Génération automatique** de certificats à partir des données KoboCollect
- **Deux formats disponibles** : PDF (avec TCPDF) et Image (avec GD)
- **Template personnalisé** utilisant votre modèle existant
- **Logos intégrés** : MOVE et Transforme
- **Données personnalisées** : nom, prénom, entreprise, date
- **Interface intégrée** : boutons dans la liste et les détails

## 📁 Fichiers ajoutés

### Scripts principaux
- `generate_certificate.php` - Générateur PDF (nécessite TCPDF)
- `generate_certificate_simple.php` - Générateur image (utilise GD)
- `test_certificate.php` - Script de test
- `install_certificate_generator.php` - Script d'installation

### Configuration
- `composer.json` - Dépendances pour TCPDF
- `README_CERTIFICATS.md` - Cette documentation

### Assets requis
- `assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg` - Template image ✅
- `assets/Modèle_Certificats Participant-e-s_FIP_complet.pdf` - Template PDF ✅
- `assets/logo-move.png` - Logo MOVE ✅
- `assets/logo-transforme.png` - Logo Transforme ✅
- `assets/fonts/` - Dossier pour les polices (optionnel)

## 🚀 Installation

### 1. Vérification automatique
```bash
# Ouvrez dans votre navigateur
http://votre-site/install_certificate_generator.php
```

### 2. Installation manuelle

#### Prérequis
- PHP >= 7.4
- Extension GD (pour les images)
- Extension cURL (déjà utilisée pour KoboCollect)

#### Installation de TCPDF (optionnel, pour PDF)
```bash
# Si Composer est installé
composer install

# Ou installation directe
composer require tecnickcom/tcpdf
```

#### Création des dossiers
```bash
mkdir assets/fonts
mkdir certificates
mkdir temp
```

## 🎨 Personnalisation

### Template de certificat
Le générateur utilise votre template existant :
- **Format image** : `assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg`
- **Format PDF** : `assets/Modèle_Certificats Participant-e-s_FIP_complet.pdf`

### Logos
- **Logo MOVE** : `assets/logo-move.png` (en haut à gauche)
- **Logo Transforme** : `assets/logo-transforme.png` (en haut à droite)

### Polices (optionnel)
Pour une meilleure qualité de texte, placez une police TTF dans :
```
assets/fonts/arial.ttf
```

## 📝 Utilisation

### Depuis la liste des participants
1. Cliquez sur le bouton certificat (📋) à côté d'un participant
2. Choisissez le format : PDF ou Image
3. Le certificat se télécharge automatiquement

### Depuis la page de détails
1. Ouvrez les détails d'un participant
2. Cliquez sur "Certificat PDF" ou "Certificat IMG"
3. Le fichier se télécharge avec le nom du participant

### Test de fonctionnement
```bash
# Ouvrez dans votre navigateur
http://votre-site/test_certificate.php
```

## 🔧 Configuration technique

### Données utilisées
Le certificat utilise ces champs de KoboCollect :
- `A_1_Nom_de_l_entrepreneur` - Nom de famille
- `A_3_Prenom` - Prénom
- `B_1_Nom_de_l_entreprise` - Nom de l'entreprise
- `Date_interview` - Date de formation

### Formats de sortie
- **PDF** : Haute qualité, vectoriel, petit fichier
- **Image PNG** : Compatible partout, plus gros fichier

### Noms de fichiers
Format automatique : `Certificat_Prenom_Nom_YYYY-MM-DD.pdf`

## 🛠️ Dépannage

### Erreur "TCPDF non trouvé"
```bash
composer require tecnickcom/tcpdf
```

### Erreur "Extension GD manquante"
Activez l'extension dans `php.ini` :
```ini
extension=gd
```

### Images/logos ne s'affichent pas
Vérifiez que les fichiers existent :
- `assets/logo-move.png`
- `assets/logo-transforme.png`
- `assets/Modèle_Certificats Participant-e-s_FIP_complet.jpg`

### Texte de mauvaise qualité
Ajoutez une police TTF dans `assets/fonts/arial.ttf`

### Erreur de mémoire
Augmentez la limite dans `php.ini` :
```ini
memory_limit = 256M
```

## 📊 Exemples de sortie

### Certificat généré contient :
- **En-tête** : "CERTIFICAT DE FORMATION"
- **Sous-titre** : "Formation en Initiatives Productives (FIP)"
- **Nom du participant** : En rouge, en majuscules
- **Texte de certification** : Texte standard
- **Entreprise** : Si renseignée
- **Date** : Date de formation
- **Logos** : MOVE et Transforme
- **Signature** : Espace pour signature

## 🔄 Intégration

### Boutons ajoutés
- **Liste principale** : Menu déroulant avec options PDF/Image
- **Page de détails** : Boutons directs PDF/Image

### Modifications apportées
- `details.php` : Ajout des boutons de génération
- `assets/js/app.js` : Ajout du menu déroulant dans la liste

## 📞 Support

Pour toute question ou problème :
1. Consultez `install_certificate_generator.php` pour les diagnostics
2. Testez avec `test_certificate.php`
3. Vérifiez les logs d'erreur PHP
4. Assurez-vous que tous les fichiers assets sont présents
