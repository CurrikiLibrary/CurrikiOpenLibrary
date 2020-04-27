<?php

include_once(__DIR__ . '/../stripe-php-7.5.0/init.php');

/**
 * Webhook_Controller
 * 
 * 
 * @author     Fahad Farrukh <fahad.curriki@nxvt.com>
 */

class Webhook_Controller extends WP_REST_Controller
{
    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {
        $namespace = 'genesis-curriki/v1';
        $path = 'webhooks/stripe';

        register_rest_route($namespace, '/' . $path, [
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'process_stripe_webhook')
            )
        ]);
    }

    /**
     * Create one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function process_stripe_webhook($request)
    {

        // \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

        // print_r(\Stripe\Charge::all(['limit' => 300]));die();

        /*
        ob_start();
        
echo 'testings';
*/

        // header('Content-Type: application/json');

        // \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

        // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //     $input = file_get_contents('php://input');
        //     $body = json_decode($input);	
        // }

        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     http_response_code(400);
        //     echo json_encode([ 'error' => 'Invalid request.' ]);
        //     exit;
        // }

        // $event = null;

        // try {
        //     // Make sure the event is coming from Stripe by checking the signature header
        //     $event = \Stripe\Webhook::constructEvent($input, $_SERVER['HTTP_STRIPE_SIGNATURE'], STRIPE_WEBHOOK_SECRET);
        // }
        // catch (Exception $e) {
        //     http_response_code(403);
        //     echo json_encode([ 'error' => $e->getMessage() ]);
        //     exit;
        // }

        // $details = '';

        // $type = $event['type'];

        // $object = $event['data']['object'];

        // if($type == 'checkout.session.completed') {
        //   error_log('Checkout Session was completed!');
        // } else {
        //     error_log('Other webhook received! ' . $type);
        // }

        // $output = [
        //     'status' => 'success'
        // ];

        // echo json_encode($output, JSON_PRETTY_PRINT);

/*
        $returnValue = ob_get_contents();
        ob_end_clean();

        $myfile = fopen("log.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $returnValue);
        fclose($myfile);
        */
    }
}
