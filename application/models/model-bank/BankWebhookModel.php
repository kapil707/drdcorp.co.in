<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankWebhookModel extends CI_Model {
    public function insert_message($data) {
        return $this->db->insert('webhook_messages', $data);
    }
}