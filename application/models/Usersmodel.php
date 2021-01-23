<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Dhyey Rathod
 */
class Usersmodel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function authentication($loginPayload)
	{
		$this->db->select('*');
		$this->db->from('users_table');
		$this->db->where('email_id',$loginPayload['email']);
		return $this->db->get()->row();
	}
}