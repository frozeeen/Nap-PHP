## PURE PHP REST API BOILERPLATE

A very simple `ready-to-use` api boilerplate for small projects. You might encounter bugs and errors that are lurking behind the scene. Feel free to edit the source code or inform me about the issue.

### Installation

* Clone this repo into your project and changed the folder name to `api`.
* Go to `api\public` and edit 4th line of `.htaccess` and change `php_rest_boilerplate` to `api` which is your api folder.

```PHP
# If your project is at the root directory
RewriteBase /api/public

# If your project is inside a subfolder named `foobar`
RewriteBase /foobar/api/public
```

### File structure
Using this boilerplate, your going to work on `setup` folder frequently.
```PHP
* app
    * private   # All of driver classes and main drivers of API are located
    * setup     # All of your API, Database Configuration, Models, Routes
* public        # You can place your assets and upload your static files here 
```

### APIs or (Controller)
Our controller or script that will process our request will be in the `app\setup\apis`, every api will extend to `API` class which will help us to load models, and verify CSRF tokens.
```PHP
class TestAPI extends Api{
    public function get(){}     # GET Request
    public function post(){}    # POST Request
    public function put(){}     # PUT Request
    public function delete(){}  # DELETE Request
    public function foobar(){}  # CUSTOM method
}

```

### Routing
The routes are located at `app\setup\routes.php`
```PHP
/**
 * By `REQUEST_METHOD` request
 * url will go to `TestAPI` API 
 * depending on $_SERVER['REQUEST_METHOD']
 */
"tests" 		=> "TestAPI",

/**
 * By `CUSTOM_METHOD` request
 * url will call the `TestAPI` API, calling `foobar` method
 */
"tests/foobar" 	=> "TestAPI.foobar"

/** With Parameter */
"tests/:id"     => "TestAPI"
```