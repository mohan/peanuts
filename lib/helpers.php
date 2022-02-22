<?php
// Peanuts
// License: GPL


function include_template($template_name, $args=[], $html_container=true)
{
	$template_path = './app/templates/' . CONFIG_TEMPLATE . '/';

	extract($args, EXTR_SKIP);
	include $template_path . ($html_container ? "index.php" : $template_name);
}


function template_filepath($template_name)
{
	$template_path = './app/templates/' . CONFIG_TEMPLATE . '/';
	return $template_path . $template_name;
}


function urlto_template_asset($uri)
{
	return CONFIG_ROOT_URL . 'app/templates/' . CONFIG_TEMPLATE . '/assets/' . $uri;
}


function linkto($html, $uri, $args=[], $class_attr='', $method='get')
{
	echo "<a href='" . urlto($uri, $args, $method, '&amp;') . "' class='$class_attr'>" . htmlentities($html) . "</a>";
}


function urlto($uri, $args=[], $method='get', $arg_separator='&')
{
	$uri_arg = ($method == 'post' ? 'post-uri=' : 'uri=') . $uri;
	$args_str = $args ? http_build_query($args, '', $arg_separator) : NULL;
	return CONFIG_ROOT_URL . '?' . $uri_arg . ($args_str ? $arg_separator : '') . $args_str;
}


function redirectto($uri, $args=[])
{
	header('Location: ' . urlto($uri, $args));
}


function header404()
{
	header("HTTP/1.1 404 Not Found");
}


function flash_set($html, $in_current_request=false)
{
	if($html) {
		if($in_current_request) $_REQUEST['APP']['flash'] = $html;
		else secure_cookie_set('flash', $html);
	}
}


function flash_clear()
{
	cookie_delete('flash');
}


function secure_cookie_set($name, $value)
{
	// <= 1/2 day; may expire at am/pm;
	$authenticity = md5($name . '%' . $value . '%' . date('y-m-d-a') . '%' . SECURE_HASH);

	// Expires end of session/browser close
	setcookie($name, "$value%$authenticity", 0, CONFIG_ROOT_URL, '', false, true);
}


function secure_cookie_get($name)
{
	if(!isset($_COOKIE[$name])) return false;

	$parts = explode('%', $_COOKIE[$name]);
	$value = $parts[0];
	$given_authenticity = $parts[1];

	$authenticity = md5($name . '%' . $value . '%' . date('y-m-d-a') . '%' . SECURE_HASH);

	if($given_authenticity != $authenticity) return false;

	return $value;
}


function cookie_delete($name)
{
	setcookie($name, '', time() - 3600);
}


// Simple debug
// Remember to remove all debugs
function __d($arg, $exit=false)
{
	echo "<textarea class='input' style='height:300px;'>" . htmlentities(print_r($arg, true)) . "</textarea>";
	if($exit) exit;
}















// 
// HTTP Filters
// 

// Map action names to functions and call current name
// Max action name 32 chars
function filter_routes($get_action_names, $post_action_names)
{
	if( is_string($_GET['post-uri']) && strlen($_GET['post-uri']) < 32 && in_array($_GET['post-uri'], $post_action_names)){
		return call_user_func( 'post_' . preg_replace("/[^a-zA-Z0-9]/", '_', $_GET['post-uri']));
	}

	if( is_string($_GET['uri']) && strlen($_GET['uri']) < 32 && in_array($_GET['uri'], $get_action_names)){
		return call_user_func( 'get_' . preg_replace("/[^a-zA-Z0-9]/", '_', $_GET['uri']));
	}

	return get_404();
}


// Permitted GET and POST params, with typecasting
function filter_permitted_params($get_param_names, $post_param_names, $get_typecasts, $post_typecasts)
{
	foreach ($_GET as $key => $value) {
		if(!in_array($key, $get_param_names)) unset($_GET[$key]);
	}

	foreach ($_POST as $key => $value) {
		if(!in_array($key, $post_param_names)) unset($_POST[$key]);
	}

	foreach ($get_typecasts as $name => $type) {
		if(is_string($_GET[$name]))
		switch ($type) {
			case 'int': $_GET[$name] = intval($_GET[$name]); break;
			case 'float': $_GET[$name] = floatval($_GET[$name]); break;
			case 'bool': $_GET[$name] = boolval($_GET[$name]); break;
		}
	}

	foreach ($post_typecasts as $name => $type) {
		if(is_string($_POST[$name]))
		switch ($type) {
			case 'int': $_POST[$name] = intval($_POST[$name]); break;
			case 'float': $_POST[$name] = floatval($_POST[$name]); break;
			case 'bool': $_POST[$name] = boolval($_POST[$name]); break;
		}
	}
}


// Defines constants CONFIG_NAME from config ini file
function filter_set_config($filepath){
	$config = parse_ini_file($filepath);

	foreach ($config as $key => $value) {
		define('CONFIG_' . $key, $value);
	}
}


function filter_set_flash()
{
	$flash = secure_cookie_get('flash');

	if($flash){
		$_REQUEST['APP']['flash'] = $flash;
		cookie_delete('flash');
	}
}





