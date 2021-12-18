<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dailyNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reach user tash where not open more than 1 day';

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
     * @return int
     */
    public function handle()
    {
        $expDate = Carbon::now()->subDays(1);
        $dataTask = Task::whereDate('date_histori', '<',$expDate); 
        return Command::SUCCESS;
         
    }
}
