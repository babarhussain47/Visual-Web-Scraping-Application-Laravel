<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageSubscribed extends Model
{
    protected $table = "subscriptions";
    protected $primaryKey = "s_id";
}
