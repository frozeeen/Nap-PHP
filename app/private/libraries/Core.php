<?php
	
/*
 * App Core Class
 * Creates URL and LOAD Core Controller
 * URL Format: controller/method/params
 *
 */
class Core{
	
	protected $currentController = 'Api';
	protected $currentMethod = 'index';
	protected $params = [];

	// Get the URL On load of the Page
	public function __construct(){
		
		# Get current URL
		$url = $_GET['url'] ?? "";
		$url = trim(filter_var(strtolower($url), FILTER_SANITIZE_URL), '/');

		# Get routes
		require APPROOT . "setup/routes.php";

		# Throw error if route doesn't exist
		if( !isset( $routes[strtolower($url)] ) ){
			$this->throwError();
		}

		# Split the handler and the method
		$url = explode(".", $routes[$url]);

		# Look for the controller in the `Controllers` folder
		if( file_exists(APPROOT .'setup/apis/' . ucwords($url[0]) . '.php') ){
			$this->currentController = ucwords($url[0]);
		}else{
			$this->throwError();
		}

		# Get post data
		if( file_get_contents('php://input') != '' ){
			$_POST = (array)json_decode(file_get_contents('php://input'), true);
		}

		# Require the controller and initialize the controller
		require_once APPROOT . 'setup/apis/' . $this->currentController . '.php';
		$this->currentController = new $this->currentController;
		
		# Check if the method exist inside the initialized controller
		$activeMethod = (isset($url[1])) ? $url[1] : $_SERVER['REQUEST_METHOD'];
		if( method_exists($this->currentController, $activeMethod) ){
			$this->currentMethod = $activeMethod;
		}else{
			$this->throwError();
		}

		// Call the callback with the array of parameters
		call_user_func_array([$this->currentController, $this->currentMethod], []);

	}

	# Return error due to incomplete API
	private function throwError(){
		returnJSON([
			"error" => 404,
			"message" => "Api not found"
		]);
	}

}

?>