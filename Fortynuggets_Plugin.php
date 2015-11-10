<?php

include_once('Fortynuggets_Plugin_menus.php');
include_once('Fortynuggets_LifeCycle.php');

class Fortynuggets_Plugin extends Fortynuggets_LifeCycle {
	
    public function getPluginDisplayName() {
        return '40Nuggets';
    }

    protected function getMainPluginFileName() {
        return 'fortynuggets.php';
    }

    public function activate() {		
		$options = $this->get_options();
		if (!empty($options->email)){
			$this->login($options->email, $options->password);
		}
	}

    public function deactivate() {
		$this->freeze_client(true);
    }

    public function addSettingsSubMenuPage() {
		$menu = new Fortynuggets_Plugin_menus();
		$menu->show();
    }

    public function addActionsAndFilters() {
		//Add settings sub manu page
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));
				
        // Add code only to public zone
		if (!is_admin()) {
			$options = $this->get_options();
			if (!empty($options->api_key)){
				wp_register_script('40nm-tracking', plugins_url('/js/track.js', __FILE__),false, $this->getVersionSaved());
				wp_localize_script('40nm-tracking', '_40nmcid', $options->api_key);
				wp_enqueue_script ('40nm-tracking');
			}
		}
    }
		
	public function shouldCreateAccount() {
		$options = $this->get_options();
		if (empty($options->email)){
			$shouldOpenAccount = true;
			try {
				$shouldOpenAccount = !file_exists(plugin_dir_path(__FILE__) . 'fortynuggets.key');
			}catch(Exception $e) {}
			
			if ($shouldOpenAccount) {
				return true;
			}
		}
		return false;
	}
	
	public function login($email, $password){
		$data = array(
			"email" => $email,
			"password" => $password,
		);
		$json["profile"] = $data;
		$data_string = json_encode($json);   
		
		$response = $this->apiCall("login", "POST", $data_string);

		if (isset($response->profile)){
			$akl = $response->profile->p_auto_login_key;
			$this->freeze_client(false);
			$response = $this->apiCall("clients/me");
			if (isset($response->client)){
				$data["id"] = $response->client->id;
				$data["api_key"] = $response->client->api_key;
				$data["akl"] = $akl;
				$this->save_options($data);
				return true;
			}
		}

		return false; 
	}

	public function create_client($email, $password, $title, $url){
		$data = array(
			"email" => $email,
			"password" => $password,
			"title" => $title,
			"url" => $url,
			"is_wordpress" => true,
		);
		$json["client"] = $data;
		$data_string = json_encode($json);	
		
		$response = $this->apiCall('public/clients', "POST", $data_string);
		if (!$response->error){
			if (!$this->login($email, $password)){ //if login failed try again!
				return $this->login($email, $password);
			}
		}
		return false;
	}

	private function freeze_client($state){
		$options = $this->get_options();
		$data["client"] = array(
			"is_frozen" => $state,
		);
		$data_string = json_encode($data);	
		$this->apiCall("clients/{$options->id}", "PUT", $data_string);
	}

	private function apiCall($api, $method="GET", $data_string=""){
		$url = 'https://40nuggets.com/api/'.$api;  
		$result = $this->httpCall($url, $method, $data_string);
		$json = json_decode($result);
		return $json;
	}
	
	private function httpCall($url, $method=null, $data_string=null){
		$cookie = dirname(__FILE__) . '/fortynuggets.fnm';
		$savedVersion = $this->getVersionSaved();
		
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17 WordPressPlugin/{$savedVersion}");
		if (isset($method)) 		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if (isset($data_string)) 	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);		
		
		$result = curl_exec($ch);
		
		curl_close($ch);
		
		return $result;
	}
	
	public function getURL($target){
		$page = empty($target) ? "dashboard" : $target;
		$options = $this->get_options();
		return "https://40nuggets.com/dashboard/{$page}?alk={$options->akl}";
	}

	private function save_options ($options){
		update_option('40nm-options', base64_encode(json_encode($options)));
	}
	
	public function get_options (){
		return json_decode(base64_decode(get_option('40nm-options')));
	}
	
	public function set_api_key ($api_key){
		$options = $this->get_options();
		$options->api_key = $api_key;
		//Convert $options from Object to Array (set_options takes an array not an object)
		$data = json_decode(json_encode($options),true);
		$this->save_options($data);
	}	
}