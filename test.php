<?php
// Test simple pour vérifier que PHP fonctionne
echo "PHP fonctionne correctement !";
echo "<br>Version PHP : " . phpversion();
echo "<br>Extension cURL : " . (extension_loaded('curl') ? 'Activée' : 'Désactivée');
echo "<br>Répertoire courant : " . getcwd();
?> 