<?php
$app_variant = "cloudfunnels";
$current_app_version = '4.7.2';

$pro_upgrade_url = "https://yournextfunnel.in/cloudfunnels2pro";
$upgrade_url = "https://getcloudfunnels.in";

$document_root = __DIR__;

$document_root = rtrim(str_replace("\\", "/", $document_root), "/");
$document_root_arr = explode("/", $document_root);
array_pop($document_root_arr);
$document_root = implode("/", $document_root_arr);
$document_root = rtrim(str_replace("\\", "/", $document_root), "/");

$GLOBALS["config_file"] = $document_root . "/config.php";

if (!function_exists('cf_dir_exists')) {
    function cf_dir_exists($dir)
    {
        $dir = rtrim($dir, "/");
        if (is_dir($dir)) {
            return 1;
        } else {
            return 0;
        }
    }
}

if (!function_exists('cf_fwrite')) {
    function cf_fwrite($file, $content)
    {
        $stat = false;
        $fp = fopen($file, "w");
        if (fwrite($fp, $content)) {
            $stat = true;
        }
        fclose($fp);
        return $stat;
    }
}
