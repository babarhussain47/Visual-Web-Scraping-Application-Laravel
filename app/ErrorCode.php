<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorCode extends Model
{
    protected $table ="error_codes";
    protected $primaryKey ="error_id";
    public $timestamps = false;
}
