<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Dhyey Rathod
 */
use chriskacerguis\RestServer\RestController;
use \Firebase\JWT\JWT;

class Api extends RestController
{	
	public $api_response ;public $token_api ;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Usersmodel','usersmodel');
		$this->api_response = array();$this->token_api = array();
	}
	public function userLogin_post()
	{
		$login_payload = array(
			'email' => $this->security->xss_clean($this->input->post('email')),
			'password' => $this->security->xss_clean($this->input->post('password')),
		);
		$loginResponse = $this->usersmodel->authentication($login_payload);
		if (!empty($loginResponse)) {
			$password_verify = password_verify($login_payload['password'],$loginResponse->password);
			if ($password_verify === TRUE) {
				$this->token_api['id'] = $loginResponse->user_id;
				$this->token_api['email_id'] = $loginResponse->email_id;
				$this->token_api['iat'] = strtotime(date("Y-m-d h:i:s"));
				$this->api_response['status_code'] = 200;
				$this->api_response['token'] = JWT::encode($this->token_api,$this->config->item('encryption_key'));
				$this->api_response['message'] = 'User has been login successfully';
			} else {
				$this->api_response['api_status'] = FALSE;
				$this->api_response['message'] = 'Password is incorrect 1';
				$this->api_response['status_code'] = 403;
			}
		} else {
			$this->api_response['api_status'] = FALSE;
			$this->api_response['message'] = 'User dose not exist.';
			$this->api_response['status_code'] = 403;
		}
        $this->response($this->api_response,$this->api_response['status_code']);
	}
}