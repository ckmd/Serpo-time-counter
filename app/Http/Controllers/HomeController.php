<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Persyaratan Passing Value
        $filteredRegion = NULL;
        $dataArray = NULL;

        $datas = Excel::pluck('region');
        $unique = $datas->unique();
        
        return view('home', compact('filteredRegion','unique','dataArray'));
    }

    public function reload(Request $request){
        // Filter berdasarkan region
        $regionName = $request->region;
        $filteredRegion = Excel::where('region' , $regionName)->get();

        // Menampilkan Region yang ada di DB
        $region = Excel::pluck('region');
        $unique = $region->unique();

        // Menghitung rataan nilai per serpo yang difilter berdasarkan region
        $serpo = Excel::where('region', $regionName)->pluck('serpo');
        $uniqueSerpo = $serpo->unique();
        $dataArray = array();
        foreach ($uniqueSerpo as $key) {
            // Variable Initiation
            $avgDurasiSBU = null;
            $avgPrepTime = null;
            $avgTravelTime = null;
            $avgWorkTime = null;
            $avgCompleteTime = null;
            $avgRSPS = null;
            $basecamp = null;

            $uniqueSerpoCount = Excel::where('serpo',$key)->count();
            $uniqueSerpoRow = Excel::where('serpo',$key)->get();

            foreach ($uniqueSerpoRow as $ubc) {
                $avgDurasiSBU += $ubc->durasi_sbu;
                $avgPrepTime += $ubc->prep_time;
                $avgTravelTime += $ubc->travel_time;
                $avgWorkTime += $ubc->work_time;
                $avgCompleteTime += $ubc->complete_time;
                $avgRSPS += $ubc->rsps;
                if($basecamp==null){
                    $basecamp = $ubc->basecamp;
                }
            }
            $avgDurasiSBU /= $uniqueSerpoCount;
            $avgPrepTime /= $uniqueSerpoCount;
            $avgTravelTime /= $uniqueSerpoCount;
            $avgWorkTime /= $uniqueSerpoCount;
            $avgCompleteTime /= $uniqueSerpoCount;
            $avgRSPS /= $uniqueSerpoCount;

            //  echo $basecamp."<br />\n";
            // convert to the array
            $dataArray[] = array(
                'basecamp' => $basecamp,
                'serpo' => $key,
                'avgDurasiSBU' => $avgDurasiSBU,
                'avgPrepTime' => $avgPrepTime,
                'avgTravelTime' => $avgTravelTime,
                'avgWorkTime' => $avgWorkTime,
                'avgCompleteTime' => $avgCompleteTime,
                'avgRSPS' => $avgRSPS                
            );
        }
        $filteredRegion = null;

        return view('home', compact ('filteredRegion', 'unique','regionName','dataArray'));
    }
}
