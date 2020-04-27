<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<style type="text/css">
    .es-starter-gopro{
        margin: 2% auto;
        text-align: center;
        padding: 2em 0 0.5em 0;
    }
    #wpwrap{
    	background: #FFF;
    }
    .mid-or{
	    display: inline-block;
	    margin: 2em;
    }
    .button.large{
        padding: 2em;
        color: #FFF;
        border: 0;
        box-shadow: none;
        font-size: 1.5em;
        line-height: 0.1em;
    }
    .button.large:hover{
        background: #4fad43;
        color: #FFF;
        transform:scale(1.075);
    }
	.button.green{
        background: #12a200;
    }
    .button.green-light{
        background: #4fad43;
    }
    @media only screen and (max-width: 1362px) {
    	.es-starter-gopro img{
    		width: 90%;
    	}
    }
</style>
<div class="es-starter-gopro">
	<img class="ig-es-pricing-img" src="<?php echo EMAIL_SUBSCRIBERS_URL.'/admin/images/email-subscribers-pricing.png' ?>"/><br/>
	<a class="button large green-light" href="https://www.icegram.com/?buy-now=409349&qty=1&page=6&with-cart=0&utm_source=in_app&utm_medium=go_pro_monthly&utm_campaign=es_upsale" target="_blank"> <?php _e('Get Started @ $9/month', 'email-subscribers') ?></a>
	<div class="mid-or">OR</div> 
	<a class="button large green"  href="https://www.icegram.com/?buy-now=407190&qty=1&page=6&with-cart=0&utm_source=in_app&utm_medium=go_pro_yearly&utm_campaign=es_upsale" target="_blank"> <?php _e('Get Started @ $49/year', 'email-subscribers') ?></a>
</div>


