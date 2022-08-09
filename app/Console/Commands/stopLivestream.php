<?php

namespace App\Console\Commands;
use App\Models\Livestream;
use App\Models\URL;
use Carbon\Carbon;
use Illuminate\Console\Command;

class stopLivestream extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stop:livestream';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop livestream after it reaches a certain time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
	    $today = Carbon::now();
	    $currentDate = $today->format('Y-m-d');
	    $hour = date('H');

        if($hour <  3){

            $previousDate = date('Y-m-d',strtotime("-1 days"));
            $time = date('H:i:s',time() - 10800);
            $expired = Livestream::where('start_date','=',$previousDate)->where('time','<',$time)->update(['status' => 'ended']);
        } else {
	$expired = Livestream::where('start_date','=',$currentDate)->where('time','<',Carbon::now()->addMinutes(-200))->update(['status' => 'ended']);
	}
    }
}
