<?php
/*
Plugin Name: Orbisius Snippets
Plugin URI: http://orbisius.com/products/wordpress-plugins/orbisius-snippets/
Description: Simple way to include snippets
Version: 1.0.4
Author: Svetoslav Marinov (Slavi)
Author URI: http://orbisius.com
*/

/*  Copyright 2017-3000 Svetoslav Marinov (Slavi) <slavi@orbisius.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Set up plugin
add_action('init', 'orbisius_snippets_init');
add_action('admin_init', 'orbisius_snippets_admin_init');
add_action('admin_menu', 'orbisius_snippets_setup_admin');
add_action('wp_footer', 'orbisius_snippets_add_plugin_credits', 1000);

/**
 * @package Orbisius Snippets
 * @since 1.0
 * Searches through posts to see if any matches the REQUEST_URI.
 * Also searches tags
 */
function orbisius_snippets_init() {
    add_shortcode('orb_snippet', 'orbisius_snippets_process_shortcode_orb_snippet', 10, 2);
    add_shortcode('orb_snippets', 'orbisius_snippets_process_shortcode_orb_snippet', 10, 2);
}

/**
 *
 * @param type $attribs
 * @param type $content
 * @return string
 * @see https://wordpress.stackexchange.com/questions/9246/how-to-enable-custom-fields-for-pages-if-not-a-bad-practice
 */
function orbisius_snippets_process_shortcode_orb_snippet( $attribs, $content = '' ) {
    $buff = '<!-- orbisius_snippets_process_shortcode_orb_snippet_not_processed -->';
    $supported_port_types = array('post', 'page', 'product');

    if (empty($attribs['id'])) {
        return $buff;
    }

    $val = '';

    // Load a snippet from the current page/post or from another one.
    $post_id = empty($attribs['post_id']) ? get_queried_object_id() : (int) $attribs['post_id'];

    if ($post_id > 0) {
        $post_type = get_post_type($post_id);

        if (!in_array($post_type, $supported_port_types)) {
            return $buff;
        }

        $val = get_post_meta($post_id, $attribs['id'], true);

        if (!empty($val)) {
            $ctx = array(
                'wrap_tag_start' => '<pre>',
                'wrap_tag_end' => '</pre>',
            );

            $val = apply_filters('orbisius_snippets_filter_snippet_val', $val, $ctx);

            // This plugin exists
            // https://wordpress.org/plugins/syntaxhighlighter/
            if (defined('ORBISIUS_SNIPPETS_WRAP_IN_CODE') && ORBISIUS_SNIPPETS_WRAP_IN_CODE) {
                $ctx['wrap_tag_start'] = '[code]';
                $ctx['wrap_tag_end'] = '[/code]';
            } elseif (class_exists('SyntaxHighlighter')) {
                $ctx['wrap_tag_start'] = '[code]';
                $ctx['wrap_tag_end'] = '[/code]';
            }

            if (!empty($val)) {
                $partially_esc_val = esc_html($val);

                $buff = $ctx['wrap_tag_start'] . $partially_esc_val . $ctx['wrap_tag_end'];

                if (class_exists('SyntaxHighlighter')) {
                    $sh_obj = new SyntaxHighlighter();

                    if (method_exists($sh_obj, 'shortcode_hack')) {
                        $buff = $sh_obj->shortcode_hack( $buff, array( $sh_obj, 'shortcode_callback' ) );
                    }
                }

                // Is there a better & safe way to show code?
	            // Ref: https://www.w3.org/wiki/Common_HTML_entities_used_for_typography
	            $search_replace = array(
		            '&lt;' => '<',
		            '&gt;' => '>',
		            '&amp;' => '&',
		            '&#039;' => "'",
		            '&apos;' => "'",
		            '&lsquo;' => "'",
		            '&rsquo;' => "'",
		            '&#8216;' => "'",
		            '&#8217;' => "'",
		            '&quot;' => '"',
		            '&amp;quot;' => '"',
		            '&ldquo;' => '"',
		            '&rdquo;' => '"',
		            '&amp;#91' => '[',
		            '&#91;' => '[',
		            '&amp;#93' => ']',
		            '&#93' => ']',
		            '<!--?' => '?',
		            '<?php' => '&lt;?php', // php code is better displayed that way.
	            );

	            $buff = str_replace(array_keys($search_replace), array_values($search_replace), $buff);
            }
        }
    }

    return $buff;
}

/**
 * @package Orbisius Snippets
 * @since 1.0
 *
 * Searches through posts to see if any matches the REQUEST_URI.
 * Also searches tags
 */
function orbisius_snippets_admin_init() {
    $suffix = '';
    $dev = empty($_SERVER['DEV_ENV']) ? 0 : 1;
}

