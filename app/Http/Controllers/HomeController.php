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
        $filteredRegion = NULL;
        $datas = Excel::pluck('region');
        $unique = $datas->unique();
        return view('home', compact('filteredRegion','unique'));
    }

    public function reload(Request $request){
        // Filter berdasarkan region
        $regionName = $request->region;
        $filteredRegion = Excel::where('region' , $regionName)->get();

        // Menampilkan Region yang ada di DB
        $region = Excel::pluck('region');
        $unique = $region->unique();

        // Menghitung rataan nilai per basecamp yang difilter berdasarkan region
        $basecamp = Excel::where('region', $regionName)->pluck('basecamp');
        $uniqueBasecamp = $basecamp->unique();
        foreach ($uniqueBasecamp as $key) {
            // Variable Initiation
            $avgDurasiSBU = null;
            $avgPrepTime = null;
            $avgTravelTime = null;
            $avgWorkTime = null;
            $avgCompleteTime = null;
            $avgRSPS = null;

            $uniqueBasecampCount = Excel::where('basecamp',$key)->count();
            $uniqueBasecampRow = Excel::where('basecamp',$key)->get();

            echo " ".$key."<br />\n";
            foreach ($uniqueBasecampRow as $ubc) {
                $avgDurasiSBU += $ubc->durasi_sbu;
                $avgPrepTime += $ubc->prep_time;
                $avgTravelTime += $ubc->travel_time;
                $avgWorkTime += $ubc->work_time;
                $avgCompleteTime += $ubc->complete_time;
                $avgRSPS += $ubc->rsps;
            }
            $avgDurasiSBU /= $uniqueBasecampCount;
            $avgPrepTime /= $uniqueBasecampCount;
            $avgTravelTime /= $uniqueBasecampCount;
            $avgWorkTime /= $uniqueBasecampCount;
            $avgCompleteTime /= $uniqueBasecampCount;
            $avgRSPS /= $uniqueBasecampCount;

            // convert to the array
            echo $avgRSPS."<br />\n";
        }
//        $filteredRegion = null;

        return view('home', compact ('filteredRegion', 'unique','regionName'));
    }
}
