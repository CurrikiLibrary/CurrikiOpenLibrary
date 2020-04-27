<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LTIOAuthToken extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lti_oauth_token';
    public $timestamps = false;
}
