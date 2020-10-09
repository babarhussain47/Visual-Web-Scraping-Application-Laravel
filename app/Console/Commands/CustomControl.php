<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\CustomController;
class CustomControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cc:hipages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'HiPages CMD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cc = new CustomController();
		$cc->traverseBusinessDataAPI();
		//$cc->traverseBusinessMemberAPI();
		//$cc->traverseBusinessPhoneAPI();
		//$cc->test();
    }
}
