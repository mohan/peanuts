<?php
// Peanuts
// License: GPL

function user_initial($username)
{
	preg_match_all("/\b([A-Za-z0-9])/", CONFIG_USERS[$username], $matches);
	return strtoupper(join($matches[1]));
}

function html_markdown($text)
{
	return "<div class='markdown'>" . render_markdown($text, true) . "</div>";
}

function html_flash()
{
	return tag($_REQUEST['flash'], ['id'=>'flash']);
}

function html_app_title()
{
	return tag(CONFIG_APP_TITLE, ['class'=>'heading'], 'h1');
}

function html_user_icon($username)
{
	return tag(user_initial($username), ['class'=>'user-icon'], 'h4');
}

function url_getpost($post)
{
	return urltoget('post', ['post_id'=>$post['id']]);
}

function link_getpost($html, $post, $attrs=[])
{
	return linkto('post', $html, ['post_id'=>$post['id']], $attrs);
}

function link_editpost($html, $post, $attrs=[])
{
	return linkto('edit-post', $html, ['post_id'=>$post['id']], $attrs);
}

function link_editcomment($html, $post, $comment, $attrs=[])
{
	return linkto('edit-comment', $html, ['post_id'=>$post['id'], 'comment_id'=>$comment['id']], $attrs);
}
