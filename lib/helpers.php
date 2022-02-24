<?php
// Peanuts
// License: GPL


function render($template_name, $args=[], $html_container='index.php')
{
	$template_path = './' . APP_NAME . '/templates/' . APP_TEMPLATE . '/';
	$uri = $_GET['uri'];

	extract($args, EXTR_SKIP);
	include $template_path . $html_container;
	exit;
}


function render_partial($template_name, $args=[])
{
	$template_path = './' . APP_NAME . '/templates/' . APP_TEMPLATE . '/';
	
	extract($args, EXTR_SKIP);
	include $template_path . $template_name;
}


function urlto_template_asset($uri)
{
	return CONFIG_ROOT_URL . APP_NAME . '/templates/' . APP_TEMPLATE . '/assets/' . $uri;
}


function formto($uri, $args=[], $attrs=[])
{
	$url = urltopost($uri, $args);

	$attrs_str = '';
	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "'";

	echo "<form method='post' action='$url' $attrs_str>";
}


function linkto($uri, $html, $args=[], $attrs=[])
{
	$url = urltoget($uri, $args, '&amp;');
	
	$attrs_str = '';
	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";

	echo "<a href='$url' $attrs_str>" . htmlentities($html) . "</a>";
}


function urltoget($uri, $args=[], $arg_separator='&')
{
	$args['uri'] = $uri;
	$hash = isset($args['__hash']) ? '#' . $args['__hash'] : '';
	unset($args['__hash']);

	return CONFIG_ROOT_URL . '?' . http_build_query($args, '', $arg_separator) . $hash;
}


function urltopost($uri, $args=[], $arg_separator='&')
{
	$args['post_uri'] = $uri;
	return CONFIG_ROOT_URL . '?' . http_build_query($args, '', $arg_separator);
}


// Auto htmlentities for safe user input
function tag($html, $attrs=[], $name='div', $closing=true)
{
	if($name != 'input' && $name != 'textarea' && !$html) return;

	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";
	
	echo "<$name $attrs_str";

	if($name != 'input'){
		echo ">" . htmlentities($html);
		if($closing) echo "</$name>";
	} else {
		echo "value='" . htmlentities($html) . "'";
		echo " />";
	}
}













function redirectto($uri, $args=[])
{
	header('Location: ' . urltoget($uri, $args));
	exit;
}


function get_404()
{
	header("HTTP/1.1 404 Not Found");
	render('404.php');
}









function flash_set($html, $in_current_request=false)
{
	if($html) {
		if($in_current_request) $_REQUEST['flash'] = $html;
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

	// Todo: If it fails, check if it matches -6
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
	if( is_string($_GET['post_uri']) && in_array($_GET['post_uri'], $post_action_names)){
		return call_user_func( 'post_' . preg_replace("/[^a-zA-Z0-9]/", '_', $_GET['post_uri']));
	}

	if( is_string($_GET['uri']) && in_array($_GET['uri'], $get_action_names)){
		return call_user_func( 'get_' . preg_replace("/[^a-zA-Z0-9]/", '_', $_GET['uri']));
	}

	if( !$_GET['uri'] ){
		return get_root();
	}

	return get_404();
}


// Permitted GET, POST, cookie params, with strlen check and typecasting
// Ex: $get_param_names = [ 'param_name' => int_length ... ]
function filter_permitted_params($get_param_names, $post_param_names, $cookie_param_names, $get_typecasts, $post_typecasts)
{
	foreach ($_GET as $key => $value) {
		if(!array_key_exists($key, $get_param_names)) unset($_GET[$key]);
		else if(strlen($_GET[$key]) > $get_param_names[$key]) get_404();
	}

	foreach ($_POST as $key => $value) {
		if(!array_key_exists($key, $post_param_names)) unset($_POST[$key]);
		else if(strlen($_POST[$key]) > $post_param_names[$key]) get_404();
	}

	foreach ($_COOKIE as $key => $value) {
		if(!array_key_exists($key, $cookie_param_names)) unset($_COOKIE[$key]);
		else if(strlen($_COOKIE[$key]) > $cookie_param_names[$key]) get_404();
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
		$_REQUEST['flash'] = $flash;
		cookie_delete('flash');
	}
}
