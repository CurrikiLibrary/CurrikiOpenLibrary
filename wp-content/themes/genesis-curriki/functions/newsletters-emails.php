<?php

//error_reporting(E_ALL); ini_set('display_errors', 1); 
class NewsLetterEmails {

  private static $newsletter_instance;
  private $wpdb;

  private function __construct() {
    global $wpdb;
    $this->wpdb = $wpdb;
    add_action('transition_post_status', array($this, 'newsLetterStatus'), 10, 3);
    add_action('populate_newsletter_tables', array($this, 'populateNewsletterTables'), 10, 3);
    add_action('send_email_newsletters', array($this, 'sendEmailNewsletters'), 10, 3);
  }

  public static function getInstance() {
    if (!self::$newsletter_instance) {
      self::$newsletter_instance = new NewsLetterEmails();
    }

    return self::$newsletter_instance;
  }

  public function newsLetterStatus($new_status, $old_status, $post) {
    if ($new_status == 'publish' && $old_status != 'publish') {
      //adding post status so that cron will pick later
      update_post_meta($post->ID, 'post_newsletter_status', 'In Queue');
    }
  }

  public function populateNewsletterTables() {
    $args = array(
        'meta_query' => array(
            array(
                'key' => 'post_newsletter_status',
                'value' => 'In Queue',
                'compare' => '=',
            )
        )
    );
    $the_query = new WP_Query($args);

    $home_url = home_url( '/' );

    if ($the_query->have_posts()) {
      
      while ($the_query->have_posts()) {
        $the_query->the_post();
        update_post_meta(get_the_ID(), 'post_newsletter_status', 'Done');
        $title = get_the_title();
        $content = wp_trim_words(get_the_content(), 50);
        $permalink = get_permalink();
        
        $subject = 'New Post Published - ' . $title;
        $body = <<<EOD
  <p>Hello {{NAME}},</p><p>Here is the latest blog post from Curriki.</p><h2>{$title}</h2>
  <p>{$content}...</p>
  <p>You can view it from this link : <a href='{$permalink}' target='_blank'>{$permalink}</a></p><p>Thanks!<br />Your Curriki Team</p><p>P.S. Be the first to know about new lessons, videos, teacher tips, and curriculum. <a href='{$home_url}curriki-newsletter-sign-up-2'>Sign-up to receive our newsletter.</a></p>
EOD;

        $this->wpdb->insert("newsletters_mailing_queue", array(
            "subject" => $subject,
            "body" => $body,
            "status" => "In Queue" // ... and so on)
        ));
        $mailing_queue_id = $this->wpdb->insert_id;
        $subscribers = $this->wpdb->get_results("Select * from newsletters where unsubscribed = 0");
        foreach ($subscribers as $subscriber) {
          $this->wpdb->insert("newsletters_sending_queue", array(
              "mailing_queue_id" => $mailing_queue_id,
              "contact_id" => $subscriber->newslettersid,
              "email" => $subscriber->email,
              "status" => "In Queue" // ... and so on)
          ));
        }
      }
      
      /* Restore original Post Data */
      wp_reset_postdata();
    } else {
      // no posts found
    }
    
    die('Newsletter populate');
  }
  
  public function sendEmailNewsletters(){
    $sql =<<<EOD
            SELECT *, REPLACE(REPLACE(body, '{{EMAIL}}', C.email), '{{NAME}}', C.name) as body
FROM newsletters_mailing_queue A
INNER JOIN newsletters_sending_queue B
ON A.id = B.mailing_queue_id
INNER JOIN newsletters C
ON B.contact_id = C.newslettersid
where B.status = 'In Queue' limit 10;
EOD;
    $mailing_queue = $this->wpdb->get_results($sql);
    
    foreach($mailing_queue as $queue){
      self::curriki_newsletter_mail($queue->email, $queue->subject, $queue->body);
      $this->wpdb->update('newsletters_sending_queue', [
          'status'=>'Sent'
      ], [
          'id'=>$queue->id
      ]);
    }
    echo "<pre>";
    print_r($mailing_queue);
    
  }
  public static function curriki_newsletter_mail($email = "fahad.curriki@nxvt.com", $subject, $body){
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($email, $subject, $body, $headers);
//    wp_mail($email, "You’re our newest member!", $body, $headers);
    //wp_mail('sajid.curriki@nxvt.com', "You’re our newest member!", $body, $headers);
}
  

}

$newsletter_instance = NewsLetterEmails::getInstance();
//var_dump($newsletter_instance);
//die('test');


