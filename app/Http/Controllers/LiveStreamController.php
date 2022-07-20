<?php

namespace App\Http\Controllers;
use App\Models\Livestream;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\URL;

class LiveStreamController extends Controller
{
    public function index() {
        
        $date = Carbon::now()->format('Y-m-d');
        $from = Carbon::parse($date)->subDays('4');
        $to = Carbon::parse($date)->addDays('3');
        
        try {
            $streams = Livestream::all();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $streams,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
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


    public function getSpecificStream($id) {
        try {
            $streams = Livestream::find($id);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $streams,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }
    public function storeStream(Request $request) {
        try {
            //Generate random unique string
            $uid = Str::random(30);
            //Adding the random unique string into the payload
            $request['uid'] = $uid;
            //Validate the payload received from request
            $data = $request->validate([
                'sports_type' => 'required',
                'league_name' => 'required',
                'uid' => 'required',
                'home_team' => 'required',
                'away_team' => 'required',
                'start_date' => 'required',
                'status' => 'required',
                'time' => 'required',
                'home_mark' => 'required',
                'home_score' => 'required',
                'away_mark' => 'required',
                'away_score' => 'required',
                'source.*' => 'nullable'
            ]);   
            //Check if there's any stream URL in payload         
            $check = $request['source'];
            //if there is stream URL, we will proceed to insert the data to tbl.livestream and tbl.url
            if($check) {
                $streams = Livestream::create($data);
                $sources = $request->validate([
                    'sports_type' => 'required',
                    'league_name' => 'required',
                    'uid' => 'required',
                    'home_team' => 'required',
                    'away_team' => 'required',
                    'start_date' => 'required',
                    'status' => 'required',
                    'time' => 'required',
                    'home_mark' => 'required',
                    'home_score' => 'required',
                    'away_mark' => 'required',
                    'away_score' => 'required',
                    'source.*' => 'nullable'
                ]);
                foreach ($sources['source'] as $source) { 
                    $end = URL::create([
                        'uid' => $uid,
                        'url' => $source['url']
                    ]);
                }
            }
            //if there isn't any stream URL, we will proceed to insert the uuid in tbl.streams for reference. 
            elseif(!$check) {
                $streams = Livestream::create($data);
                $basicURL = URL::create([
                    'uid' => $uid
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'data' => $data,
            'message' => "Stream Created Successfully"
        ], JsonResponse::HTTP_OK);
    }    
    

    public function updateStream(Request $request) {
        try {
            $uid = $request['uid'];
            $validate = $request->validate([
                'uid' => 'required',
                'source.*' => 'nullable'
            ]);
            $uid = $request->input('uid');
            $streams = Livestream::where('uid','=',$uid)
                        ->update([
                            'league_name' => $request['league_name'],
                            'sports_type' => $request['sports_type'],
                            'uid' => $request['uid'],
                            'home_team' => $request['home_team'],
                            'away_team' => $request['away_team'],
                            'start_date' => $request['start_date'],
                            'status' => $request['status'],
                            'time' => $request['time'],
                            'home_mark' => $request['home_mark'],
                            'home_score' => $request['home_score'],
                            'away_mark' => $request['away_mark'],
                            'away_score' => $request['away_score'],
                        ]);
            if($request->filled('source')) {

                foreach ($request['source'] as $source) { 
                    $end = URL::where('uid','=',$uid)->updateOrCreate([
                        'uid' => $uid,
                        'url' => $source['url']
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $streams,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }
    
    public function removeStream($id) {
        try {
            $streams = Livestream::destroy($id);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $streams,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }
    
}
