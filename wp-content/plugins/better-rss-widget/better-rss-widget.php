<?php

/*
  Plugin Name: Better RSS Widget
  Plugin URI: http://grandslambert.net/plugins/better-rss-widget
  Description: Replacement for the built in RSS widget that adds an optional link target, shortcode_handler, and page conditionals.
  Author: grandslambert
  Version: 2.7.1
  Author URI: http://grandslambert.net/

 * *************************************************************************

  Copyright (C) 2009-2016 GrandSlambert

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

 * *************************************************************************

 */

/* Class Declaration */

class better_rss_widget extends WP_Widget {

    var $version = '2.7.1';

    /* Plugin settings */
    var $optionsName = 'better-rss-widget-options';
    var $menuName = 'better-rss-widget-settings';
    var $pluginName = 'Better RSS Widget';
    var $options = array();
    var $make_link = false;

    /**
     * Plugin Constructor Method
     */
    function better_rss_widget() {
        add_action('init', array($this, 'init'));

        /* Set the plugin name to use the selected language. */
        $this->pluginName = __('Better RSS Widget', 'better-rss-widget');

        $widget_ops = array('description' => __('Replaces the built in RSS Widget adding more options. By GrandSlambert.', 'better-rss-widget'));
        $control_ops = array('width' => 500, 'height' => 350);
        parent::WP_Widget(false, $this->pluginName, $widget_ops, $control_ops);

        /* Plugin paths */
        $this->pluginPath = WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__));
        $this->pluginURL = WP_PLUGIN_URL . '/' . basename(dirname(__FILE__));

        /* Load the plugin settings */
        $this->load_settings();

        /* WordPress Actions */
        add_action('admin_menu', array(&$this, 'admin_menu'));
        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('update_option_' . $this->optionsName, array(&$this, 'update_option'), 10);

        /* WordPress FIlters */
        add_filter('plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2);

        /* Add shortcode_handlers */
        add_shortcode('better-rss', array($this, 'shortcode_handler'));
    }

    /**
     * Load the plugin settings.
     */
    function load_settings() {
        $options = get_option($this->optionsName);

        $defaults = array(
            'link_target' => '_blank',
            'allow_intro' => (is_array($options)) ? isset($options['allow_intro']) : true,
            'show_summary' => false,
            'show_author' => false,
            'show_date' => false,
            'show_time' => false,
            'nofollow' => false,
            'enable_cache' => (is_array($options)) ? isset($options['enable_cache']) : true,
            'cache_duration' => 3600,
            'items' => 10,
            'title_length' => (is_array($options) && !empty($options['title_length'])) ? isset($options['title_length']) : 0,
            'excerpt' => 360,
            'suffix' => ' [&hellip;]'
        );

        $this->options = (object) wp_parse_args($options, $defaults);
    }

    /**
     * Load the language file during WordPress init.
     */
    function init() {
        /* Load Langague Files */
        $langDir = dirname(plugin_basename(__FILE__)) . '/lang';
        load_plugin_textdomain('better-rss-widget', false, $langDir, $langDir);
    }

    /**
     * Add the admin page for the settings panel.
     *
     * @global string $wp_version
     */
    function admin_menu() {
        $page = add_options_page($this->pluginName . __(' Settings', 'better-rss-widget'), $this->pluginName, 'manage_options', $this->menuName, array(&$this, 'options_panel'));

        add_action('admin_print_styles-' . $page, array(&$this, 'admin_print_styles'));
        add_action('admin_print_scripts-' . $page, array(&$this, 'admin_print_scripts'));
    }

    /**
     * Register the options for Wordpress MU Support
     */
    function admin_init() {
        register_setting($this->optionsName, $this->optionsName);
        wp_register_style('better-rss-widget-admin-css', $this->pluginURL . '/includes/better-rss-widget-admin.css');
        wp_register_script('better-rss-widget-js', $this->pluginURL . '/js/better-rss-widget.js');
    }

    /**
     * Print the administration styles.
     */
    function admin_print_styles() {
        wp_enqueue_style('better-rss-widget-admin-css');
    }

    /**
     * Print the scripts needed for the admin.
     */
    function admin_print_scripts() {
        wp_enqueue_script('better-rss-widget-js');
    }

    /**
     * Add a configuration link to the plugins list.
     *
     * @staticvar object $this_plugin
     * @param array $links
     * @param array $file
     * @return array
     */
    function plugin_action_links($links, $file) {
        static $this_plugin;

        if (!$this_plugin) {
            $this_plugin = plugin_basename(__FILE__);
        }

        if ($file == $this_plugin) {
            $settings_link = '<a href="' . admin_url('options-general.php?page=' . $this->menuName) . '">' . __('Settings', 'better-rss-widget') . '</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    /**
     * Check on update option to see if we need to reset the options.
     * @param <array> $input
     * @return <boolean>
     */
    function update_option($input) {
        if ($_REQUEST['confirm-reset-options']) {
            delete_option($this->optionsName);
            wp_redirect(admin_url('options-general.php?page=' . $this->menuName . '&tab=' . $_POST['active_tab'] . '&reset=true'));
            exit();
        } else {
            wp_redirect(admin_url('options-general.php?page=' . $this->menuName . '&tab=' . $_POST['active_tab'] . '&updated=true'));
            exit();
        }
    }

    /**
     * Settings management panel.
     */
    function options_panel() {
        include($this->pluginPath . '/includes/settings.php');
    }

    /**
     * Method to create the widget.
     *
     * @param array $args
     * @param array $instance
     * @return false
     */
    function widget($args, $instance) {
        $instance = $this->defaults($instance);

        if (isset($instance['error']) && $instance['error'])
            return;

        extract($args, EXTR_SKIP);

        $url = $instance['rss_url'];
        while (stristr($url, 'http') != $url)
            $url = substr($url, 1);

        if (empty($url)) {
            return;
        }

        $rss = fetch_feed($url);
        $desc = '';
        $link = '';

        if (!is_wp_error($rss)) {
            $desc = esc_attr(strip_tags(@html_entity_decode($rss->get_description(), ENT_QUOTES, get_option('blog_charset'))));
            if (empty($instance['title'])) {
                $instance['title'] = esc_html(strip_tags($rss->get_title()));
            }
            $link = esc_url(strip_tags($rss->get_permalink()));
            while (stristr($link, 'http') != $link) {
                $link = substr($link, 1);
            }
        }

        if (empty($instance['title'])) {
            $instance['title'] = empty($desc) ? __('Unknown Feed', 'better-rss-widget') : $desc;
        }

        $instance['title'] = apply_filters('widget_title', $instance['title']);
        $url = esc_url(strip_tags($url));
        $icon = includes_url('images/rss.png');

        if ($instance['title_url']) {
            $url = $link = $instance['title_url'];
        }

        $target = '';

        if ($instance['link_target'] != 'none') {
            $target = 'target="' . $instance['link_target'] . '"';
        }

        if ($instance['title']) {
            if (!$instance['no_link_title']) {
                $instance['title'] = '<a class="rsswidget" href="' . $link . '" title="' . $desc . '" ' . $target . '>' . $instance['title'] . '</a>';
            }

            if ($instance['show_icon']) {
                $instance['title'] = "<a class='rsswidget' href='" . $instance[$instance['link_icon']] . "' title='" . esc_attr(__('Syndicate this content', 'better-rss-widget')) . "' . $target . '><img style='background:orange;color:white;border:none;' width='14' height='14' src='$icon' alt='RSS' /></a> " . $instance['title'];
            }
        }

        print $before_widget;
        if ($instance['title']) {
            print $before_title . $instance['title'] . $after_title;
        }

        if (true == $this->options->allow_intro && !empty($instance['intro_text'])) {
            print '<div class="better-rss-intro-text">' . $instance['intro_text'] . '</div>';
        }

        $this->rss_output($rss, $instance);
        print $after_widget;
    }

    /**
     * Method to output the RSS for the widget and shortcode_handler
     *
     * @param string $rss
     * @param array $args
     * @return blank
     */
    function rss_output($rss, $args = array()) {
        if (is_string($rss)) {
            $rss = fetch_feed($rss);
        } elseif (is_array($rss) && isset($rss['url'])) {
            $args = $rss;
            $rss = fetch_feed($rss['url']);
        } elseif (!is_object($rss)) {
            return;
        }

        if (is_wp_error($rss)) {
            if (is_admin() || current_user_can('manage_options')) {
                print '<p>' . sprintf(__('<strong>RSS Error</strong>: %s', 'better-rss-widget'), $rss->get_error_message()) . '</p>';
            }

            return;
        }

        $args = wp_parse_args($args, $this->defaults_args());
        extract($args, EXTR_SKIP);

        $items = (int) $items;
        if ($items < 1 || 20 < $items) {
            $items = 10;
        }
        $show_summary = (int) $show_summary;
        $show_author = (int) $show_author;
        $show_date = (int) $show_date;

        // Set the cache duration
        $rss->enable_cache($enable_cache);
        $rss->set_cache_duration($cache_duration);
        $rss->init();

        if (!$rss->get_item_quantity()) {
            print '<ul><li>' . __('An error has occurred; the feed is probably down. Try again later.', 'better-rss-widget') . '</li></ul>';
            return;
        }

        if (strtolower($link_target) != 'none') {
            $target = 'target="' . $link_target . '"';
        } else {
            $target = '';
        }

        print '<' . $args['html_parent'] . '>';
        
        foreach ($rss->get_items(0, $items) as $item) {
            $link = $item->get_link();
            while (stristr($link, 'http') != $link) {
                $link = substr($link, 1);
            }
            $link = esc_url(strip_tags($link));
            $title = esc_attr(strip_tags($item->get_title()));
            
            if (empty($title)) {
                $title = __('Untitled', 'better-rss-widget');
            }

            $desc = str_replace(array("\n", "\r"), ' ', esc_attr(strip_tags(@html_entity_decode($item->get_description(), ENT_QUOTES, get_option('blog_charset')))));

            if (!$hide_title) {
                $desc = wp_html_excerpt($desc, $excerpt) . $this->options->suffix;
                $desc = esc_html($desc);
            }

            if ($show_summary) {
                $summary = "<div class='rssSummary'>$desc</div>";
            } else {
                $summary = '';
            }

            $date = '';
            if ($show_date) {
                $date = $item->get_date();

                if ($date) {
                    if ($date_stamp = strtotime($date))
                        $date = ' <span class="rss-date">' . date_i18n(get_option('date_format'), $date_stamp) . '</span>';
                    else
                        $date = '';
                }
            }

            $time = '';
            if ($show_time) {
                $time = $item->get_date();

                if ($time) {
                    if ($date_stamp = strtotime($time)) {
                        $time = ' <span class="rss-date">' . date_i18n(get_option('time_format'), $date_stamp) . '</span>';
                    } else {
                        $time = '';
                    }
                }
            }

            $author = '';
            if ($show_author) {
                $author = $item->get_author();
                if (is_object($author)) {
                    $author = $author->get_name();
                    $author = ' <cite>' . esc_html(strip_tags($author)) . '</cite>';
                }
            }

            if ($hide_title && $item->get_description()) {
                $title = $item->get_description();
            }

            if (true == $args['limit_title_length'] && $args['title_length'] > 0) {
                $title = substr($title, 0, $args['title_length']);
            }
            
            if ($args['html_parent'] === 'dl') {
                $html_open = '<dt>';
                $html_after_title = '</dt>';
                $html_before_details = '<dd>';
                $html_close = '</dd>';
            } else {
                $html_open = '<' . $args['html_item'] . '>';
                $html_after_title = '';
                $html_before_details = '';
                $html_close = '</' . $args['html_item'] . '>';
            }

            if ($link == '' or $hide_link) {
                print $html_open . $title . $html_after_title;
                print $html_before_details . $date . $summary . $author . $html_close;
            } else {
                print $html_open . '<a ';
                
                if ($nofollow) {
                    print ' rel="nofollow" ';
                }
                
                print 'class="rsswidget" href="' . $link . '" title="' . $desc . '"' .  $target . '>' . $title . '</a>' . $html_after_title;
                print $html_before_details . $date . $time . $summary . $author . $html_close;
            }
        }
        print '</' . $args['html_parent'] . '>';
    }

    /**
     * Widget Update method
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    /**
     * Load the instance defaults.
     *
     * @param array $instance
     * @return array
     */
    function defaults($instance) {

        /* Fix any old instances to use new naming convention. */
        if (isset($instance['url'])) {
            $instance['rss-url'] = $instance['url'];
            $instance['title_url'] = $instance['titleurl'];
            $instance['show_icon'] = $instance['showicon'];
            $instance['show_summary'] = $instance['showsummary'];
            $instance['show_author'] = $instance['showauthor'];
            $instance['show_date'] = $instance['showdate'];
            $instance['show_time'] = $instance['showtime'];
            $instance['link_target'] = $instance['linktarget'];
            $instance['title_legnth'] = (isset($instance['title_length']) ? $instance['title_length'] : $this->options->title_length);
        }

        /* This is the new naming convention for the form fields */
        $new_defaults = array(
            'rss_url' => '',
            'title' => '',
            'title_url' => '',
            'no_link_title' => false,
            'show_icon' => false,
            'link_icon' => 'rss_url',
            'show_summary' => $this->options->show_summary,
            'show_author' => $this->options->show_author,
            'show_date' => $this->options->show_date,
            'show_time' => $this->options->show_time,
            'link_target' => $this->options->link_target,
            'nofollow' => $this->options->nofollow,
            'enable_cache' => $this->options->enable_cache,
            'cache_duration' => $this->options->cache_duration,
            'is_home' => $this->options->is_home_default,
            'is_front' => $this->options->is_front_default,
            'is_archive' => $this->options->is_archive_default,
            'is_search' => $this->options->is_search_default,
            'is_category' => $this->options->is_category_default,
            'is_tag' => $this->options->is_tag_default,
            'is_single' => $this->options->is_single_default,
            'is_date' => $this->options->is_date_default,
            'title_length' => $this->options->title_length,
            'excerpt' => $this->options->excerpt,
            'items' => $this->options->items
        );

        return wp_parse_args($instance, $new_defaults);
    }

    /**
     * Widget form.
     *
     * @param array $instance
     */
    function form($instance) {
        if (count($instance) < 1) {
            $instance = $this->defaults($instance);
        }
        include( $this->pluginPath . '/includes/widget-form.php');
    }

    /**
     * Method for the [better-rss] short code.
     *
     * @param array $atts
     * @return string
     */
    function shortcode_handler($atts) {
        global $post;

        $atts = (object) wp_parse_args($atts, $this->defaults_args());

        if (!$atts->feed) {
            return false;
        }

        if ($atts->use_title) {
            $add_url[] = str_replace(' ', '+', $post->post_title);
        }

        if ($atts->use_tags) {
            foreach (get_the_tags() as $tag) {
                $add_url[] = str_replace(' ', '+', $tag->name);
            }
        }

        if (isset($add_url) and is_array($add_url)) {
            $atts->feed = $atts->feed . implode('+', $add_url);
        }

        ob_start();
        $this->rss_output($atts->feed, $atts);
        $output.= ob_get_contents();
        ob_end_clean();

        return $output;
    }
    
    function defaults_args() {
        return array(
            // Query Attributes
            'feed' => NULL,
            'use_title' => false,
            'use_tags' => false,
            'use_category' => false,
            'items' => 10,
            'hide_title' => false,
            'hide_link' => false,
            'show_author' => $this->options->show_author,
            'show_date' => $this->options->show_author,
            'show_time' => $this->options->show_time,
            'show_summary' => $this->options->show_summary,
            'link_target' => $this->options->link_target,
            'nofollow' => $this->options->nofollow,
            'cache_duration' => $this->options->cache_duration,
            'excerpt' => $this->options->excerpt,
            'html_parent' => 'ul',
            'html_item' => 'li'
        );
    }

    /**
     * Get an object with all of the post types.
     *
     * @return object
     */
    function get_post_types($args = array()) {
        if (function_exists('get_post_types')) {
            $post_types = get_post_types(array('public' => true));
            unset($post_types['attachment']);
        } else {
            $post_types = array('post', 'page');
        }

        $defaults = array(
            'output' => 'object',
            'name' => 'post_type',
            'id' => 'post_type'
        );

        $args = (object) wp_parse_args($args, $defaults);

        switch ($args->output) {
            case 'dropdown':
                $results = '<select name="' . $args->name . '" id="' . $args->id . '">';
                foreach ($post_types as $post_type) {
                    $results.= '<option value="' . $post_type . '">' . $post_type . "</option>\n";
                }
                $results.= '</select>';
                break;
            default:
                $results = (object) $post_types;
        }

        return $results;
    }

    /**
     * Display the list of contributors.
     * @return boolean
     */
    function contributor_list() {
        if (function_exists('simplexml_load_file')) {
            $this->showFields = array('NAME', 'LOCATION', 'COUNTRY');
            print '<ul>';

            $items = simplexml_load_file('http://cdn.grandslambert.com/xml/better-rss-widget.xml');

            foreach ($items as $item) {
                print '<li>';
                if (empty($item->url)) {
                    print $item->name;
                } else {
                    print '<a href="' . $item->url . '" target="_blank">' . $item->name . '</a>';
                }

                if (!empty($item->location)) {
                    print ' from ' . $item->location;
                }

                if (!empty($item->country)) {
                    print ', ' . $item->country;
                }

                print ' contributed ' . $item->item . ' on ' . date('F jS, Y', strtotime($item->date)) . '</li>';
            }
            print '</ul>';
        } else {
            _e('PHP 5 Required to see a list of contributors.', 'pretty-sidebar-categories');
        }
    }

    /**
     * Displayes any data sent in textareas.
     *
     * @param <type> $input
     */
    function debug($input) {
        $contents = func_get_args();

        foreach ($contents as $content) {
            print '<textarea style="width:49%; height:250px; float: left;">';
            print_r($content);
            print '</textarea>';
        }

        echo '<div style="clear: both"></div>';
    }

}

/**
 * Add the widget code to the initialization action
 */
add_action('widgets_init', create_function('', 'return register_widget("better_rss_widget");'));
register_activation_hook(__FILE__, 'better_rss_activate');

function better_rss_activate() {
    /* Compile old options into new options Array */
    $new_options = '';
    $options = array('link_target', 'items', 'show_summary', 'show_author', 'show_date', 'show_time', 'enable_cache', 'cache_duration', 'is_home_default', 'is_front_default', 'is_archive_default', 'is_search_default', 'is_category_default', 'is_tag_default', 'is_single_default', 'is_date_default');

    foreach ($options as $option) {
        if ($old_option = get_option('better_rss_' . $option)) {
            $new_options[$option] = $old_option;
            delete_option('better_rss_' . $option);
        }
    }

    if (is_array($new_options) and ! add_option('better-rss-widget-options', $new_options)) {
        update_option('better-rss-widget-options', $new_options);
    }
}
