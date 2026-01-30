<?php
// === 1. IDENTIFIANTS BASE DE DONNÉES (Correspond au formulaire) ===
$db_host = 'nskkskw4oks80wos4c4g40kg'; // Host
$db_user = 'root';                     // Username
$db_password = 'ZS0KzCglM00xxM04wSRYOy18aoBjW8aLvP5vU7y5p0AyyPhcvFCB9hVHUCC3pOOk';        // Password (METTRE LE VRAI)
$db_name = 'default';                   // Database Name
$db_port = 3306;                        // Port

// === 2. PRÉFIXE DES TABLES (Manquait dans ma version précédente) ===
// D'après les standards CloudFunnels, c'est souvent "cf_" ou vide.
// Si tu ne sais pas, mets "cf_" par sécurité.
$table_prefix = 'cf_'; 

// === 3. CONSTANTES (Pour compatibilité interne) ===
define('DB_HOST', $db_host);
define('DB_USER', $db_user);
define('DB_PASSWORD', $db_password);
define('DB_NAME', $db_name);
// Certaines versions attendent DB_PORT en constante, d'autres non.
if(!defined('DB_PORT')) define('DB_PORT', $db_port);

// === 4. CRÉATION DE LA CONNEXION (Pour éviter l'erreur "on null") ===
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

// === 5. ASSIGNATION GLOBALE (C'est là que ça plantait dans options.php) ===
// L'application cherche probablement $mysqli ou $conn. On définit les deux.
global $conn, $wpdb; 
$conn = $mysqli;
$wpdb = $mysqli; // Parfois utilisé dans les applis inspirées de WP

// === 6. INITIALISATION ROUTE (Pour éviter l'erreur index.php) ===
if (!function_exists('doInitRoute')) {
    function doInitRoute() {
        return true;
    }
}

// === 7. URL DU SITE ===
if (!defined('SITE_URL')) {
    // L'URL de ton site Coolify
    define('SITE_URL', 'https://g44k4skk80cs8wkssssgck84.livehashtag.xyz');
}
?>
