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

	public function update_webhook_reply_message(){
		
		$result = $this->BankModel->select_query("SELECT reply.body AS reply_body, wm.id as whatsapp_id FROM webhook_messages AS wm LEFT JOIN tbl_bank_processing AS bp ON bp.whatsapp_id = wm.id LEFT JOIN webhook_messages AS reply ON reply.reply_id = wm.message_id WHERE wm.reply_status=0 and reply.body!=''");
		$result = $result->result();
		foreach($result as $row) {
			if($row->reply_body){

				$whatsapp_id 	= $row->whatsapp_id;
				$reply_body 	= trim($row->reply_body);

				$where = array(
					'id' => $whatsapp_id,
				);
				$dt = array(
					'reply_body'=>$reply_body,
					'reply_status'=>1,
				);
				$this->BankModel->edit_fun("webhook_messages", $dt,$where);
			}
		}
	}
}