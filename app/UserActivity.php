<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $table = 'user_activities';
	protected $primaryKey = null;
	public $incrementing = false;
}
