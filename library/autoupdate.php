<?php
class Autoupdate
{
    var $update_url;
    var $current_version;
    var $base_dir;
    var $mysqli;
    var $dbpref;

    function __construct($arr)
    {
        $this->mysqli = $arr['mysqli'];
        $this->dbpref = $arr['dbpref'];
        $this->update_url = "http://162.0.238.76/membership/api/auto_update";
        $this->current_version = get_option('qfnl_current_version');
        $this->base_dir = $arr['base_dir'];
    }
    function request($url, $arr = array(), $type = "post")
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($type == "post") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Host: cloudfunnels.in"
        ));
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    function checkForUpdate($download_url = 0, $version = 0)
    {
        if (!filter_var($download_url, FILTER_VALIDATE_URL)) {
            $arr = array('user_version' => $this->current_version, 'check_qfnl_update' => 1);
            $res = self::request($this->update_url, $arr);
            $res = json_decode($res);
            if (json_last_error() === 0) {
                if ($res->update) {
                    return json_encode(array('download_url' => $res->download_url, 'version' => $res->updated_version, 'changes' => $res->changes));
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } elseif (filter_var($download_url, FILTER_VALIDATE_URL)) {
            $zip = self::request($download_url, array(), 'get');
            $fp = fopen($this->base_dir . "/qfunnel_update.zip", "w");
            fwrite($fp, $zip);
            fclose($fp);
            return 1;
        }

        return 0;
    }
    function doUpdate($version)
    {
        $file = $this->base_dir . "/" . "qfunnel_update.zip";
        $zip = new ZipArchive();
        $zip->open($file);
        $zip->extractTo($this->base_dir);
        $zip->close();
        unlink($file);
        return 1;
    }
    function installDependencies($version = 0)
    {
        // Database resources
        $mysqli = $this->mysqli;
        $dbpref = $this->dbpref;

        if ($version > 0) {
            $updates = [
                //=> Please change to the currunt version of cloudfunnels
                // Update 466
                [
                    'option' => 'qfnl_has_update_466',
                    'queries' => [
                        "ALTER TABLE `" . $dbpref . "quick_sequence` ADD `attachment` TEXT NULL AFTER `sentdata`, ADD INDEX (`sequence_id`)"
                    ]
                ]
            ];

            foreach ($updates as $update) {
                if (!get_option($update['option'])) {
                    if (isset($update['queries'])) {
                        foreach ($update['queries'] as $query) {
                            $mysqli->query($query);
                        }
                    }

                    if (isset($update['value'])) {
                        add_option($update['option'], $update['value']);
                    }
                }
            }

            // Update current version if needed
            $strVar = str_replace(".", "", (string)$version);
            $currentVersion = get_option('qfnl_current_version');

            if ($version > $currentVersion) {
                update_option('qfnl_current_version', $version);
            }

            if (!get_option('qfnl_has_update_' . $strVar)) {
                update_option('qfnl_current_version', $version);
                update_option('qfnl_has_update_' . $strVar, '1');
            }
        }

        return 1;
    }
}
