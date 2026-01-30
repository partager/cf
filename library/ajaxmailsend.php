<?php
function sendSequencedMail($info, $data_arr)
{
	if (isset($data_arr['rwqmlr'])) {
		$mysqli = $info['mysqli'];
		$dbpref = $info['dbpref'];
		$attachment='';

		foreach ($data_arr as $data_arr_index => $data_arr_val) {
			$data_arr[$data_arr_index] = $mysqli->real_escape_string($data_arr_val);
		}

		$stoken = $data_arr['rtoken'];
		$object = $info['load']->loadSequence();
		$sequence_api_ob = $info['load']->loadScheduler();

		// Combine SELECT and UPDATE into a single query
		$query = $mysqli->query("SELECT * FROM `{$dbpref}quick_subscription_mail_schedule` WHERE stoken='{$stoken}'");
		while ($row = $query->fetch_assoc()) {
			$sendmailstat = 0;

			$email_subjct = explode("@clickbrk@", $row['sentdata']);
			$email_body = $email_subjct[1];

			$email_body .= $object->brbanding();

			$mailstat = $object->sendMail($row['smtpid'], '', $row['extraemails'], $email_subjct[0], $email_body,$attachment, $email_subjct[2]);

			if ($mailstat == 1) {
				$sendmailstat = 1;
			}

			// Update status and time
			$mysqli->query("UPDATE `{$dbpref}quick_subscription_mail_schedule` SET status='{$sendmailstat}', `time`='" . time() . "' WHERE id=" . $row['id']);

			$sequence_api_ob->remScheduledMail($data_arr['rtoken'], $data_arr['rurl']);
		}
	}
}
