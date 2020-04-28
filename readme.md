
Enterprise-Level Collaboration System for Managing Curricula

This project contains implimentation of the Curriki library.

Key features:

- Library of OER (Open Educational Resources) 
- LTI to provide integrations with LMSs (Learning Management Systems)
- Reporting and Analytics 
- API for custom interface implimentation.

Installment : 



- Clone repository

- Import Database
	-  Import the open_curriki.sql.gz file provided in data folder

- Setup AWS
	- Configure AWS, CloudSearch, S3, Elastic Transcoder
	- Under DBI_AWS_S3_BUCKET, require following folders
	- (avatars, blobs, community_pages, linkimages, miscellaneous, posters, resourcedocs, resourcefiles, resourceimgs, resourceswfs, uploads, videos)

- Configuration Settings
	- Rename wp-config-sample.php as wp-config.php
	- Update the wp-config.php file with the settings (Curriki Database, AWS, Stripe, Google Recaptcha credentials)

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



	- Run below commands
		- cd curriki-xapi-app/app/Curriki/curriki-lti/
		- run composer install
	- Update credentials in curriki-xapi-app/app/Curriki/curriki-lti/config.php

- Vhost Configuration
	- Create vhost for curriki.loc
