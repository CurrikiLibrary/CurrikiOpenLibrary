---
title: Genesis Onboarding
menuTitle: Onboarding
layout: layouts/base.njk
permalink: developer-features/onboarding/index.html
minVersion: Genesis 2.9.0+ and WordPress 5.0.0+
tags: docs
---

## Onboarding is a theme setup wizard for Genesis child themes

Genesis Onboarding reduces frustrating manual theme setup by offering to automate parts of the theme setup process when a Genesis child theme is activated.

When activating a Genesis child theme with Onboarding support, users are redirected to a theme setup page:

<img src="{{ '/img/onboarding.png' | url }}" alt="Onboarding screen showing the Create Your New Homepage prompt.">

## Actions theme developers can take during Onboarding

Your child theme's `onboarding.php` config file determines what happens during the Onboarding process. You can:

- **Specify plugins to install and activate** upon theme activation.
- **Create a new page, set it as the site's static homepage, and populate it with demo content**, including layouts for the block editor.
- **Create additional pages** if desired, such as a Contact and About page, each with their own content.

## Add Onboarding to your Genesis child theme

Add an `onboarding.php` file to your Genesis child theme's `config` folder.

<p class="notice-small">
Create the <code>config</code> folder in the root of your child theme (at the same level as <code>style.css</code>) if it does not exist already.
</p>

The `onboarding.php` file must return an array with a specific structure. Check out the `onboarding.php` file from the Genesis Sample theme as an example:

```php
<?php
/**
 * Genesis Sample.
 *
 * Onboarding config to load plugins and homepage content on theme activation.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */
return array(
	'dependencies'     => array(
		'plugins' => array(
			array(
				'name'       => __( 'Atomic Blocks', 'genesis-sample' ),
				'slug'       => 'atomic-blocks/atomicblocks.php',
				'public_url' => 'https://atomicblocks.com/',
			),
			array(
				'name'       => __( 'WPForms Lite', 'genesis-sample' ),
				'slug'       => 'wpforms-lite/wpforms.php',
				'public_url' => 'https://wordpress.org/plugins/wpforms-lite/',
			),
			array(
				'name'       => __( 'Genesis eNews Extended', 'genesis-sample' ),
				'slug'       => 'genesis-enews-extended/plugin.php',
				'public_url' => 'https://wordpress.org/plugins/genesis-enews-extended/',
			),
			array(
				'name'       => __( 'Simple Social Icons', 'genesis-sample' ),
				'slug'       => 'simple-social-icons/simple-social-icons.php',
				'public_url' => 'https://wordpress.org/plugins/simple-social-icons/',
			),
		),
	),
	'content'          => array(
		'homepage' => array(
			'post_title'     => 'Homepage',
			'post_content'   => require dirname( __FILE__ ) . '/import/content/homepage.php',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'page_template'  => 'page-templates/blocks.php',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
		'blocks'   => array(
			'post_title'     => 'Block Content Examples',
			'post_content'   => require dirname( __FILE__ ) . '/import/content/block-examples.php',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'page_template'  => 'page-templates/blocks.php',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
		'about'    => array(
			'post_title'     => 'About Us',
			'post_content'   => require dirname( __FILE__ ) . '/import/content/about.php',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'page_template'  => 'page-templates/blocks.php',
			'featured_image' => get_stylesheet_directory_uri() . '/config/import/images/about.jpg',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
		'contact'  => array(
			'post_title'     => 'Contact Us',
			'post_content'   => require dirname( __FILE__ ) . '/import/content/contact.php',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'featured_image' => get_stylesheet_directory() . '/config/import/images/contact.jpg',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
		'landing'  => array(
			'post_title'     => 'Landing Page',
			'post_content'   => require dirname( __FILE__ ) . '/import/content/landing-page.php',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'page_template'  => 'page-templates/landing.php',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
	),
	'navigation_menus' => array(
		'primary' => array(
			'homepage' => array(
				'title' => 'Home',
			),
			'about'    => array(
				'title' => 'About Us',
			),
			'contact'  => array(
				'title' => 'Contact Us',
			),
			'blocks'   => array(
				'title' => 'Block Examples',
			),
			'landing'  => array(
				'title' => 'Landing Page',
			),
		),
	),
);
```
### Onboarding config details

