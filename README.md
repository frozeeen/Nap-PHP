![NAP Banner](https://user-images.githubusercontent.com/40148013/146707759-45ed54f6-f992-441a-b102-682169baeef4.jpg)

**Table of contents**
* [ Installation ](#installation)
* [ File Structure ](#file-structure)
* [ APIs ](#apis)
	* [ API class helpers ](#api-class-helpers)
* [ Routing ](#routing)
* [ Models ](#models)
	* [ Database configuration ](#database-configuration)
	* [ Creating models ](#creating-models)
	* [ Model in action ](#model-in-action)
* [Authentication](#authentication)
	* [ Authentication Methods ](#authentication-methods)
* [Validation](#validation)

## Installation
Clone this repo into our project, change the folder name to `api` and done.

## File structure
Using this boilerplate, we're going to work on `setup` folder frequently.
```PHP
* app
	* private   # Main drivers of boilerplate are located
	* setup     # APIs, Database Configuration, Models, Routes etc.
		* apis		
		* composer
		* helpers	
		* configs
		* models
* public        # We can place assets and upload your static files here 
```

## Routing
The routes are located at `app\setup\routes.php`
```PHP
/** By `REQUEST_METHOD` routes */
$routing->get("url", "classAPI::method");
$routing->post("url", "classAPI::method");
$routing->put("url", "classAPI::method");
$routing->delete("url", "classAPI::method");

/**
 * Auto routing `REQUEST_METHOD`
 * This is equal to
 * $route->get("url", 	"class::get");
 * $route->post("url", 	"class::post");
 * $route->put("url", 	"class::put");
 * $route->delete("url", class::delete");
 */
$routing->auto("url", "classAPI");

/** With parameter/s URL */
$routing->get("url/:id", "classAPI::method");
```

## APIs
Our APIs that will process our request will be in the `app\setup\apis`, every api will extend to `Api` class which will help us to load models, make responses, and verify CSRF tokens.
```PHP
class TestAPI extends Api{
	public function get(){}     # GET Request
	public function post(){}    # POST Request
	public function put(){}     # PUT Request
	public function delete(){}  # DELETE Request
	public function foobar(){}  # CUSTOM method

	# For, with parameters URL, we have several ways
	# First, we can access it from Api class
	public function choiceOne(){
		$this->id; // (string)
	}

	# Second, from argument
	# $params is an object containing the parameters
	public function choiceTwo($params){
		$params; // (object)
		$params->id; // (string)
	}

	/**
	 * Handling Request Parameters
	 * Sample data:
	 * {
	 * 	"username": "foobar",
	 * 	"email": "foobar@gmail.com"
	 * } 
	 **/
	public function handlingRequest(){

		# Access by Property
		# Throw error if the request doesn't exist
		$this->request->username;
		$this->request->password;

		# Access by method
		# Return null if doesn't exist
		$this->request("password");
	}

}
```

### APIs helpers
API class comes with simple methods that can help speed up our development.
```php
class TestAPI extends Api{

	public function foobar(){

		# Terminate the request when not using `POST` request
		$this->expectMethod("POST");

		# Return CSRF token
		# You can pass `true` to refresh csrf token
		$token = $this->csrfGetToken();

	}
}
```

## Loading Models / Interacting with MySQL Database
To interact with MySQL database, we need to create a model that can connect to database and perform queries. Each model will extends to `Model` class, that will give us some *simple* pre-defined methods such as `get`, `insert`, `update`, and `delete`.

### Database Configuration
First we need to update some configuration to properly connect to MySQL.<br>Go to `app\setup\configs\config.php` and update the following values.
```php
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'nap');
```

### Creating a model
To create a model, go to models, create a *model* that extends to `Model` class.
```php
class Task extends Model{
}
```
Next, create a constructor to define our table, specify the table name using `table_name` property, and table columns using `table_columns` property.
```php
class Task extends Model{
	function __construct(){
		$this->table_name = "tasks";
		$this->table_columns = ["id", "title", "content"];
	}
}
```

### Model in action
To use model into our API, we can load model using `model()` method that is part of API class.
```php
class TaskAPI extends Api{

	 # Load `Task` model
	function __construct(){
		$this->taskModel = $this->model("Task");
	}
	
	# Return data
	public function get(){
		$this->taskModel->get("taskID");
	}

	# Insert new data
	public function post(){
		$this->taskModel->insert([
			"title" => "Take a Nap",
			"content" => "Let's rest"
		]);
	}

	# Update existing task
	public function put(){
		$this->taskModel->update([
			"title" => "Take a Nap (edited)",
			"content" => "Let's take a rest part 2"
		], [ # This second parameter is the condition
			"id" => "taskID"
		]);
	}

	# Delete existing task
	public function delete(){
		$this->taskModel->delete("taskID");
	}

}
```

## Authentication
Login and registration are two of the most important parts of your application, if necessary. NAP provides a basic authentication boilerplate that you can start with, and that also extends to `Model` class.

To use the authentication boilerplate, you can just rename the `Authentication.php` located in the models folder, or duplicate it based on your needs.

The boilerplate is just a plain session-based authentication that you can rewrite if you want or create your own authentication class.

### Authentication Methods
Authentication Boilerplate includes.
```php
/**
 * First array is the data to be inserted
 * Second is the keys that need to be unique
 */
$this->Authentication->register([
	"username"	=> $this->request->username,
	"password"	=> $this->request->password
], [
	"unique"	=> ['username']
]);

# Login
$this->Authentication->login("username", "password");

/**
 * Check status if logged or not
 * Pass `true` to terminate the script if you are not logged in.
 */
$this->Authentication->guarded();

# Logout
$this->Authentication->logout();
```

## Validation
Checking for values can become exhaustive. That can create a bunch of nested conditional statements.

The validation class is enabled by default in the `boostrap.php` in the app folder. To use the validation class, pass the items with their checks to the first parameter, and pass a boolean to the second parameter to set if the validation will automatically terminate the script if there's an invalid check.

As an example, let's put a validation on the `AuthAPI login`.
```php
public function login(){

	new Validate([
		[
			# The value to be check
			"value" => $this->request("username"),

			# The key of this item when it is converted as an object
			"key"	=> "username",

			# The label on error
			"label"	=> "Username",

			# Several checks to be perform, separated by pipe "|"
			"checks" => "required|min:3|max:50"
		],
		[
			"value" => $this->request("password"),
			"key"	=> "password",
			"label"	=> "Password",
			"checks" => "required|min:8|max:150"
		]
	], true);

	$return = $this->Authentication->login($this->request->username, $this->request->password);

	$this->json([
		"status"	=> $return['success'],
		"message" 	=> $return['message']
	]);
}
```