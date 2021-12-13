## NAP
NAP is vanilla PHP REST-API boilerplate. It's friendly and lightweight 🐘⚡.

### Installation
* Clone this repo into your project, change the folder name to `api` and done.

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
    public function withParams($PARAMS){} # WITH parameters
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
"tests/foobar" 	=> "TestAPI:foobar"

/** With Parameter */
"tests/:id"     => "TestAPI:withParams"
```