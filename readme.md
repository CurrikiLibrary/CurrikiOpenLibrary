
Enterprise-Level Collaboration System for Managing Curricula

This project contains implimentation of the Curriki library.

**Key features:**

- Library of OER (Open Educational Resources) 
- LTI to provide integrations with LMSs (Learning Management Systems)
- Reporting and Analytics 
- API for custom interface implimentation.

**Installation:**



- **Clone** **Repository**

- **Database** **Configuration**
    - Import the open_curriki.sql.zip file provided in data folder
    - Update the definer of all triggers with [db_user_name@host](mailto:db_user_name@host). For reference see the below link.
        
            https://stackoverflow.com/questions/18593746/how-to-bulk-change-mysql-triggers-definer

- **Setup AWS**
	- Configure [AWS](https://aws.amazon.com/), 
    [CloudSearch](https://aws.amazon.com/cloudsearch/getting-started/), 
    [S3](https://aws.amazon.com/s3/getting-started/?nc=sn&loc=5), 
    [Elastic Transcoder](https://aws.amazon.com/elastictranscoder/getting-started/)
	- Under DBI_AWS_S3_BUCKET, require following folders
	- (avatars, blobs, community_pages, linkimages, miscellaneous, posters, resourcedocs, resourcefiles, resourceimgs, resourceswfs, uploads, videos)

- **Configuration Settings**
	- Rename wp-config-sample.php as wp-config.php
	- Update the wp-config.php file with the settings (Curriki Database, AWS, Stripe, Google Recaptcha credentials)

		```
		DB_NAME

		DB_USER

		DB_PASSWORD

		DB_HOST

		STRIPE_SECRET_KEY

		STRIPE_PUBLISHABLE_KEY

		STRIPE_WEBHOOK_SECRET

		STRIPE_CURRENCY

		JWT_AUTH_SECRET_KEY

		DBI_AWS_ACCESS_KEY_ID

		DBI_AWS_SECRET_ACCESS_KEY

		DBI_AWS_REGION

		DBI_AWS_S3_BUCKET

		DBI_AWS_S3_CDN_URL

		DBI_AWS_TRANSCODE_PIPELINE_ID

		AWS_CLOUDSEARCH_END_POINT

		GOOGLE_RECAPTCHA_SITE_KEY

		GOOGLE_RECAPTCHA_SECRET_KEY
		```

	- Run below commands

		```
		cd /PROJECT-ROOT/curriki-xapi-app/app/Curriki/curriki-lti/
		composer install
        cp /PROJECT-ROOT/data/config.php /PROJECT-ROOT/curriki-xapi-app/app/Curriki/curriki-lti
		```
	- Update credentials in curriki-xapi-app/app/Curriki/curriki-lti/config.php
		```
        WP_LTI_APP_URL
        WP_LTI_DB_DRIVER
        WP_LTI_DB_HOST
        WP_LTI_DB_USER
        WP_LTI_DB_PASSWORD
        WP_LTI_DB_NAME
        WP_LTI_DB_TABLE_PREFIX
		```

- **Vhost Configuration**
    - Create and enable vhost.
    - Restart the server
    - Update the hosts file entry.

- **Test Credentials**
    - user=root
    - pass=123456

- **Plugins**
    - Advanced Custom Fields
    - Akismet Anti-Spam
    - All In One SEO Pack
    - Amazon Web Services
    - Analytify - Google Analytics Dashboard
    - Autoptimize
    - bbPress
    - Better RSS Widget
    - BuddyPress
    - BuddyPress Follow
    - GDPR
    - Genesis Connect for BuddyPress
    - Google XML Sitemaps
    - Gravity Forms
    - Invite Anyone
    - Jetpack by WordPress.com
    - Landing pages leads analytics SEO content
    - Share Buttons by AddThis
    - Social Login
    - SSH SFTP Updater Support
    - TablePress
    - WooCommerce
    - WordPress Importer
    - WP GDPR Compliance
    - WP Landing Pages
    - WP Mail SMTP
    - WP Offload Media Lite

- **Themes**
    - Genesis Framework