/**
 * Set up administration
 *
 * @package Orbisius Snippets
 * @since 0.1
 */
function orbisius_snippets_setup_admin() {
    add_options_page( 'Orbisius Snippets', 'Orbisius Snippets', 'manage_options',
        'orbisius_snippets_snippetsoptions', 'orbisius_snippets_settings_page' );

    // when plugins are show add a settings link near my plugin for a quick access to the settings page.
    add_filter('plugin_action_links', 'orbisius_snippets_add_plugin_settings_link', 10, 2);
}

// Add the ? settings link in Plugins page very good
function orbisius_snippets_add_plugin_settings_link($links, $file) {
    if ($file == plugin_basename(__FILE__)) {
        $prefix = admin_url('options_general.php?page=' . plugin_basename(__FILE__));
        $dashboard_link = "<a href=\"{$prefix}\">" . 'Settings' . '</a>';
        array_unshift($links, $dashboard_link);
    }

    return $links;
}

// Generates Options for the plugin
function orbisius_snippets_settings_page() {
    ?>
    <div class="wrap orbisius_snippets_container">
        <h2>Orbisius Snippets</h2>

        <div class="updated0"><p>
                <!--This plugin doesn't currently have any configuration options. <br/>-->
            <h3>Usage</h3>
            <pre>
To use this Orbisius plugin you need to create/edit a page/post.
Then create a <a href='https://codex.wordpress.org/Custom_Fields' target="_blank">custom field</a> (e.g. orb_snippet1) and paste the snippet into the value box.
To make the snippet show up use this shortcode in the post/page's content like you would with any other shortcode.
[orb_snippet id='orb_snippet1']
</pre>
            </p></div>

        <!--        <h2>Video Demo</h2>

        <p class="orbisius_snippets_demo_video hide00">
            <?php if (0) : ?>
                <iframe width="560" height="315" src="http://www.youtube.com/embed/BZUVq6ZTv-o" 
                        frameborder="0" allowfullscreen></iframe>
                <br/>Video Link: <a href="www.youtube.com/watch?v=BZUVq6ZTv-o"
                                    target="_blank">www.youtube.com/watch?v=BZUVq6ZTv-o</a>
            <?php else : ?>
                TODO
            <?php endif; ?>
         </p>-->

        <h2>Support & Feature Requests</h2>
        <div class="updated000"><p>
                ** NOTE: ** Support is handled on our site: <a href="http://orbisius.com/support/" target="_blank" title="[new window]">http://orbisius.com/support/</a>.
                Please do NOT use the WordPress forums or other places to seek support.
            </p></div>

        <div style="background: #ffffcc;padding:5px;">
            <h2>Free Staging Site</h2>
            <p>
                Do you have a test site that you can use to play with themes and plugin?
                No? Then try <a href="http://qsandbox.com/?utm_source=orbisius-snippets&utm_medium=settings_screen&utm_campaign=product"
                                target="_blank" title="[new window]">http://qsandbox.com</a> now and have your test
                WordPress site set up in seconds. The best part is no technical knowledge is required.
            </p>
        </div>

        <h2>Mailing List</h2>
        <p>
            Get the latest news and updates about this and future cool
            <a href="//profiles.wordpress.org/lordspace/"
               target="_blank" title="Opens a page with the pugins we developed. [New Window/Tab]">plugins we develop</a>.
        </p>
        <p>
            <!-- // MAILCHIMP SUBSCRIBE CODE \\ -->
            <a href="http://eepurl.com/guNzr" target="_blank">Subscribe to our newsletter</a>
            <!-- \\ MAILCHIMP SUBSCRIBE CODE // -->
        </p>
    </div>
    <?php
}

/**
 * Returns some plugin data such name and URL. This info is inserted as HTML
 * comment surrounding the embed code.
 * @return array
 */
function orbisius_snippets_get_plugin_data() {
    // pull only these vars
    $default_headers = array(
        'Name' => 'Plugin Name',
        'PluginURI' => 'Plugin URI',
        'Description' => 'Description',
    );

    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');

    $url = $plugin_data['PluginURI'];
    $name = $plugin_data['Name'];

    $data['name'] = $name;
    $data['url'] = $url;

    $data = array_merge($data, $plugin_data);

    return $data;
}


/**
 * adds some HTML comments in the page so people would know that this plugin powers their site.
 */
function orbisius_snippets_add_plugin_credits() {
    // pull only these vars
    $default_headers = array(
        'Name' => 'Plugin Name',
        'PluginURI' => 'Plugin URI',
    );

    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');

    $url = $plugin_data['PluginURI'];
    $name = $plugin_data['Name'];

    printf(PHP_EOL . PHP_EOL . '<!-- ' . "Powered by $name | URL: $url " . '-->' . PHP_EOL . PHP_EOL);
}
