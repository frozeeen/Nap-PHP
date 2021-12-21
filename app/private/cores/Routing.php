<?php
		
	/**
	 * NAP Routing 2.0
	 * Suggested by [@Kulotsystems](https://github.com/kulotsystems)
	 */
	class Routing{
		
		public $routes = [];

		/**
		 * Dynamic routing, the `REQUEST_METHOD` will be the basis
		 * what is the method to be use
		 * @param string $link
		 * @param string $class
		 */
		public function auto($link, $class){
			$this->get($link, 		$class . "::get");
			$this->post($link, 		$class . "::post");
			$this->put($link, 		$class . "::put");
			$this->delete($link, 	$class . "::delete");
		}

		/**
		 * Link is expecting `GET REQUEST_METHOD`
		 * @param string $link
		 * @param string $class
		 */
		public function get($link, $class){
			$this->register($link, $class, __FUNCTION__);
		}

		/**
		 * Link is expecting `POST REQUEST_METHOD`
		 * @param string $link
		 * @param string $class
		 */
		public function post($link, $class){
			$this->register($link, $class, __FUNCTION__);
		}

		/**
		 * Link is expecting `PUT REQUEST_METHOD`
		 * @param string $link
		 * @param string $class
		 */
		public function put($link, $class){
			$this->register($link, $class, __FUNCTION__);
		}

		/**
		 * Link is expecting `DELETE REQUEST_METHOD`
		 * @param string $link
		 * @param string $class
		 */
		public function delete($link, $class){
			$this->register($link, $class, __FUNCTION__);
		}

		/**
		 * Register function route
		 * @param string $link
		 * @param string $class
		 * @param string $method
		 */
		private function register($link, $class, $method){
			$this->routes[strtoupper($method)][$link] = [
				"link" 	 => $link,
				"class"  => $class
			];
		}

	}

?>