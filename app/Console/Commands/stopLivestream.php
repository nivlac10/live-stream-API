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
	    $hour = date('H:i');
        $expiredTime = "03:25:00"; //Define the time of the expiry which is 3:20a.m (3.25 to prevent the delay causes)
        $converted = date('H:i',strtotime($expiredTime));
        if($hour <  $converted){
            $previousDate = date('Y-m-d',strtotime("-1 days"));
            $time = date('H:i:s',time() - 12000);
            $expired = Livestream::where('start_date','=',$previousDate)->where('time','<',$time)->update(['status' => 'ended']);
        } else {
	        $expired = Livestream::where('start_date','=',$currentDate)->where('time','<',Carbon::now()->addMinutes(-200))->update(['status' => 'ended']);
	    }
    }
}
