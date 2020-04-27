<?php 
    if (! defined("FORM_APPLICATION") ) {
        define("FORM_APPLICATION", "_prefix_app_form_");
    }
	if (@session_id() == '') {
        @session_start();
    }
    class landing_page_wb {
        
        private static $titles = array(
        'title-name'    => 'Your name',
        'title-email'   => 'Your email',
        'title-message' => 'Describe your wish',
        'title-phone'   => 'Want a call?',
        'title-button'  => 'Make order',
        );

        public static $post = null;
        public static $page_set = false;

        public static function show_landin_page_form_post($post)
        {
            if (is_single()) {
                $postid = url_to_postid( 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); 
                $form_lp = '';
                if ($postid > 0 && $postid == $post->ID ) {
                    $cat = get_the_category( $post->ID );
                    if (checkCategory($cat, 'landing-page')) {
                        echo self::form_application();
                    }
                }
            }
        }

        public static function activate_plugin()
        {
            global $wpdb, $table_prefix;

            $sql = "CREATE TABLE IF NOT EXISTS `{$table_prefix}order` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `email` varchar(256) NOT NULL,
            `name` varchar(512) NOT NULL,
            `message` text NOT NULL,
            `phone` varchar(16) NOT NULL,
            `ip` varchar(64) NOT NULL,
            `user_agent` varchar(512) NOT NULL,
            `landing_page` varchar(512) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            $res = $wpdb->query( $sql );

            $cat_defaults = array(
            'cat_name' => 'landing page',
            'category_description' => 'This Category for langing page plugin',
            'category_nicename' => 'landing-page',
            'taxonomy' => 'category' );
            $my_cat_id = wp_insert_category($cat_defaults);


        }
        public static function deactivate_plugin()
        {
            $category = get_term_by('slug', 'landing-page', 'category', ARRAY_A);
            if (isset($category['term_id'])) {
                wp_delete_term( $category['term_id'], 'category' );
            }

            global $wpdb, $table_prefix;

            $sql = "DROP TABLE IF EXISTS `{$table_prefix}order`; ";
            $res = $wpdb->query( $sql );
            delete_option(FORM_APPLICATION . "title-landing-page");

        }
        public static function filter_page($params)
        {
            if($params == 'descr_landing_page') {
                $post = get_post();
                $title = get_option(FORM_APPLICATION . "title-landing-page");
                if (isset($title[$post->ID])) {
                    $res = $title[$post->ID];
                    if (isset($res['desc'])) {
                        echo $res['desc'];
                    }
                }
            }
        }

        public static function edit_form($post)
        {
            $categories = get_the_category($post->ID); 
            if ( checkCategory($categories, 'landing-page') ) {
                global $wpdb;
                $title = get_option(FORM_APPLICATION . "title-landing-page");
                if (isset($title[$post->ID])) {
                    $res = $title[$post->ID];
                    if (isset($res['img_id']) && $res['img_id'] != -1) {
                        $res_img = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE ID = {$res['img_id']}", ARRAY_A ); 
                    }
                } 

                ob_start();
                require_once (plugin_dir_path( __FILE__ ) . "template/form-edit-post.php");
                $form = ob_get_clean();
                echo $form;
            }
        }
        public static function save_post_title($id)
        {
            if (isset($_POST['title-landing-page']) && isset($_POST['text-message-landing-page']) && isset($_POST['description-landing-page'])) {
                $title = get_option(FORM_APPLICATION . "title-landing-page");
                if ($title) {
                    $title[$id]['title'] = $_POST['title-landing-page']; 
                    $title[$id]['text']  = $_POST['text-message-landing-page']; 
                    $title[$id]['desc']  = $_POST['description-landing-page']; 
                    $title[$id]['img_id']  = @$_POST['image-landing-page']; 
                    update_option(FORM_APPLICATION . "title-landing-page", $title);
                } else {
                    $title[$id]['title'] = $_POST['title-landing-page']; 
                    $title[$id]['text']  = $_POST['text-message-landing-page']; 
                    $title[$id]['desc']  = $_POST['description-landing-page']; 
                    $title[$id]['img_id']  = @$_POST['image-landing-page']; 
                    add_option(FORM_APPLICATION . "title-landing-page", $title);
                }

            }
        }

        static function setPost($obj)
        {
            self::$post = $obj;
        }

        static function print_scripts()
        {                                                                 
            wp_enqueue_style('css-landig-page', plugins_url( "/template/css/form-admin.css",  __FILE__ ) );
            wp_enqueue_script( 'js-admin-landing-page', plugins_url( "/template/js/scripts-form-app.js",  __FILE__ ) );
        }

        static function menu()
        {
            $menu_id = "1.11111111234";
            $page = add_menu_page(
            'Orders through landing pages', 
            'Orders through landing pages', 
            "read", 
            'application_form_to_admin_menu', 
            array('landing_page_wb', 'admin_page'),
            plugins_url('/img/icon.png',  __FILE__ ),
            $menu_id     
            );

        }
        static function admin_page()
        {
            global $wpdb, $table_prefix;  
            if (isset($_POST['host']) && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['port'])) {
                $array_to = array_map('trim', $_POST);
                $array_to = array_map('stripslashes', $array_to);
                $array_to = array_map('strip_tags', $array_to);
                $error = false;
                if (empty($array_to['host'])) {
                    $error = true;
                    self::setMessage("Error: field Host is empty", true);
                }
                if (empty($array_to['user'])) {
                    $error = true;
                    self::setMessage("Error: field Email is empty", true);
                }
                if (empty($array_to['pass'])) {
                    $error = true;
                    self::setMessage("Error: field Password is empty", true);
                }   
                if (empty($array_to['port'])) {
                    $error = true;
                    self::setMessage("Error: field Port is empty", true);
                }
                if ( !is_email( $array_to['user'] ) ) {
                    $error = true; 
                    self::setMessage("Mail us entered incorrect", true);
                }
                if (!$error) {
                    $smtp = get_option(FORM_APPLICATION . "setting_smpt");
                    if ($smtp) {
                        update_option( FORM_APPLICATION . "setting_smpt", $array_to );
                    } else {
                        add_option( FORM_APPLICATION . "setting_smpt", $array_to );
                    }
                }
            }
            if ( isset($_POST['text-description']) ) {
                $settings = get_option(FORM_APPLICATION . "settings");
                $settings['text-description'] = base64_encode( str_replace("\\", "", $_POST['text-description']) ); 
                if ($settings) {
                    update_option( FORM_APPLICATION . "settings", $settings );
                } else {
                    add_option( FORM_APPLICATION . "settings", $settings );
                }
            }
            if (isset($_POST['title-name']) && isset($_POST['title-email']) && isset($_POST['title-message']) && isset($_POST['title-phone'])) {
                if (!isset($settings)) {
                    $settings = get_option(FORM_APPLICATION . "settings");
                }
                $settings['title-name']    = trim(stripslashes(strip_tags($_POST['title-name'])));
                $settings['title-email']   = trim(stripslashes(strip_tags($_POST['title-email'])));
                $settings['title-message'] = trim(stripslashes(strip_tags($_POST['title-message'])));
                $settings['title-phone']   = trim(stripslashes(strip_tags($_POST['title-phone'])));
                $settings['title-button']   = trim(stripslashes(strip_tags($_POST['title-button'])));
                update_option( FORM_APPLICATION . "settings", $settings );
            }
            
            $page = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']) : 1;
            $count = $wpdb->get_var( "SELECT COUNT(*) FROM {$table_prefix}order; " );
            $pages = ceil($count / 30);
            $vars = $wpdb->get_results( "SELECT * FROM {$table_prefix}order; ", ARRAY_A );
            $smtp = get_option(FORM_APPLICATION . "setting_smpt");
            $settings = get_option(FORM_APPLICATION . "settings");
            $error = self::getMessage(true);
            $msg = self::getMessage();
            ob_start();
            require_once (plugin_dir_path( __FILE__ ) . "template/form-admin.php");
            $form = ob_get_clean();
            echo  $form;
        }


        static function form_show( $atts = false )
        {
            $sidebar = false;
            if (isset($atts['sidebar'])) {
                $sidebar = true;
            }
            if (self::$post !== null) {
                $cat = get_the_category( self::$post->ID );
                if (checkCategory($cat, 'landing-page')) {
                    return self::form_application($sidebar);
                }
            } 
            return '';
        }
        static function clear_page_application()
        {
            self::$page_set = false;
        }
        static function form_application($side_bar = false)
        {
            $msg = self::getMessage();
            $err = self::getMessage(true);
            $params = array();
            if (isset($_SESSION['params'])) {
                $params = $_SESSION['params'];
                unset($_SESSION['params']);
            }
            $titles = get_option(FORM_APPLICATION . "title-landing-page");
            if ($titles && isset($titles[self::$post->ID])) {
                $title_post = $titles[self::$post->ID];
            } else {
                $title_post['title'] = get_the_title(self::$post->ID); 
                $title_post['text'] = str_replace('%landing-page-title%', $title_post['title'], 'I want to order %landing-page-title% for...'); 
            }
            ob_start();
			if (self::$page_set === false) {
                require_once (plugin_dir_path( __FILE__ ) . "template/form-page.php");
            }
            self::$page_set = true;
            $form = ob_get_clean();
            return  $form;
        }
        public static function getDirs($dir, $folder = "", $types = array())
        {
            $dir_open = opendir($dir);
            $ret = array();
            $t = count($types);
            while( $d = readdir($dir_open)) {
                if ($d != '.' && $d != '..') {
                    if (empty($folder)) {
                        if ($t > 0) {
                            $type = substr($d, -4);
                            if(in_array($type, $types)) {
                                $ret[] = $d;
                            } 
                        } else {
                            $ret[] = $d;
                        }
                    } elseif (!empty($folder) && $d == $folder) {
                        $ret = self::getDirs($dir . '/' . $d, '', $types);
                    }
                }

            }
            return $ret;
        }
        private static function translit($text)
        {
            $ruswords = array("а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я");
            $translit = array("a","b","v","g","d","e","e","j","z","i","y","k","l","m","n","o","p","r","s","t","u","f","h","c","ch","sh","sh","","i","","e","y","ya");
            return str_replace($ruswords, $translit, $text);
        }

        private static function getIP()
        {
            if(getenv("HTTP_CLIENT_IP"))
            {
                $ip = getenv("HTTP_CLIENT_IP");
            }
            elseif(getenv("HTTP_X_FORWARDED_FOR"))
            {
                $ip = trim(getenv("HTTP_X_FORWARDED_FOR"));
                if(preg_match('/^10\./iU',$ip) || 
                preg_match('/^192\.168\./iU',$ip) || 
                preg_match('/^172\.16\./iU',$ip)||
                ('127.0.0.1'==$ip) ||
                strstr($ip,'unknown') || 
                strpos($ip, ",") !== false)
                    $ip = getenv("REMOTE_ADDR");
            }
            else
            {
                $ip = getenv("REMOTE_ADDR");
            }
            return $ip;
        }
        public static function setMessage($str, $error = false, $param = "")
        {
            if (@session_id() == '') {
                @session_start();
            }
            if ($error) {
                if (!empty($param)) {
                    $_SESSION['error'][$param] = $str;
                } else {
                    $_SESSION['error'][] = $str;  
                }
            } else {
                if (!empty($param)) {
                    $_SESSION['message'][$param] = $str;
                } else {
                    $_SESSION['message'][] = $str;  
                }
            }
        }
        public static function getMessage($error = false, $delete = true)
        {
            if (@session_id() == '') {
                @session_start();
            }
            $ret = "";
            if ($error) {
                if(isset($_SESSION['error'])) {
                    if(isset($_SESSION['error'][0])) {
                        $ret = implode("<br />", $_SESSION['error']);
                    } else {
                        $ret = $_SESSION['error']; 
                    }
                    if ($delete) {
                        unset($_SESSION['error']);
                    }
                }
            } else {
                if(isset($_SESSION['message'])) {
                    $ret = implode("<br />", $_SESSION['message']);
                    if ($delete) {
                        unset($_SESSION['message']);
                    }
                }
            }
            return $ret;
        }
        public static function send()
        {
			if (isset($_POST['create-application']) && wp_verify_nonce($_POST['create-application'], 'form-send') && isset($_POST['name']) && isset($_POST['email']) ) {
                global $wpdb, $table_prefix;  
                $name = isset($_POST['name']) ? trim( stripslashes( strip_tags( $_POST['name'] ) ) ) : '';
                $email = isset($_POST['email']) ? trim( stripslashes( strip_tags( $_POST['email'] ) ) ) : '';
                $message = isset($_POST['message']) ? trim( stripslashes( strip_tags( $_POST['message'] ) ) ) : '';
                $phone = isset($_POST['phone']) ? trim( stripslashes( strip_tags( $_POST['phone'] ) ) ) : '';
                $phone_radio = isset($_POST['phone_radio']) ? trim( stripslashes( strip_tags( $_POST['phone_radio'] ) ) ) : '';
                $error = false;
                if (empty($name)) {
                    $error = true;
                    self::setMessage("Enter your name", true, 'name');
                }
                if (empty($phone_radio)) {
                    $error = true;
                    self::setMessage("Select Communications ", true, 'phone_radio');
                }  else {
                    if ($phone_radio != 'no') {
                        if (empty($phone)) {
                            $error = true;
                            self::setMessage("Enter your contact phone", true, 'phone');
                        } else {
                            $phone = str_replace("+", "", $phone);
                            if (!preg_match("/^[0-9]+$/", $phone, $res)) {
                                $error = true;
                                self::setMessage("Phone number is invalid", true, 'phone');
                            }
                        }
                    }
                }
                if (empty($message)) {
                    $error = true;
                    self::setMessage("Enter your wishes", true, 'message');
                }
                if (empty($email)) {
                    $error = true; 
                    self::setMessage("Enter your email", true, 'email');
                } else {
                    if ( !is_email( $email ) ) {
                        $error = true; 
                        self::setMessage("The entered email is incorrect", true, 'email');
                    }
                }
                $home = get_home_url();   
				
                if ($error === false) {
                    $address = get_option('admin_email');
					$mes = '';
                    $ref = isset($_POST['_wp_http_referer']) ? urldecode($_POST['_wp_http_referer']) : "";
                    $mes .= "Landing page order \n\n\n";
                    $mes .= "Name  : $name\n\n";
                    $mes .= "Email: $email\n\n";
                    $mes .= "Message: $message\n\n";
                    if (!empty($ref)) {
                        $mes .= "Referer {$home}{$ref}\n\n";
                    }
                    if (!empty($_SERVER['REQUEST_URI'])) {
                        $mes .= "Landing page: " . urldecode( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ."\n\n";
                    }
                    $ip = self::getIP();
                    $mes .= "IP:  $ip\n\n";
                    $mes .= "User Agent:  {$_SERVER['HTTP_USER_AGENT']}\n\n";
					
                    $res = $wpdb->insert( $table_prefix . 'order',
                    array( 'email' => $email, 'name' => $name, 'message' => $message, 'ip' => $ip, 
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'landing_page' => urldecode( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), 
                    'phone' => $phone ),
                    array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
                    );
                    $smtp = get_option(FORM_APPLICATION . "setting_smpt");
                    if (@wp_mail($address, "New Order through landing page of domain:" . $_SERVER['HTTP_HOST'], $mes)) {
                        self::setMessage("Your order is accepted");
                    } else {
                        self::setMessage("Your order is not accepted, try later");
                    }
                } else {
                    $_SESSION['params']['name'] = $name; 
                    $_SESSION['params']['email'] = $email; 
                    $_SESSION['params']['message'] = $message; 
                    $_SESSION['params']['phone'] = $phone; 
                    $_SESSION['params']['phone_radio'] = $phone_radio; 
                }
				header("location: " . $_SERVER['REQUEST_URI'], false, 302);
                exit; 
            }
        }
    }
?>