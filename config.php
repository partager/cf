<?php
// === CONFIGURATION BASE DE DONNÉES ===
define('DB_HOST', 'nskkskw4oks80wos4c4g40kg'); // Votre UUID MySQL
define('DB_USER', 'root');
define('DB_PASSWORD', 'ZS0KzCglM00xxM04wSRYOy18aoBjW8aLvP5vU7y5p0AyyPhcvFCB9hVHUCC3pOOk'); // REMPLACEZ PAR VOTRE MOT DE PASSE COMPLET
define('DB_NAME', 'default');
define('DB_PORT', 3306);

// Création de la connexion MySQLi
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

// Vérification
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Encodage
$mysqli->set_charset("utf8mb4");

// === CRUCIAL : COMPATIBILITÉ ===
// On rend la connexion accessible sous tous les noms possibles
// pour éviter l'erreur "on null" dans options.php
global $conn, $wpdb;
$conn = $mysqli;
$wpdb = $mysqli;

// === URL DU SITE ===
// Remplacez par votre vraie URL si nécessaire
if (!defined('SITE_URL')) {
    define('SITE_URL', 'https://g44k4skk80cs8wkssssgck84.livehashtag.xyz');
}

// === FONCTION MANQUANTE ===
// Empêche l'erreur "Call to undefined function doInitRoute"
if (!function_exists('doInitRoute')) {
    function doInitRoute() {
        return true;
    }
}
?>
