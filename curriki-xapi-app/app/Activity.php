<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Activity extends Model
{
    public function lesson(){
        return $this->belongsTo('App\Lesson');
    }

    public function type(){
        return $this->belongsToMany('App\ActivityType', 'activity_type_activities', 'activity_id', 'type_id');
    }

    public function previous_activity(){
    	foreach($this->lesson->activities as $i=>$activity) { 
    		if($activity->id != $this->id)
    			continue;
    		if($i == 0)
    			return null;
    		return $this->lesson->activities[$i - 1];
    	}
    }

    public function next_activity(){
    	$c = $this->lesson->activities->count();
    	foreach($this->lesson->activities as $i=>$activity) { 
    		if($activity->id != $this->id)
    			continue;
    		if($i + 1 == $c)
    			return null;
    		return $this->lesson->activities[$i + 1];
    	}
    }

    // Weird LTI library stuff
    public function get_lti_type_id(){
        $lti_type = DB::table('wcl_lti')
            ->select('wcl_lti_types.id')
            ->join('wcl_lti_types', 'wcl_lti_types.id', '=', 'wcl_lti.typeid')
            ->where('wcl_lti.id', $this->lti_id)
            ->first();
        return (empty($lti_type)) ? null : $lti_type->id;
    }
}
