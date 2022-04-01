<?php
	
	# Set up routes
	$routing = new Routing();

	# Default
	$routing->get("/", "TestAPI::get");

	/**
	 * Auto routing `REQUEST_METHOD`
	 * This is equal to
	 * $route->get("url", 	"class::get");
	 * $route->post("url", 	"class::post");
	 * $route->put("url", 	"class::put");
	 * $route->delete("url", class::delete");
	 */
	$routing->auto("test", "TestAPI");

	/** With parameter/s URL */
	$routing->get("foobar/:id", "TestAPI::foobar");

	/** Authentication Test */
	$routing->post("auth/login", "AuthAPI::login");
	$routing->post("auth/register", "AuthAPI::register");
	$routing->post("auth/check", "AuthAPI::check");
	$routing->post("auth/logout", "AuthAPI::logout");

?>