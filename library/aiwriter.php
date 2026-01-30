<?php
class AI_Writer
{
    var $mysqli;
    var $dbpref;
    var $load;
    var $base_dir;
    var $media_dir;
    var $media_dir_url;
    var $table;
    function __construct($arr)
    {
        $this->mysqli = $arr['mysqli'];
        $this->dbpref = $arr['dbpref'];
        $this->load = $arr['load'];

        $this->table = $this->dbpref . 'media';

        if (isset($arr['base_dir'])) {
            $this->base_dir = $arr['base_dir'];
        }

        $media_folder = '/assets/media';
        $this->media_dir =  $this->base_dir . $media_folder;

        $this->media_dir_url = get_option('install_url') . $media_folder;
        if (!cf_dir_exists($this->media_dir)) {
            mkdir($this->media_dir);
        }
    }
    function aiAPIResponse($prompt_option, $detailed_explanation)
    {
        $valid_user_data = cf_enc(get_option('valid_user_data'), 'decrypt');
        $valid_user_data = json_decode($valid_user_data, true);

        $data = array(
            "prompt_option" => $prompt_option,
            "detailed_explanation" => $detailed_explanation,
            'custdomain' => get_option('install_url'),
            'custemail' => $valid_user_data['custemail']
        );
        $apiurl = "https://162.0.238.76/membership/api/ai_writer";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Host: cloudfunnels.in"
        ));
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}
