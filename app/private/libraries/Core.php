<?php
	
	# ENTRY POINT OF THE API
	class Core{
		
		protected $currentController = 'Base';
		protected $currentControllerMethod = 'notFound';
		protected $params = [];

		# LOAD THE SAID Controller onload
		public function __construct(){
			
			# GET THE URL that is setup by the .htaccess
			$url = "";
			if( isset($_GET['url']) ){
				$url = rtrim( $_GET['url'], '/');
				$url = filter_var($url, FILTER_SANITIZE_URL);
			}
			$url = explode('/', $url);
			
			# FIND THE CONTROLLER
			$selectedController = ucwords($url[0]);
			unset($url[0]);

			// If the API exist, select that API
			// If now choose the default API
			if( file_exists('../app/setup/apis/' . $selectedController . '.php') ){
				$this->currentController = $selectedController;
			}

			# LOAD THE CONTROLLER, and intiailize the API
			require_once '../app/setup/apis/' . $this->currentController . '.php';
			$this->currentController = new $this->currentController;
			
			# CHOOSE THE REQUEST FROM CLIENT SIDE
			// If ever the client, past a `_method` parameters
			// This will going to override the method the `SERVER['REQUEST_METHOD']`
			$passedRequest = json_decode(file_get_contents('php://input'), true);
			if( isset($passedRequest['_method']) ){
				$request = strtolower($passedRequest['_method']);
			}else{
				$request = strtolower($_SERVER['REQUEST_METHOD']);
			}

			# CHECK IF THE METHOD EXIST inside the loaded API
			if( method_exists($this->currentController, $request) ){
				$this->currentControllerMethod = $request;
			}

			# GET THE URL PARAMETERS AND PASS IT TO THE LOADED Controller
			$this->params = $url ? array_values($url) : [];

			# Execute the METHOD inside the CONTROLLER
			call_user_func_array([$this->currentController, $this->currentControllerMethod], $this->params);

		}

	}

?>