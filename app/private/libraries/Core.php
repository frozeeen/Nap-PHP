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

	# Get the URL on load of the Page
	public function __construct(){
		
		# Get current URL
		$url = $_GET['url'] ?? "";
		$url = trim(filter_var(strtolower($url), FILTER_SANITIZE_URL), '/');
		$selectedClass = $this->getRequestClass($url);

		# Split the handler and the method
		$url = explode(":", $selectedClass);

		# Look for the controller in the `Controllers` folder
		if( file_exists(APPROOT .'setup/apis/' . ucwords($url[0]) . '.php') ){
			$this->currentController = ucwords($url[0]);
		}else{
			$this->throwError();
		}

		# Get post data
		if( file_get_contents('php://input') != '' && $_POST == '' ){
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
		call_user_func_array([$this->currentController, $this->currentMethod], [$this->params]);
	}

    /**
     * Get request class based on url
     * @param string $url - The current url
     * @return mixed
     */
	private function getRequestClass($url){

		# Get routes
		require APPROOT . "setup/routes.php";

		# If primary route doesn't exist, try with placeholder
		if( !isset( $routes[strtolower($url)] ) ){

			$isRouteExist = false;
			$url_split = explode('/', $url);
			$url_split_length = count($url_split);

			foreach($routes as $template => $route){

				$route_split = explode("/", $template);
				if( $url_split_length == count($route_split) ){

					$isMatched = true;
					foreach($route_split as $i => $_slug){
						if( $_slug[0] != ':' && $_slug != $url_split[$i] ){
							$isMatched = false;
						}
					}

					# Get the parameters based on placeholder
					if( $isMatched ){
						foreach($route_split as $i => $_slug){
							if( $_slug[0] == ':' ){
								$this->params[substr($_slug, 1)] = $url_split[$i];
							}
						}
						return $route;
					}

				}
			}
			
			if( $isRouteExist === false ){
				$this->throwError();
			}
		
		}else{
			return $routes[$url];
		}
	}

	# Return error due to incomplete API
	private function throwError(){
		returnJSON([
			"message" => "Api not found"
		], 404);
	}

}

?>