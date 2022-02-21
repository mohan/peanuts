<?php
// Peanuts
// License: GPL


function include_template($template_name, $args=[], $html_container=true)
{
	$template_path = "./templates/" . CONFIG_TEMPLATE . '/';

	extract($args, EXTR_SKIP);
	include $template_path . ($html_container ? "index.php" : $template_name);
}


function urlto_template_asset($uri)
{
	return CONFIG_ROOT_URL . 'templates/' . CONFIG_TEMPLATE . '/' . $uri;
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


function flash_set($html)
{
	if($html) secure_cookie_set('flash', $html);
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
	echo "<textarea class='input' style='height:300px;'>" . htmlentities(var_dump($arg, true)) . "</textarea>";
	if($exit) exit;
}
