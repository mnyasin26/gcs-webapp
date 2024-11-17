<?php

namespace App\Http\Controllers;

use App\Events\TravelingSalesmenUpdate;
use App\Http\Requests\StoreTravelingSalesmanRequest;
use App\Http\Requests\UpdateMapViewRequest;
use App\Models\FlightCode;
use App\Models\TelemetriLog;
use App\Models\TravelingSalesman;
use Illuminate\Support\Facades\DB;

class TravelingSalesmanController extends Controller
{
    public function index()
    {
        //
    }

    public function store(StoreTravelingSalesmanRequest $request)
    {
        $telemetriLog = TelemetriLog::where('lat', $request->lat)->where('long', $request->long)->first();
        if(!empty($telemetriLog))
        {
            $traveling_salesman = TravelingSalesman::create(['telemetry_log_id' => $telemetriLog->id]);
            TravelingSalesmenUpdate::dispatch([
                'telemetriLog' => $telemetriLog
            ]);

            return response()->json($telemetriLog, 201);
        }

        return response()->json(['message' => 'Error, data not found!'], 500);
    }

    public function get_data($flight_code)
    {
        $data = DB::select("SELECT a.lat, a.long FROM telemetri_logs AS a
            JOIN flight_codes AS b ON a.flight_code_id = b.id
            WHERE a.klasifikasi = 1 AND b.flight_code = ?", [$flight_code]);
        
        $lines = [];
        foreach ($data as $row)
        {
            array_push($lines, [(float) $row->lat, (float) $row->long]);
        }

        return response()->json($lines);
    }

    public function draw(StoreTravelingSalesmanRequest $request)
    {
        $data = $this->get_data();

        $result = $this->get_tsp_path($data, count($data));
        return response()->json($result, 201);
    }

    public function get_tsp_path($points, $points_num)
    {
        $tour = range(0, $points_num - 1);
        shuffle($tour);
    
        for ($temperature = pow(10, 5); $temperature >= 1; $temperature /= pow(10, 0.01)) {
            $i = mt_rand(0, $points_num - 1);
            $j = mt_rand(0, $points_num - 1);
            if ($j < $i) {
                list($i, $j) = array($j, $i);
            }
    
            $newTour = array_merge(
                array_slice($tour, 0, $i),
                array_slice($tour, $j, $j + 1),
                array_slice($tour, $i + 1, $j),
                array_slice($tour, $i, $i + 1),
                array_slice($tour, $j + 1)
            );
    
            $oldDistances = 0;
            $newDistances = 0;
            foreach ([$j, ($j - 1), $i, ($i - 1)] as $k) {
                $lat = $points[$tour[$k % $points_num]][0];
                $lon = $points[$tour[$k % $points_num]][1];
                $lat2 = $points[$tour[($k + 1) % $points_num]][0];
                $lon2 = $points[$tour[($k + 1) % $points_num]][1];
    
                $origin = [$lat, $lon];
                $destination = [$lat2, $lon2];
    
                $test = $this->haversine_distance($origin, $destination);
                $oldDistances += $test;
            }
    
            foreach ([$j, $j - 1, $i, $i - 1] as $k) {
                $lat = $points[$newTour[$k % $points_num]][0];
                $lon = $points[$newTour[$k % $points_num]][1];
                $lat2 = $points[$newTour[($k + 1) % $points_num]][0];
                $lon2 = $points[$newTour[($k + 1) % $points_num]][1];
    
                $origin = [$lat, $lon];
                $destination = [$lat2, $lon2];
    
                $test = $this->haversine_distance($origin, $destination);
                $newDistances += $test;
            }
    
            $oldDistances *= 1000;
            $newDistances *= 1000;
    
            if (exp(($oldDistances - $newDistances) / $temperature) > mt_rand() / mt_getrandmax()) {
                $tour = $newTour;
            }
        }

        $result = [];
        for ($i = 0; $i < $points_num; $i++)
        {
            array_push($result, [$points[$tour[$i % $points_num]][0], $points[$tour[$i % $points_num]][1]]);
        }

        return $result;
    }

    private function haversine_distance($origin, $destination)
    {
        $lat1 = $origin[0] * pi() / 180;
        $lon1 = $origin[1] * pi() / 180;
    
        $lat2 = $destination[0] * pi() / 180;
        $lon2 = $destination[1] * pi() / 180;
    
        $radius = 6371; // km
    
        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;
    
        $a = sin($deltaLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($deltaLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $dis = $radius * $c;
    
        return $dis;
    }
}