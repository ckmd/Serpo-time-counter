<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Excel;
use App\avgExcel;
use DateTime;
use DateInterval;

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
        $dbAvgExcel = null;

        $datas = Excel::pluck('region');
        $unique = $datas->unique();
        
        return view('home', compact('filteredRegion','unique','dbAvgExcel'));
    }

    public function download(Request $request){
        return $request->all();
    }

    public function reload(Request $request){
        // Delete Database inside avgExcel
        avgExcel::truncate();
        // Filter berdasarkan region
        $regionName = $request->region;
        $filteredRegion = Excel::where('region' , $regionName)->get();
        $pAwal = $request->pawal;
        $pAkhir = $request->pakhir;
        $addOneDay = (new DateTime($pAkhir))->add(new DateInterval('P1D'))->format('Y-m-d');

        // Menampilkan Region yang ada di DB
        $region = Excel::pluck('region');
        $unique = $region->unique();
        
        // Menghitung rataan nilai per serpo yang difilter berdasarkan region
        $serpo = $filteredRegion->where('wo_date','>=',$pAwal)->where('wo_date','<=',$addOneDay)->pluck('serpo');
        
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
            // save into database
            $avgExcel = new avgExcel();
                $avgExcel->basecamp = $basecamp;
                $avgExcel->serpo = $key;
                $avgExcel->durasi_sbu = $avgDurasiSBU;
                $avgExcel->prep_time = $avgPrepTime;
                $avgExcel->travel_time = $avgTravelTime;
                $avgExcel->work_time = $avgWorkTime;
                $avgExcel->complete_time = $avgCompleteTime;
                $avgExcel->rsps = $avgRSPS;
            $avgExcel->save();
            // convert to the array

            // Refactoring Starts Here
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
            // refactoring ends here
        }
        $filteredRegion = null;

        $dbAvgExcel = avgExcel::orderBy('basecamp','asc')->get();
        return view('home', compact ('filteredRegion', 'unique','regionName','dataArray','dbAvgExcel','pAwal','pAkhir'));
    }
}
