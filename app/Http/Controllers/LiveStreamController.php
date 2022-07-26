<?php
namespace App\Http\Controllers;
use App\Models\Livestream;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\URL;
use Stichoza\GoogleTranslate\GoogleTranslate;

class LiveStreamController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $sportsType = $request['sports_type'];
            $status = $request['status'];
            if ($status and $sportsType)
            {
                $streams = Livestream::where(['status' => $status, 'sports_type' => $sportsType])->get();
            }
            elseif ($status or $sportsType)
            {
                $streams = Livestream::where('status', '=', $status)->orWhere('sports_type', '=', $sportsType)->get();
            }
            else
            {
                $streams = Livestream::all();
            }
        }
        catch(Exception $e)
        {
            return response()->json(['data' => [], 'message' => $e->getMessage() ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()
            ->json(['data' => $streams, 'message' => 'Succeed'], JsonResponse::HTTP_OK);
    }
    /*
    public function getSpecificRange(Request $request) {
        if($request->start_date && $request->end_date) {
            echo "Hi";
        } else {
            echo "Nope";
        }
    }
    */

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
                    //after validating every data, we call to handling function with the data provided.
                    $stream[] = $this->handlesRequest($data);
                }

            }
            //if the request body is an single object, then do this
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
        $stream->sports_type = $trsportsType;
        $stream->league_name = $trleagueName;
        $stream->home_team = $trhomeTeam;
        $stream->away_team = $trawayTeam;
        $stream->start_date = $data["start_date"];
        $stream->time = $data["time"];
        $stream->home_mark = $data["home_mark"];
        $stream->away_mark = $data["away_mark"];
        $stream->status = $data["status"];
        //Store the data
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

    public function updateStream(Request $request)
    {
        try
        {
            $stream = [];
            if ($request->has("data") && is_array($request->data))
            {
                $request->validate(['data.*.league_name' => 'required', 'data.*.uid' => 'required', 'data.*.home_team' => 'required', 'data.*.source.*' => 'nullable']);
                foreach ($request->data as $data)
                {
                    $stream[] = $this->handleUpdateRequests($data);
                }
            }
            else
            {
                $request->validate(['league_name' => 'required', 'required' => 'uid', 'home_team' => 'required', 'source.*' => 'nullable']);
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

        $stream = Livestream::updateOrCreate(["uid" => $data["uid"]], ["league_name" => $data["league_name"], "home_team" => $data["home_team"]]);

        foreach ($data["source"] as $source)
        {
            URL::updateOrCreate(["uid" => $data["uid"], "url" => $source["url"]]);
        }

        return $stream;

    }

    public function getSpecificStream(Request $request)
    {
        try
        {
            $url=[];
            $validate = $request->validate(['uid' => 'required']);
            $uid = $request->only('uid');
            $streams = Livestream::where('uid', '=', $uid)->get();
            $url = URL::where('uid', '=', $uid)->whereNotNull('url')
                ->get();
            
            
            $data = $streams;
            $data['source'] = $url;
            foreach ($data['source'] as $n) {
                $preParsed = $n['url'];
                $parsed = str_replace("https://sandboxlivepc","https://sandboxliveh5",$preParsed);    
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

