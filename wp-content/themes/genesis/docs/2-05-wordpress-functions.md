---
title: WordPress Theme Functions
menuTitle: WordPress Functions
layout: layouts/base.njk
permalink: basics/wordpress-functions/index.html
tags: docs
---

Genesis uses the following WordPress theme functions:

- <a href="https://make.wordpress.org/themes/2019/03/29/addition-of-new-wp_body_open-hook/"><code>wp_body_open()</code></a> (requires Genesis 2.10 and WordPress 5.2)
- <a href="https://developer.wordpress.org/reference/functions/wp_head"><code>wp_head()</code></a> (immediately before `</head>`)
- <a href="https://developer.wordpress.org/reference/functions/body_class"><code>body_class()</code></a> (inside `<body>` tag)
- <a href="https://developer.wordpress.org/reference/functions/post_class"><code>post_class()</code></a>
- <a href="https://developer.wordpress.org/reference/functions/wp_footer"><code>wp_footer()</code></a> (immediately before `</body>`)

You do not need to include these functions in your Genesis child theme.

You can make use of actions and filters related to those functions in your plugin and theme code. For example:

```php
add_filter( 'body_class', 'theme_prefix_landing_body_class' );
/**
 * Adds landing page body class to the landing page template.
 *
 * @since 1.0.0
 *
 * @param array $classes Original body classes.
 * @return array Modified body classes.
 */
function theme_prefix_landing_body_class( $classes ) {

	$classes[] = 'landing-page';
	return $classes;

}
```
