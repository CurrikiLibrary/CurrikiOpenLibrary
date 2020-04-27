<?php
namespace CurrikiSite\Modules\Oer;
use CurrikiSite\Components\Resource\Resource;

/**
 * Description of Oer
 *
 * @author waqarmuneer
 */

class Oer {
    
    public $message = null;
    public $resource = null;
    
    public function __construct(Resource $resource) {        
        //$this->resource = json_encode( $resource->getOne(1) );
        $this->resource = $resource->getOne(0,'Go-Crazy');
    }
}
