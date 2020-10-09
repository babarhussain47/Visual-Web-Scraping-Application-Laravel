<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtractorUrl extends Model
{
    protected $table ="extractor_urls";
    protected $primaryKey =null;
    public $timestamps = false;
	public $incrementing = false;
}
