<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankWebhookModel extends CI_Model {
    public function insert_message($data) {
        $db_bank = $this->load->database('bank_db', TRUE);
        return $db_bank->insert('webhook_messages', $data);
    }

    function update_message($dt,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->update('webhook_messages',$dt,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}