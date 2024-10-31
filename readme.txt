=== Orbisius Snippets ===
Contributors: lordspace,orbisius
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7APYDVPBCSY9A
Tags: code, sourcecode, source-code, snippet, library, snippets, php
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 1.0.4
Requires PHP: 5.2.4
License: GPLv2 or later

This plugin allows you to include snippets in your posts/pages.

== Description ==

To use it you need to create/edit a page/post.
Then create a <a href='https://codex.wordpress.org/Custom_Fields' target="_blank">custom field</a> (e.g. orb_snippet1) and paste the snippet into the value box.
To make the snippet show up use this shortcode.
[orb_snippet id='orb_snippet1']

= Features =

* Super easy to use.
* Supports SyntaxHighlighter Evolved (if installed) https://wordpress.org/plugins/syntaxhighlighter/)
* Load a snippet from another page/post (pass post_id=123 to the shortcode)

= Usage =
To use it you need to create/edit a page/post.
Then create a <a href='https://codex.wordpress.org/Custom_Fields' target="_blank">custom field</a> (e.g. orb_snippet1) and paste the snippet into the value box.
To make the snippet show up use this shortcode.
[orb_snippet id='orb_snippet1']

= Demo =

n/a

= Support =
* Support is handled on our site: <a href="http://orbisius.com/support/" target="_blank" title="[new window]">http://orbisius.com/support/</a>
* Please do NOT use the WordPress forums or other places to seek support.

= Author =

Do you need an amazing plugin created especially for your needs? Contact me.
Svetoslav Marinov (Slavi) | <a href="http://orbisius.com" title="Custom Web Programming, Web Design, e-commerce, e-store, Wordpress Plugin Development, Facebook and Mobile App Development in Niagara Falls, St. Catharines, Ontario, Canada" target="_blank">Custom Web and Mobile Programming by Orbisius.com</a>

== Upgrade Notice ==
n/a

== Screenshots ==
1. n/a

== Installation ==

1. Upload the zip package within WP Admin > Plugins > Add
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How are the snippets stored? =
They are stored as post meta. Each snippet is tied to the post that uses it.

== Changelog ==

= 1.0.4 =
* unescape [ ] used in code with php
* The settings links was using another plugin's text.
* Remvoved the shortcode call

= 1.0.3 =
* There were cases when double quotes weren't replaced.
* Tested with latest WP install

= 1.0.2 =
* Unescaped some of the chars because esc_html was making the code look bad in a the syntax highlighting plugin
* Added another shortcode (plural) just in case.
* Tested with latest WP install

= 1.0.1 =
* Updated Usage text
* Fixes the usage in the readme file.
* Other

= 1.0.0 =
* Initial release