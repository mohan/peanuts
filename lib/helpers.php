<?php
// Peanuts
// License: GPL


function include_template($template_name, $html_container=true)
{
	$template_path = "./templates/" . CONFIG_TEMPLATE . '/';
	require_once './templates/' . CONFIG_TEMPLATE . '/functions.php';
	include $template_path . ($html_container ? "index.php" : $template_name);
}


function urlto_template_asset($uri)
{
	return CONFIG_ROOT_URL . 'templates/' . CONFIG_TEMPLATE . '/' . $uri;
}


function linkto($html, $uri, $args=[], $method='get', $class_attr='')
{
	return "<a href='" . urlto($uri, $args, $method, '&amp;') . "' class='$class_attr'>" . htmlentities($html) . "</a>";
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
	$_value  = $value . '%' . md5($name . '%' . $value . '%' . SECURE_HASH);

	// 20 minutes
	setcookie($name, $_value, time() + 20 * 60, CONFIG_ROOT_URL);
}


function secure_cookie_get($name)
{
	if(!isset($_COOKIE[$name])) return false;

	$parts = explode('%', $_COOKIE[$name]);

	if($parts[1] != md5($name . '%' . $parts[0] . '%' . SECURE_HASH)) return false;

	return $parts[0];
}


function cookie_delete($name)
{
	setcookie($name, '', time() - 3600);
}
