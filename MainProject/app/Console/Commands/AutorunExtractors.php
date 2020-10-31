<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Extractor;
class AutorunExtractors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extractor:autorun';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking the extractors if any present to run.';

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
	/*	echo "Checking Extractor auto scheduled \n";
		$extractors  = Extractor::where('ext_run_type','!=','no_repeat')->count();
		echo $extractors." extractors to auto-run \n";
		*/
    }
}
