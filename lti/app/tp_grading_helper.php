<?php
use IMSGlobal\LTI\ToolProvider;
use IMSGlobal\LTI\ToolProvider\DataConnector;

class TPGradingHelper 
{
    public static function replaceResult($db,$session)
    {
        $user_resource_pk = NULL;
            
        $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
        $consumer = ToolProvider\ToolConsumer::fromRecordId($session['consumer_pk'], $data_connector);
        $resource_link = ToolProvider\ResourceLink::fromRecordId($session['resource_pk'], $data_connector);

        $users = $resource_link->getUserResultSourcedIDs();

        foreach ($users as $user) 
        {                
            $resource_pk = $user->getResourceLink()->getRecordId();
            $user_pk = $user->getRecordId();

            if($user_pk === $session["user_pk"])
            {             
                    $update = is_null($user_resource_pk) || is_null($user_user_pk) || (($user_resource_pk === $resource_pk) && ($user_user_pk === $user_pk));
                    if($update)
                    {                                    
                        $c_n = strval($_POST["ratevalue"]);
                        $lti_outcome = new ToolProvider\Outcome(strval($c_n));
                        //var_dump($lti_outcome);
                        $rtn = $resource_link->doOutcomesService(ToolProvider\ResourceLink::EXT_WRITE, $lti_outcome, $user);
                    }
            }

        }
    }
    
    public static function readResult($db,$session) 
    {
        $outcome = false;
        $user_resource_pk = NULL;            
        $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
        $consumer = ToolProvider\ToolConsumer::fromRecordId($session['consumer_pk'], $data_connector);
        $resource_link = ToolProvider\ResourceLink::fromRecordId($session['resource_pk'], $data_connector);


        $num = getVisibleItemsCount($db, $session['resource_pk']);
        $ratings = getVisibleRatingsCounts($db, $session['resource_pk']);
        $users = $resource_link->getUserResultSourcedIDs();

        foreach ($users as $user) 
        {                            
            $user_pk = $user->getRecordId();
            if($user_pk === $session["user_pk"])
            {                    
                $lti_outcome = new ToolProvider\Outcome();                
                $resource_link->doOutcomesService(ToolProvider\ResourceLink::EXT_READ, $lti_outcome, $user);                        
                $outcome = $lti_outcome->getValue();  
                $outcome = strlen($outcome) === 0 ? false : $outcome;
            }
        }
        return $outcome;
    }
    public static function deleteResult($db,$session) 
    {
        $outcome = false;
        $user_resource_pk = NULL;            
        $data_connector = DataConnector\DataConnector::getDataConnector(DB_TABLENAME_PREFIX, $db);
        $consumer = ToolProvider\ToolConsumer::fromRecordId($session['consumer_pk'], $data_connector);
        $resource_link = ToolProvider\ResourceLink::fromRecordId($session['resource_pk'], $data_connector);


        $num = getVisibleItemsCount($db, $session['resource_pk']);
        $ratings = getVisibleRatingsCounts($db, $session['resource_pk']);
        $users = $resource_link->getUserResultSourcedIDs();

        foreach ($users as $user) 
        {                            
            $user_pk = $user->getRecordId();
            if($user_pk === $session["user_pk"])
            {                    
                $lti_outcome = new ToolProvider\Outcome();                
                $resource_link->doOutcomesService(ToolProvider\ResourceLink::EXT_DELETE, $lti_outcome, $user);                        
                $outcome = $lti_outcome->getValue();    
            }
        }
        return $outcome;
    }
}
