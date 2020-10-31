<?php namespace App\Handyimport;

use App\Package;
use App\Extractor;
use App\PackageSubscribed;
use Auth;
class PackageDetail
{
	/*
	* Constructor function
	*/
	
	public $allowed_requests;
	public $allowed_api_requests;
	public $isEmailAllowed;
	public $isAPIAllowed;
	public $isPostDataAllowed;
	public $isAutoScheduleAllowed;
	public $allowedColumn;
	public $allowedRow;
	
	function __construct()
	{
		$this->allowed_requests = 0;
		$this->allowed_api_requests = 0;
		$this->allowed_extractors = 0;
		$this->isEmailAllowed = FALSE;
		$this->isPostDataAllowed = FALSE;
		$this->isAutoScheduleAllowed = FALSE;
		$this->isAPIAllowed = FALSE;
		$this->allowedColumn = 0;
		$this->allowedRow = 0;
		$this->getPackages();
	}
	
	function isUserAllowedToCreateNewExtractor()
	{
		$packages =  PackageSubscribed::join("packages","p_id","=","sp_id")->where("u_id",Auth::user()->id)->get();
		foreach($packages as $package)
		{
			if($this->isNotExpired($package->p_validity,$package->created_at))
			{
				$this->allowed_extractors += $package->p_allowed_extractors;
			}
		}
		
		$extractors = Extractor::where("user_id",Auth::user()->id)->count();
		
		if($this->allowed_extractors > $extractors)
		{
			return true;
		}
		return false;
	}
	
	function updatePackage($requ,$api)
	{
		$packages =  PackageSubscribed::join("packages","p_id","=","sp_id")->where("u_id",Auth::user()->id)->get();
		foreach($packages as $package)
		{
			if($this->isNotExpired($package->p_validity,$package->created_at))
			{
				if($package->p_allowed_request_rem >= $requ)
				{
					$package->p_allowed_request_rem -= $requ;
					$requ = 0;
				}
				else if($package->p_allowed_request_rem < $requ && $requ > 0)
				{
					$package->p_allowed_request_rem = 0 ;
					$requ -= $package->p_allowed_request_rem;
				}
					
				if($package->p_allowed_request_rem >= $api){
					$package->p_allowed_api_request_rem -= $api;
					$api=0;
				}
				else if($package->p_allowed_api_request_rem < $api && $api > 0)
				{
					$package->p_allowed_api_request_rem = 0 ;
					$api -= $package->p_allowed_api_request_rem;
				}
				$package->save();
			}
		}
	}
	function isNotExpired($count,$date)
	{
		$start = strtotime($date);
		$end = time();

		$days_between = ceil(abs($end - $start) / 86400);
	
		if($days_between <= $count)
		{
			return true;
		}
		return false;
		
	}
	/*
		will get all valid packages that are not expired and get remaining quota
	*/
	function getPackages()
	{
		$packages =  PackageSubscribed::join("packages","p_id","=","sp_id")->where("u_id",Auth::user()->id)->get();
		foreach($packages as $package)
		{
			if($this->isNotExpired($package->p_validity,$package->created_at))
			{
				$this->allowed_requests += $package->p_allowed_request_rem;
				$this->allowed_api_requests += $package->p_allowed_api_request_rem;
		
				if($package->p_post_data)
				{
					$this->isPostDataAllowed = TRUE;
				}
				if($package->p_email_data)
				{
					$this->isEmailAllowed = TRUE;
				}
				if($package->p_auto_schedule)
				{
					$this->isAutoScheduleAllowed = TRUE;
				}
				if($package->p_allowed_api)
				{
					$this->isAPIAllowed = TRUE;
				}
				
			}
		}
	}

} // end of class


?>