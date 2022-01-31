<?php
	
/**
 * API Core
 * Find route and Load controller
 */
class Core{
	
	public $url = "";
	protected $currentController = '';
	protected $currentMethod = '';
	protected $params = [];

	# Get the URL on load of the Page
	public function __construct(){
		
		# Get current URL and specific class
		$this->url = $_GET['url'] ?? "/";
		$this->url = trim(filter_var(strtolower($this->url), FILTER_SANITIZE_URL), '/');
		$this->url = ( $this->url == "" ) ? "/" : $this->url;
		$selectedClass = $this->getRequestClass($this->url);
		
		# Split the handler and the method
		$this->url = explode("::", $selectedClass);

		# Look for the controller in the `Controllers` folder
		if( file_exists(APPROOT .'setup/apis/' . ucwords($this->url[0]) . '.php') ){
			$this->currentController = ucwords($this->url[0]);
		}else{
			$this->throwError();
		}

		# Get data
		if( file_get_contents('php://input') != '' && count($_POST) == 0 ){
			$_POST = (array)json_decode(file_get_contents('php://input'), true);
		}
		
		# Require the controller and initialize the controller
		require_once APPROOT . 'setup/apis/' . $this->currentController . '.php';
		$this->currentController = new $this->currentController;
		
		# Check if the method exist inside the initialized controller
		$activeMethod = (isset($this->url[1])) ? $this->url[1] : $_SERVER['REQUEST_METHOD'];
		if( method_exists($this->currentController, $activeMethod) ){
			$this->currentMethod = $activeMethod;
		}else{
			$this->throwError();
		}

		# Sanitize passed data from `$_POST`
		foreach($_POST as $key => $input){
			if( is_string($input) ){
				$this->currentController->request[$key] = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
			}else if( is_array($input) ){
				$this->currentController->request[$key] = $this->htmlspecialchars_array($input);
			}else if( is_numeric($input) ){
				$this->currentController->request[$key] = $input;
			}
		}
		$this->currentController->request = (object)$this->currentController->request;

		/**
		 * Parameters will be properties of the api controller.
		 * Suggested by [@Kulotsystems](https://github.com/kulotsystems)
		 */
		foreach($this->params as $key => $param){
			$this->currentController->{$key} = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
		}

		# Call the callback with the array of parameters
		call_user_func_array([$this->currentController, $this->currentMethod], [$this->params]);
	}

	/**
     * Get request class based on url
     * @return mixed
     */
	private function getRequestClass(){

		# Get routes
		require APPROOT . "setup". DIRECTORY_SEPARATOR ."routes.php";

		# If primary route doesn't exist, try with placeholder
		if( !isset( $routes[strtolower($this->url)] ) ){

			$isRouteExist = false;
			$url_split = explode('/', $this->url);
			$url_split_length = count($url_split);

			# Check if METHOD set
			if( !isset( $routing->routes[$_SERVER['REQUEST_METHOD']] ) ){
				$this->throwError();
			}

			# Check every save route
			foreach($routing->routes[$_SERVER['REQUEST_METHOD']] as $template => $route){

				# Empty route
				if( $template == "/" ){
					if( $this->url == $template ){
						return $route['class'];
					}else{
						continue;
					}
				}

				$route_split = explode("/", $template);
				if( $url_split_length == count($route_split) ){

					$isMatched = true;
					foreach($route_split as $i => $_slug){
						if( $_slug[0] != ':' && $_slug != $url_split[$i] ){
							$isMatched = false;
						}
					}

					# Get the parameters based on placeholder
					if( $isMatched == true ){
						foreach($route_split as $i => $_slug){
							if( $_slug[0] == ':' ){
								$this->params[substr($_slug, 1)] = $url_split[$i];
							}
						}
						return $route['class'];
					}
				}

			}
			
			return $this->throwError();
		}else{
			return $routes[$this->url];
		}
	}

	# Encode arrays
	# From: https://www.php.net/manual/en/function.htmlspecialchars.php#50842
	private function htmlspecialchars_array($arrays = array()) {
		$rs = array();
		foreach( $arrays as $key => $value ){
			if(is_array($value)) {
				$rs[$key] = $this->htmlspecialchars_array($value);
			}else {
				$rs[$key] = htmlspecialchars($value, ENT_QUOTES);
			}
		}
		return $rs;
	}

	# Hard coded reponse for not found
	private function throwError(){

		if( ENVIRONMENT == "DEV" ){
			http_response_code(400);
			echo json_encode([
				"status" => false,
				"message" => "API not found",
				"methodUsed" => $_SERVER["REQUEST_METHOD"],
				"link" => $this->url
			]);
		}else{
			http_response_code(404);
			echo json_encode([
				"status" => false,
				"message" => "Api not found"
			]);
		}
		
		exit;
	}

}

?>