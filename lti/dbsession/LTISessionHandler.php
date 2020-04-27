<?php
require('DbSessionHandler.php');
class LTISessionHandler extends DbSessionHandler {
	
    protected $session_db_table = 'cur_sessions';
    protected $session_name = "CURSESSION"; 
    
    public function __construct($settings_obj) {            

        $this->pdo_data_source_name = $settings_obj->pdo_data_source_name;
        $this->pdo_username = $settings_obj->pdo_username;
        $this->pdo_password = $settings_obj->pdo_password;                    
        parent::__construct();
    }
}

?>