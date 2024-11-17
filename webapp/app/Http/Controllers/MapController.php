<?php

namespace App\Http\Controllers;

use App\Events\MapUpdate;
use App\Events\SignalUpdate;
use App\Http\Requests\UpdateMapViewRequest;
use App\Http\Requests\UpdateSignalRequest;
use App\Models\CenterPoint;
use App\Models\FlightCode;
use App\Models\GardenProfile;
use App\Models\TelemetriLog;
use App\Models\TravelingSalesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MapController extends Controller
{
    // Config.
    private $py_path = 'D:\Workspace\Python\python.exe';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $selectedFlightCode = Session::get('selectedFlightCode');
        $selectedFlightCode = FlightCode::where('selected', 1)->first()->id;
        $ids = FlightCode::where('selected', 1)->first()->ids;

        $centerPoint = CenterPoint::get()->first();
        $gardenProfiles = GardenProfile::orderBy('id', 'asc')->get()->all();

        if(empty($selectedFlightCode)){
            $telemetriLogs = TelemetriLog::with(['flight_code', 'garden_profile'])->orderBy('created_at', 'asc')->get()->all();
            $telemetriLog = TelemetriLog::with(['flight_code', 'garden_profile'])->orderBy('created_at', 'desc')->first();
            $jarakTempuh = TelemetriLog::all()->sum('haversine');
        }else{
            $telemetriLogs = TelemetriLog::where('flight_code_id', $selectedFlightCode)->with(['flight_code', 'garden_profile'])->orderBy('created_at', 'asc')->get()->map(function ($item) {
                
                // $item->tPayload = str_replace(";", ":", $item->tPayload);
                return $item;
            })->all();
            $telemetriLog = TelemetriLog::where('flight_code_id', $selectedFlightCode)->with(['flight_code', 'garden_profile'])->orderBy('created_at', 'desc')->first();
            $jarakTempuh = TelemetriLog::where('flight_code_id', $selectedFlightCode)->sum('haversine');
        }

        if(count($telemetriLogs) != 0){
            $jarakAwalAkhir = $this->haversineGreatCircleDistance($telemetriLogs[0]->lat, $telemetriLogs[0]->long, $telemetriLog->lat, $telemetriLog->long);
        }else{
            $jarakAwalAkhir = [];
        }

        $flightCode = FlightCode::orderBy('created_at', 'asc')->get()->all();
        $traveling_salesmen = TelemetriLog::where('flight_code_id', $selectedFlightCode)->join('traveling_salesmen', 'telemetri_logs.id', '=', 'traveling_salesmen.telemetry_log_id')
            ->orderBy('traveling_salesmen.created_at', 'asc')->get(['telemetri_logs.lat', 'telemetri_logs.long'])->all();

        
        
        // return json_encode($telemetriLogs);
        
        

        return view('map.index', compact('centerPoint', 'telemetriLogs', 'gardenProfiles', 'jarakTempuh', 'jarakAwalAkhir', 'flightCode', 'selectedFlightCode', 'traveling_salesmen', 'ids'));
    }

    /**
     * Select the specified flight code (API version).
     */
    public function select_view_api(UpdateMapViewRequest $request)
    {
        $flightCodeExist = FlightCode::where('id', $request->flight_code_id)->exists();
        if($flightCodeExist)
        {
            $flightCode = FlightCode::where('id', $request->flight_code_id)->first();
            
            $activeFlightCode = FlightCode::where('selected', 1)->first();
            if($activeFlightCode)
            {
                $activeFlightCode->selected = 0;
                $activeFlightCode->save();
            }

            $flightCode->selected = 1;
            $flightCode->save();

            Session::put('selectedFlightCode', $flightCode->id);

            $telemetriLogs = TelemetriLog::where('flight_code_id', $request->flight_code_id)->with(['flight_code', 'garden_profile'])->orderBy('created_at', 'asc')->get()->all();
            $telemetriLog = TelemetriLog::where('flight_code_id', $request->flight_code_id)->with(['flight_code', 'garden_profile'])->orderBy('created_at', 'desc')->first();
            $jarakTempuh = TelemetriLog::where('flight_code_id', $request->flight_code_id)->sum('haversine');
        }
        else
        {
            Session::forget('selectedFlightCode');

            $telemetriLogs = TelemetriLog::with(['flight_code', 'garden_profile'])->orderBy('created_at', 'asc')->get()->all();
            $telemetriLog = TelemetriLog::with(['flight_code', 'garden_profile'])->orderBy('created_at', 'desc')->first();
            $jarakTempuh = TelemetriLog::all()->sum('haversine');
        }

        $centerPoint = CenterPoint::get()->first();
        $gardenProfiles = GardenProfile::orderBy('id', 'asc')->get()->all();
        if(count($telemetriLogs) != 0)
        {
            $jarakAwalAkhir = $this->haversineGreatCircleDistance($telemetriLogs[0]->lat, $telemetriLogs[0]->long, $telemetriLog->lat, $telemetriLog->long);
        }
        else
        {
            $jarakAwalAkhir = [];
        }

        MapUpdate::dispatch([
            //'telemetriLog' => $telemetriLogs,
            // 'centerPoint' => $centerPoint,
            // 'gardenProfiles' => $gardenProfiles,
            // 'jarakTempuh' => $jarakTempuh,
            // 'jarakAwalAkhir' => $jarakAwalAkhir
        ]);

        return response()->json([
            'telemetriLogs' => $telemetriLogs,
            'centerPoint' => $centerPoint,
            'gardenProfiles' => $gardenProfiles,
            'jarakTempuh' => $jarakTempuh,
            'jarakAwalAkhir' => $jarakAwalAkhir,
            'flightCode' => $flightCode
        ], 201);
    }

    public function tsp_api($flight_code)
    {
        $data = TelemetriLog::where('flight_code_id', $flight_code)->get(['lat', 'long', 'klasifikasi'])->map(function ($item) {
            return [
            'lat' => (float) $item->lat,
            'long' => (float) $item->long,
            'klasifikasi' => (int) $item->klasifikasi,
            ];
        });

        // return response()->json(
        //     $data
        // , 200);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://' . env('TSP_API', 'localhost:8001') . '/calculate-tsp', [
            'json' => $data->toArray()
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error calling external API');
        }

        $result = json_decode($response->getBody()->getContents(), true);

        // return response()->json(
        //     $result
        // , 200);
        
        $jarakTempuhTSP = 0;
        for ($i = 0; $i < count($result["path"]) - 1; $i++) {
            $start = $result["path"][$i];
            $end = $result["path"][$i + 1];
            // return response()->json([
            //      $start["lat"],
            //     $end
            // ], 200);
            $jarakTempuhTSP += $this->haversineGreatCircleDistance($start["lat"], $start["long"], $end["lat"], $end["long"]);
        }

        $formattedResult = array_map(function($item) {
            return implode(',', $item);
        }, $result["path"]);

        return response()->json([
            'output' => $formattedResult,
            'jarakTempuhTSP' => $jarakTempuhTSP
        ], 200);

        // $output = $process->getOutput();
        // $output = explode("\r\n", $output);
        // return response()->json([
        //     'output' => $output
        // ], 201);
    }

    public function signal_api(UpdateSignalRequest $request)
    {
        $status = $request->status;
        SignalUpdate::dispatch([
            "status" => $status
        ]);

        $selectedFlightCode = FlightCode::where('selected', 1)->first()->id;
        FlightCode::where('id', $selectedFlightCode)->update(["ids" => $status]);

        return response()->json([
            'message' => "Success"
        ], 200);
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

}
