<?php 
/*
Plugin Name: Feed Facebook, Leave Facebook
Plugin URI: http://www.keyvan.net/code/feed-facebook-leave-facebook/
Donate link: http://www.keyvan.net/code/feed-facebook-leave-facebook/#donate
Description: Creates a separate, partial feed for Facebook. Use this to direct Facebook visitors to your blog while leaving your main full-text feed intact. <a href="../feed/?feedfacebook">URL of your feed for Facebook</a>
Author: Keyvan Minoukadeh
Version: 1.0
Author URI: http://www.keyvan.net/
*/

/*
Copyright 2009 Keyvan Minoukadeh

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 

if (isset($_GET['feedfacebook'])) {
	add_filter('pre_option_rss_use_excerpt', 'feedfacebook_rss_use_excerpt');
	add_filter('the_excerpt_rss', 'feedfacebook_the_excerpt_rss');
	// override feedburner feedsmith plugin
	add_action('init', 'feedfacebook_kill_feedsmith', 5); // 5 to make sure it runs before feedsmith
}
function feedfacebook_rss_use_excerpt($val) {
	return true;
}
function feedfacebook_the_excerpt_rss($val) {
	$msg = trim($_GET['feedfacebook']);
	if (!$msg || $msg == 'leavefacebook') $msg = 'Leave Facebook to read the rest on my blog';
	$val .= '<br /><br />';
	$val .= '<a rel="nofollow" href="'.apply_filters('the_permalink_rss', get_permalink()).'">'.htmlspecialchars($msg).'</a>';
	// should we link to an explanation?
	if (isset($_GET['why'])) $val .= ' (<a rel="nofollow" href="'.$_GET['why'].'">'.(isset($_GET['whytext']) ? $_GET['whytext'] : 'why?').'</a>)';
	return $val;
}
function feedfacebook_kill_feedsmith() {
	global $feedburner_settings;
	$feedburner_settings = array(
		'feedburner_url'		=> '',
		'feedburner_comments_url'	=> ''
	);
}
?>