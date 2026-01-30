<?php
class ComposeMail
{
	var $mysqli;
	var $dbpref;
	var $load;
	var $ip;
	var $sequence_ob;
	function __construct($arr)
	{
		$this->mysqli = $arr['mysqli'];
		$this->dbpref = $arr['dbpref'];
		$this->load = $arr['load'];
		$this->ip = $arr['ip'];
		$this->sequence_ob = $this->load->loadSequence();
	}
	function init($title, $smtps, $lists, $custom_emails, $sentdata, $extra_setup, $formData)
	{
		$smtps = array_values(array_unique(array_filter(explode(",", $smtps), function ($smtp) {
			return is_numeric($smtp) || trim($smtp) === 'php';
		})));

		if (count($smtps) < 1) {
			return false;
		}

		// Extract unique lists and process their data
		$lists = array_values(array_unique(explode(",", $lists)));
		$list_data = [];
		$list_ob = $this->load->createlist();
		$smtp_pointer = 0;
		foreach ($lists as $list) {
			if (is_numeric($list)) {
				$get_list_data = $list_ob->getLeadsFromLists($list);
				if ($get_list_data) {
					while ($data = $get_list_data->fetch_object()) {
						$smtp_pointer = isset($smtps[$smtp_pointer]) ? $smtp_pointer : 0;
						array_push($list_data, base64_encode(json_encode([
							$data->name,
							$data->email,
							$data->exf,
							$smtps[$smtp_pointer],
							$data->listid
						])));
						$smtp_pointer++;
					}
				}
			}
		}
		// Extract unique custom emails and process them
		$custom_emails = array_values(array_unique(array_filter(explode(',', $custom_emails), 'filter_var', FILTER_VALIDATE_EMAIL)));
		foreach ($custom_emails as $custom_email) {
			$smtp_pointer = isset($smtps[$smtp_pointer]) ? $smtp_pointer : 0;
			array_push($list_data, base64_encode(json_encode([
				"",
				$custom_email,
				"{}",
				$smtps[$smtp_pointer],
				0
			])));
			$smtp_pointer++;
		}

		if (count($list_data)) {

			$in = $this->sequence_ob->createSequenceForCompose($title, "@" . implode('@', $lists) . "@", implode('@', $smtps), $sentdata, $extra_setup, $formData);

			if ($in) {
				return ["data" => $list_data, "token" => base64_encode($in)];
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}



	function compose($compose_data, $token)
	{
		$compose_datas = (array)json_decode($compose_data);
		$sent_count = 0;
		foreach ($compose_datas as $data) {
			$data = (array)json_decode(base64_decode($data));
			$exf = (array)$data[2];
			foreach ($exf as $index => $exf_data) {
				if (is_object($exf_data)) {
					$exf[$index] = (array)$exf_data;
				}
			}

			if ($this->sequence_ob->composeOrScheduleSubscriptionMail($data[4], $data[1], $data[0], $exf, base64_decode($token), array('smtp' => $data[3]))) {
				++$sent_count;
			}
		}
		return $sent_count;
	}
	function getCompose($id)
	{
		$mysqli = $this->mysqli;
		$pref = $this->dbpref;

		$table = $pref . "quick_sequence";
		$id = $mysqli->real_escape_string($id);

		$qry = $mysqli->query("SELECT * FROM `" . $table . "` WHERE `id`=" . $id);

		if ($qry->num_rows > 0 && $r = $qry->fetch_object()) {
			return $r;
		} else {
			return 0;
		}
	}
}