- **The plugin `slug` is the basename for the plugin's main PHP file.** This is the plugin folder name (if the plugin has a folder), a forward slash, and then the name of the PHP file containing the plugin header. It's the value returned by adding `var_dump( plugin_basename( __FILE__ ) );` to the PHP file that contains the plugin header.
- **The `content` array contains one or more posts and pages**. You can use any unique value for the array keys. The special 'homepage' key tells Genesis that the imported page should be set as the site's static homepage.
- **The `page_template` key and value can be omitted** if you do not wish to use a page template for a given page.
- **The value of `post_content` should be a string containing the raw HTML of the page content you wish to import**, obtained from viewing the Text or Source of your page content in the WordPress editor. As this string is likely to be long, we recommend storing it in a separate file as [described below](#using-a-separate-file-for-your-post_content).
- **Specifying a plugin as a dependency will cause it to be installed and activated.** If your theme uses plugin-specific code you should still check for plugin classes and functions before using them, because users might skip the Onboarding process. You can use the [`class_exists()`](http://php.net/manual/en/function.class-exists.php) or [`function_exists()`](http://php.net/manual/en/function.function-exists.php) functions for this.
- **You can use the `onboarding.php` config file to set up a default navigation menu.** By specifying the `navigation_menus` key with an array of values, you can create and assign menus to a menu location. In the example above, the Genesis Sample theme is setting up a navigation menu that is to be assigned to the `primary` menu location. Each item in the menu is set up using the slug from a page that is imported in the `content` array.
- **To set a manual excerpt**, add a `post_excerpt` key to any content item array.
- **To add post meta to posts and pages**, set a `meta_input key`. For example: `'meta_input' => array( '_genesis_layout' => 'sidebar-content' ),`

### Using a separate file for your `post_content`

We recommend storing page content you wish to import in a separate file.

Instead of including a long string in your `onboarding.php` config, like this:

```php
'post_content' => '<!-- wp:paragraph --><p>This is a simple paragraph block, but your imported content can contain much more exciting content than this.</p><!-- /wp:paragraph -->';
```

You can require a file that returns the string instead, like this:

```php
'post_content' => require dirname( __FILE__ ) . '/homepage.php',
```

This `homepage.php` file can be named as you wish. It can live in your `config` directory, but you may wish to place it in a subdirectory such as `config/import/`:

```php
'post_content' => require dirname( __FILE__ ) . '/import/homepage.php',
```

The `homepage.php` file must return a string containing the content you wish to import:

```php
<?php
/**
 * Your Theme Name
 *
 * Homepage content optionally installed after theme activation.
 *
 * @package Theme Name
 * @author  Your Name
 * @license GPL-2.0-or-later
 * @link    https://example.com/
 */

$theme_name_homepage_content = <<<CONTENT
<!-- wp:paragraph -->
<p>This is a simple paragraph block, but your imported content can contain much more exciting content than this.</p>
<!-- /wp:paragraph -->
CONTENT;

return $theme_name_homepage_content;
```

Note that the string is returned on the final line. If you omit the return statement, no content will be imported during Onboarding and your pages will be blank.

Your raw page content should be copied from the code editor. The code editor is accessible from the menu in the top-right of the block editor:

<img src="{{ '/img/code-editor.png' | url }}" alt="The menu from the block editor showing how to access the code editor mode.">

<p class="notice-small">
The opening <code>&lt;&lt;&lt;CONTENT</code> and closing <code>CONTENT;</code> string delimiters in the <code>homepage.php</code> code snippet are a PHP feature called the <a href="http://php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc">heredoc syntax</a>.
<br><br>The heredoc syntax is an alternative to wrapping multi-line strings with quote marks. It prevents you from having to escape single or double quotes within your string. PHP will also process internal variables such as <code>$my_var</code> or <code>{$my_array['key']}</code>.
<br><br>You can replace the <code>CONTENT</code> identifier with your own delimiter if you wish, as long as you use the same for the starting and ending one. The line with the ending <code>CONTENT;</code> delimiter must contain no other characters aside from the identifier and the semicolon, and no white space at the start of the line.
</p>

Each page or post you create can import the same sample content, or you can create a separate file with different content for each page, then update the `post_content` value for each imported page to point to that file.

### To test the onboarding process

1. Activate another theme from the Appearance → Themes page.
2. Activate your theme. You should be redirected to the onboarding page.
3. Click “Set up your homepage” and wait for the setup steps to complete.
4. Click “View your homepage” or “Edit your homepage” to see the imported homepage content.

You can repeat the Onboarding process by leaving your theme active and visiting `/wp-admin/admin.php?page=genesis-getting-started` instead of deactivating and reactivating your theme. Note that new pages will be created each time. Pages are not deleted or overwritten during Onboarding.

### Running code before and after importing content

<p class="notice">
Requires Genesis 2.10.0+.
</p>

To run code before content is imported during onboarding, use the `genesis_onboarding_before_import_content` action:

```php
add_action( 'genesis_onboarding_before_import_content', 'theme_prefix_onboarding_before_import_content' );
/**
 * Runs code before content is imported during onboarding.
 *
 * @since 1.0.0
 *
 * @param array $content The content data from the `onboarding.php` file.
 */
function theme_prefix_onboarding_before_import_content( $content ) {

	// Code you would like to run before content is imported.

}
```

To run code after content is imported during onboarding, use the `genesis_onboarding_after_import_content` action:

```php
add_action( 'genesis_onboarding_after_import_content', 'theme_prefix_onboarding_after_import_content', 10, 2 );
/**
 * Runs code after content is imported during onboarding.
 *
 * @since 1.0.0
 *
 * @param array $content The content data from the `onboarding.php` file.
 * @param array $imported_post_ids Content keys and created post IDs. Example: `[ "homepage" => 123 ]`.
 */
function theme_prefix_onboarding_after_import_content( $content, $imported_post_ids ) {

	// Code you would like to run after content is imported.

}
```

This code should live outside your `onboarding.php` file.

### Onboarding order

The order of operation for the onboarding process is:

1. Dependencies (plugins) are installed.
2. Actions hooked to `genesis_onboarding_before_import_content` run.
3. Content is imported.
4. Actions hooked to `genesis_onboarding_after_import_content` run. 
5. Menu items are set and assigned to menu areas.

## Points to note

<p class="notice">
The Onboarding feature is new and will continue to improve, but there are some things to be aware of at present.
</p>

1. **Onboarding requires WordPress 5.0.0+ and Genesis 2.9.0+**. The onboarding config file has no effect if both of these requirements are not met.
2. **Widgets and media cannot yet be imported.**
3. **The redirect to the onboarding page occurs when themes are activated via the Appearance → Themes screen only**. A redirect will not occur when activating themes via the Customizer or WP-CLI. You are welcome to direct people to `/wp-admin/admin.php?page=genesis-getting-started` to complete the theme onboarding process in your support documentation or elsewhere. Running the setup process multiple times will create additional pages, but is otherwise not destructive.
4. **The text used on the onboarding admin screen is not currently filterable,** including the “Create your new homepage” title. We expect to add the ability to change this via your config file in the future.
5. **Only plugins from the [WordPress.org plugins repository](https://wordpress.org/plugins/) are currently supported** as dependencies.
6. **Be aware that Blocks displaying posts can not be guaranteed to display in the same way as your demo content**. It's not safe to assume that all of a site's posts have featured images set, for example, or that a site contains any posts at all.

If you need to use images in your imported page content, for now you can use [this workaround from the Genesis Sample theme](https://github.com/studiopress/genesis-sample/blob/412a59ec37e143c87fc7d3349d29df1bf096c1fc/config/import/content/homepage.php#L13-L27) where demo images are stored in the child theme folder and linked directly in the imported content.

We encourage you to experiment with Onboarding, and we welcome your <a href="{{ '/contribute/#general-feedback' | url }}">feedback</a>.
