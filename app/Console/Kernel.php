<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Extractor;
use App\Http\Controllers\UserExtractorController;
use Illuminate\Http\Request;
use App\Lib\SendSMS;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\AutorunExtractors',
        'App\Console\Commands\CustomControl',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
		
		/* $schedule->command('cc:hipages') 
                  ->monthlyOn(17, '00:00');  */
		
		$extractors  = Extractor::where('ext_run_type','!=','no_repeat')->select('ext_id','ext_run_type','ext_time','ext_day','ext_date','ext_month')->get();
		
		
		foreach($extractors as $ext)
		{
			// Use the scheduler to add the task at its desired frequency
			
			$time_parts = explode(":",$ext->ext_time);
			//minute, hour, day of month, month and day of week
			if(isset($time_parts[1]))
			{
				if($ext->ext_run_type == "daily")
				{
					$expression = $time_parts[1]." ".$time_parts[0]." 1/1 * *";
				}
				else if($ext->ext_run_type == "weekly")
				{
					$expression = $time_parts[1]." ".$time_parts[0]." ? * ".$ext->ext_day;
				}
				else if($ext->ext_run_type == "monthly")
				{
					//0 5 13 15 1/1 ? *
					$expression = $time_parts[1]." ".$time_parts[0]." ".$ext->ext_date." 1/1 * ";
				}
				else if($ext->ext_run_type == "yearly")
				{
					//	0 5 13 15 5 ? *
					$expression = $time_parts[1]." ".$time_parts[0]." ".$ext->ext_date." ".$ext->ext_month." *";
				}
				echo "\n$expression \n";	
				
				$schedule->call(function() use ($expression,$ext) {
					$this->myCall($expression,$ext);
				})->cron($expression);
			}
		}
		
         /*$schedule->command('extractor:autorun')
                  ->everyMinute();*/
				  
    }
	protected function myCall($expr,$ext)
	{
		$sms =  new SendSMS();
		//$sms->sendSMS("Extractor Expression ".$expr." run based on type ".$ext->ext_id,'03004955999');
		//
		$extcntl = new UserExtractorController();

		$myRequest = new Request();
		$myRequest->replace(['_token' => "hi_cron_job"]);
		
		$extcntl->runExtractor($myRequest,$ext->ext_id);
	  // Place your logic here
	}
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
