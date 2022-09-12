<?php
namespace App\Http\Controllers;
use App\Models\Livestream;
use App\Models\dictST;
use App\Models\dictLN;
use App\Models\dictFT;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\StreamDump;
use Carbon\Carbon;
use App\Models\URL;
use Stichoza\GoogleTranslate\GoogleTranslate;

class LiveStreamController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $today = Carbon::now();
            $from = $today->format('Y-m-d');
            $to = Carbon::now()->addDay();
            $sportsType = $request['sports_type'];
            $status = $request['status'];
            if ($status and $sportsType)
            {
                $hour = date('H');
                $streams = Livestream::whereBetween('start_date', [$from, $to])->where(['status' => $status, 'sports_type' => $sportsType])->orderBy('start_date', 'ASC')
                    ->orderBy('time', 'ASC')
                    ->get();
                if ($hour == 0)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '21:30:00')
                        ->where(['status' => $status, 'sports_type' => $sportsType])->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
                elseif ($hour == 1)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '22:30:00')
                        ->where(['status' => $status, 'sports_type' => $sportsType])->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
                elseif ($hour == 2)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '23:30:00')
                        ->where(['status' => $status, 'sports_type' => $sportsType])->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
            }
            elseif ($status)
            {
                $streams = Livestream::whereBetween('start_date', [$from, $to])->where('status', '=', $status)->orderBy('start_date', 'ASC')
                    ->orderBy('time', 'ASC')
                    ->get();
                $hour = date('H');
                if ($hour == 0)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '21:30:00')
                        ->where('status', '=', $status)->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
                elseif ($hour == 1)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '22:30:00')
                        ->where('status', '=', $status)->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
                elseif ($hour == 2)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '23:30:00')
                        ->where('status', '=', $status)->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
            }
            elseif ($sportsType)
            {
                $streams = Livestream::whereBetween('start_date', [$from, $to])->whereIn('status', ['notStarted', 'inPlay'])
                    ->where('sports_type', '=', $sportsType)->orderBy('start_date', 'ASC')
                    ->orderBy('time', 'ASC')
                    ->get();
                $hour = date('H');
                if ($hour == 0)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '21:30:00')
                        ->where('sports_type', '=', $sportsType)->whereIn('status', ['notStarted', 'inPlay'])
                        ->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
                elseif ($hour == 1)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '22:30:00')
                        ->where('sports_type', '=', $sportsType)->whereIn('status', ['notStarted', 'inPlay'])
                        ->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
                elseif ($hour == 2)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '23:30:00')
                        ->where('sports_type', '=', $sportsType)->whereIn('status', ['notStarted', 'inPlay'])
                        ->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
            }
            else
            {
                $streams = Livestream::whereBetween('start_date', [$from, $to])->whereIn('status', ['notStarted', 'inPlay'])
                    ->orderBy('start_date', 'ASC')
                    ->orderBy('time', 'ASC')
                    ->get();
                $hour = date('H');
                if ($hour == 0)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '21:30:00')
                        ->whereIn('status', ['notStarted', 'inPlay'])
                        ->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
                elseif ($hour == 1)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '22:30:00')
                        ->whereIn('status', ['notStarted', 'inPlay'])
                        ->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
                elseif ($hour == 2)
                {
                    $prev_date = date("Y-m-d", strtotime('-1 days'));
                    $streams_prev = Livestream::where('start_date', $prev_date)->where('time', '>', '23:30:00')
                        ->whereIn('status', ['notStarted', 'inPlay'])
                        ->orderBy('start_date', 'ASC')
                        ->orderBy('time', 'ASC')
                        ->get();
                    $streams = $streams_prev->merge($streams);
                }
            }
        }
        catch(Exception $e)
        {
            return response()->json(['data' => [], 'message' => $e->getMessage() ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()
            ->json(['data' => $streams, 'message' => 'Succeed'], JsonResponse::HTTP_OK);
    }

    public function storeStream(Request $request)
    {
        try
        {
            //define the variable as array
            $stream = [];
            //if the request is an array and valid then validate the entire payload
            if ($request->has("data") && is_array($request->data))
            {
                $request->validate(['data.*.league_name' => 'required', 'data.*.status' => 'required', 'data.*.league_name' => 'required', 'data.*.start_date' => 'required', 'data.*.time' => 'required', 'data.*.home_mark' => 'required', 'data.*.away_mark' => 'required', 'data.*.sports_type' => 'required', 'data.*.away' => 'required', 'data.*.home' => 'required', 'data.*.source.*' => 'nullable']);

                foreach ($request->data as $data)
                {
                    //Generate random unique string
                    $uid = Str::random(30);
                    //Adding the random unique string into the payload
                    $data['uid'] = $uid;
                    $dump = StreamDump::create(['league_name' => $data['league_name'], 'home_team' => $data['home'], 'away_team' => $data['away'], 'status' => $data['status'], 'start_date' => $data['start_date'], 'time' => $data['time'], 'home_mark' => $data['home_mark'], 'away_mark' => $data['away_mark'], 'sports_type' => $data['sports_type'], 'league_mark' => $data['league_mark'], 'uid' => $data['uid']]);
                    $stream[] = $this->handlesRequest($data);
                }

            }
            //if the request body is an single object, then do this. NOTE: This is not maintained properly as Crawler will always send in an array format regardless
            else
            {
                $request->validate(['league_name' => 'required', 'status' => 'required', 'league_name' => 'required', 'start_date' => 'required', 'time' => 'required', 'home_mark' => 'required', 'away_mark' => 'required', 'away' => 'required', 'home' => 'required', 'sports_type' => 'required', 'source.*' => 'nullable']);
                $data = $request->all();
                //Generate random unique string
                $uid = Str::random(30);
                //Adding the random unique string into the payload
                $data['uid'] = $uid;
                $stream = $this->handlesRequest($data);
            }
        }
        catch(Exception $e)
        {
            return response()->json(['data' => [], 'message' => $e->getMessage() ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()
            ->json(['data' => $stream, 'message' => "Stream Created Successfully"], JsonResponse::HTTP_OK);
    }

    private function handlesRequest(array $data)
    {
        //Checking the request data with the dictionary table and find match.
        $sports_type = dictST::where('OB', '=', $data['sports_type'])->pluck('actual_word');
        $league_name = dictLN::where('OB', '=', $data['league_name'])->pluck('actual_word');
        $home_team = dictFT::where('OB', '=', $data['home'])->pluck('actual_word');
        $away_team = dictFT::where('OB', '=', $data['away'])->pluck('actual_word');

        //Check if the scrape parameter is OB, and then check if the data matches the dictionary if match then execute this.
        if (TRIM($league_name) == '[]' || TRIM($home_team) == '[]' || TRIM($away_team) == '[]')
        {
            $translate = new GoogleTranslate('ja');
            //Start to translate (league_name, home_team, away_team, sports_type)
            $leagueName = $data['league_name'];
            $homeTeam = $data['home'];
            $awayTeam = $data['away'];
            $sportsType = $data['sports_type'];
            //Start to translate
            $trleagueName = $translate->translate($leagueName);
            $trhomeTeam = $translate->translate($homeTeam);
            $trawayTeam = $translate->translate($awayTeam);
            $trsportsType = $translate->translate($sportsType);
            //Start creating all of the data by defining which column to use which data provided.
            $stream = new LiveStream;
            $stream->uid = $data["uid"];
            $stream->sports_type = $data['sports_type'];
            $stream->jp_sports_type = $trsportsType;
            $stream->league_name = $trleagueName;
            $stream->home_team = $trhomeTeam;
            $stream->away_team = $trawayTeam;
            $stream->start_date = $data["start_date"];
            $stream->time = $data["time"];
            $stream->home_mark = $data["home_mark"];
            $stream->away_mark = $data["away_mark"];
            $stream->status = $data["status"];
            $stream->league_mark = $data["league_mark"];
            $stream->save();

            //If there is an URL in the [source] payload, use this method.
            if (filled($data["source"]))
            {
                foreach ($data['source'] as $source)
                {
                    $end = new URL;
                    $end->uid = $data["uid"];
                    $end->url = $source["url"];
                    $end->save();
                }
            }
            //if there is no URL in the [source] payload, use this method instead.
            else
            {
                $end = new URL;
                $end->uid = $data["uid"];
                $end->save();
            }
            return $stream->load("sources");
        }
        //if there is no scrape parameter or there are no match on the dictionary table then execute this.
        elseif ($data['scrape'] == "OB")
        {
            $stream = new LiveStream;
            $stream->uid = $data["uid"];
            $stream->sports_type = $data['sports_type'];
            $stream->jp_sports_type = $sports_type[0];
            $stream->league_name = $league_name[0];
            $stream->home_team = $home_team[0];
            $stream->away_team = $away_team[0];
            $stream->start_date = $data["start_date"];
            $stream->time = $data["time"];
            $stream->home_mark = $data["home_mark"];
            $stream->away_mark = $data["away_mark"];
            $stream->status = $data["status"];
            $stream->league_mark = $data["league_mark"];
            $stream->save();

            //If there is an URL in the [source] payload, use this method.
            if (filled($data["source"]))
            {
                foreach ($data['source'] as $source)
                {
                    $end = new URL;
                    $end->uid = $data["uid"];
                    $end->url = $source["url"];
                    $end->save();
                }
            }
            //if there is no URL in the [source] payload, use this method instead.
            else
            {
                $end = new URL;
                $end->uid = $data["uid"];
                $end->save();
            }
            return $stream->load("sources");
        }
    }

    public function updateStream(Request $request)
    {
        try
        {
            $stream = [];
            if ($request->has("data") && is_array($request->data))
            {
                $request->validate(['data.*.uid' => 'required', 'data.*.source.*' => 'nullable']);
                foreach ($request->data as $data)
                {
                    $stream[] = $this->handleUpdateRequests($data);
                }
            }
            else
            {
                $request->validate(['required' => 'uid', 'source.*' => 'nullable']);
                $data = $request->all();
                $stream = $this->handleUpdateRequests($data);
            }
        }
        catch(Exception $e)
        {
            return response()->json(['data' => [], 'message' => $e->getMessage() ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()
            ->json(['data' => $stream, 'message' => "Stream Updated Successfully"], JsonResponse::HTTP_OK);
    }

    private function handleUpdateRequests(array $data)
    {
        //Check if there is a status inside the data array if no do this or that.
        if (!$data['status'])
        {
            foreach ($data["source"] as $source)
            {
                URL::updateOrCreate(["uid" => $data["uid"], "url" => $source["url"]]);
            }

        }
        elseif ($data['status'])
        {
            $stream = Livestream::where("uid", '=', $data['uid'])->update(["status" => $data["status"]]);
            foreach ($data["source"] as $source)
            {
                URL::updateOrCreate(["uid" => $data["uid"], "url" => $source["url"]]);
            }
        }
        return $data;
    }

    public function getSpecificStream(Request $request)
    {
        try
        {
            $url = [];
            $validate = $request->validate(['uid' => 'required']);
            $uid = $request->only('uid');
            $streams = Livestream::where('uid', '=', $uid)->get();
            $url = URL::where('uid', '=', $uid)->whereNotNull('url')
                ->get();

            $data = $streams;
            $data['source'] = $url;
            foreach ($data['source'] as $n)
            {
                $preParsed = $n['url'];
                $parsed = str_replace("https://sandboxlivepc", "https://sandboxliveh5", $preParsed);
                $n['mobile'] = $parsed;
            }

            return $data;
        }
        catch(Exception $e)
        {
            return response()->json(['data' => [], 'message' => $e->getMessage() ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()
            ->json(['data' => $data, 'message' => 'Succeed'], JsonResponse::HTTP_OK);
    }
}

