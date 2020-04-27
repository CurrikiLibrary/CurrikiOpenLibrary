<?php
namespace CurrikiRecommender\Entities;

/**
 * Measure class properties are use in anaylitcal quries as a base criteria
 *
 * @author waqarmuneer
 */

class Measures {
    private $subjectarea_ids = [];
    private $subject_ids = [];
    private $educationlevelids = [];
    private $keywords = [];
    
    public function isAnyMeaureSet() {
        return !empty($this->getSubjectIds()) || !empty($this->getSubjectareaIds()) 
                || !empty($this->getEducationlevelids()) || !empty($this->getKeywords());
    }
    public function isEmpty() {
        return empty($this->getSubjectIds()) && empty($this->getSubjectareaIds()) 
                && empty($this->getEducationlevelids()) && empty($this->getKeywords());
    }
    
    public function setSubjectareaIds($subjectarea_ids = []) {
        $this->subjectarea_ids = $subjectarea_ids;
    }
    
    public function getSubjectareaIds() {
        return $this->subjectarea_ids;
    }
    
    public function setSubjectIds($subject_ids = []) {
        $this->subject_ids = $subject_ids;
    }
    
    public function getSubjectIds(){
        return $this->subject_ids;
    }
    
    public function setEducationlevelids($educationlevelids = []) {
        $this->educationlevelids = $educationlevelids;
    }    
    
    public function getEducationlevelids() {
        return $this->educationlevelids;
    }
    
    public function setKeywords($keywords = []) {
        $this->keywords = is_array($keywords) ? $keywords : [];        
    }
    
    public function getKeywords() {
        return $this->keywords;
    }
}
