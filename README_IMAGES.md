# Solution pour l'affichage des images KoboToolbox

## Problème résolu

Les images provenant de l'API KoboToolbox nécessitent une authentification pour être affichées. Quand on essaie d'afficher ces images directement dans des balises `<img>` ou de les ouvrir dans un navigateur, on obtient une erreur 403 (Forbidden) car le navigateur ne peut pas envoyer les headers d'authentification nécessaires.

## Solution implémentée

### 1. Proxy d'images (`image_proxy.php`)

Ce fichier agit comme un intermédiaire entre votre application et les serveurs KoboToolbox :
- Il reçoit l'URL de l'image en paramètre
- Il utilise l'authentification configurée pour télécharger l'image
- Il renvoie l'image au navigateur avec les headers appropriés
- Il inclut une mise en cache pour améliorer les performances

### 2. Script de téléchargement (`download_image.php`)

Ce fichier permet de télécharger les images avec un nom de fichier approprié :
- Il utilise la même authentification que le proxy
- Il force le téléchargement avec le bon nom de fichier
- Il nettoie le nom de fichier pour éviter les problèmes de sécurité

### 3. Méthode ajoutée à la classe API (`KoboCollectAPI::downloadImage()`)

Une nouvelle méthode a été ajoutée à la classe `KoboCollectAPI` pour télécharger les images avec authentification :
- Elle utilise cURL avec les mêmes paramètres d'authentification que les autres requêtes API
- Elle retourne les données de l'image, le type MIME et la taille
- Elle gère les erreurs de manière appropriée

### 4. Modifications dans `details.php`

Le fichier d'affichage des détails a été modifié pour :
- Utiliser le proxy pour toutes les images (petites, moyennes, grandes)
- Afficher toutes les images attachées, pas seulement la pièce d'identité
- Utiliser le script de téléchargement pour les liens de téléchargement
- Améliorer l'interface utilisateur avec de meilleures informations sur les fichiers

## Utilisation

### Affichage d'une image
```php
$imageUrl = "https://kc.kobotoolbox.org/media/original?media_file=...";
$proxyUrl = 'image_proxy.php?url=' . urlencode($imageUrl);
echo '<img src="' . $proxyUrl . '" alt="Image">';
```

### Téléchargement d'une image
```php
$imageUrl = "https://kc.kobotoolbox.org/media/original?media_file=...";
$filename = "mon_image.jpg";
$downloadUrl = 'download_image.php?url=' . urlencode($imageUrl) . '&filename=' . urlencode($filename);
echo '<a href="' . $downloadUrl . '" download>Télécharger</a>';
```

## Sécurité

- Les proxies vérifient que les URLs proviennent bien des domaines KoboToolbox autorisés
- Les noms de fichiers sont nettoyés pour éviter les injections
- L'authentification est gérée de manière sécurisée via la configuration existante

## Test

Utilisez le fichier `test_images.php` pour vérifier que la solution fonctionne correctement :
1. Ouvrez `test_images.php` dans votre navigateur
2. Vérifiez que les images s'affichent correctement via le proxy
3. Testez les liens de téléchargement

## Fichiers modifiés/ajoutés

- `includes/KoboCollectAPI.php` : Ajout de la méthode `downloadImage()`
- `details.php` : Modification pour utiliser les proxies
- `image_proxy.php` : Nouveau fichier proxy pour l'affichage
- `download_image.php` : Nouveau fichier pour le téléchargement
- `test_images.php` : Script de test
- `README_IMAGES.md` : Cette documentation

## Configuration requise

Aucune configuration supplémentaire n'est nécessaire. La solution utilise la configuration d'authentification existante dans `config/kobocollect.php`.
