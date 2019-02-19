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
        $dbAvgExcel = null;

        $datas = Excel::pluck('region');
        $unique = $datas->unique();
        
        return view('home', compact('unique','dbAvgExcel'));
    }

    public function download(Request $request){
        return $request->all();
    }

    public function reload(Request $request){
        function zeroIsNull($toNull){
            if($toNull==0){
                $toNull = null;
            }
            return $toNull;
        }
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
        $getFilteredDate = $filteredRegion->where('wo_date','>=',$pAwal)->where('wo_date','<=',$addOneDay);
        $serpo = $getFilteredDate->pluck('serpo');
        
        $uniqueSerpo = $serpo->unique();
        $cardArray = array();
        
        foreach ($uniqueSerpo as $key) {
            // Variable Initiation
            $avgDurasiSBU = null;
            $avgPrepTime = null;
            $avgTravelTime = null;
            $avgWorkTime = null;
            $avgRSPS = null;
            $basecamp = null;
            
            $uniqueSerpoCount = Excel::where('serpo',$key)->count();
            $uniqueSerpoRow = Excel::where('serpo',$key)->get();
            
            foreach ($uniqueSerpoRow as $ubc) {
                $avgDurasiSBU += $ubc->durasi_sbu;
                $avgPrepTime += $ubc->prep_time;
                $avgTravelTime += $ubc->travel_time;
                $avgWorkTime += $ubc->work_time;
                $avgRSPS += $ubc->rsps;
                if($basecamp==null){
                    $basecamp = $ubc->basecamp;
                }
            }
            $avgDurasiSBU /= $uniqueSerpoCount;
            $avgPrepTime /= $uniqueSerpoCount;
            $avgTravelTime /= $uniqueSerpoCount;
            $avgWorkTime /= $uniqueSerpoCount;
            $avgRSPS /= $uniqueSerpoCount;
            // Zero is Null
            $avgPrepTime = zeroIsNull($avgPrepTime);
            $avgTravelTime = zeroIsNull($avgTravelTime);
            $avgWorkTime = zeroIsNull($avgWorkTime);
            // save into database
            $avgExcel = new avgExcel();
                $avgExcel->basecamp = $basecamp;
                $avgExcel->serpo = $key;
                $avgExcel->jumlah_wo = $uniqueSerpoCount;
                $avgExcel->durasi_sbu = $avgDurasiSBU;
                $avgExcel->prep_time = $avgPrepTime;
                $avgExcel->travel_time = $avgTravelTime;
                $avgExcel->work_time = $avgWorkTime;
                $avgExcel->rsps = $avgRSPS;
            $avgExcel->save();
        }

        // Filter untuk dropdown kosong
        if($regionName!=null){
            $dbAvgExcel = avgExcel::orderBy('basecamp','asc')->get();
        }else{
            $dbAvgExcel = null;
        }
        // Get the total WO and Average data
        $regionSum = $getFilteredDate->count();
        $avgDurasiSBU = round($getFilteredDate->pluck('durasi_sbu')->sum()/$regionSum,2);
        $avgPrepTime = round($getFilteredDate->pluck('prep_time')->sum()/$regionSum,2);
        $avgtravelTime = round($getFilteredDate->pluck('travel_time')->sum()/$regionSum,2);
        $avgWorkTime = round($getFilteredDate->pluck('work_time')->sum()/$regionSum,2);
        $avgRSPS = round($getFilteredDate->pluck('rsps')->sum()/$regionSum,2);
        // Assign the calculated value into array
        $cardArray = array(
            'regionSum' => $regionSum,
            'avgDurasiSBU' => $avgDurasiSBU,
            'avgPrepTime' => $avgPrepTime,
            'avgTravelTime' => $avgtravelTime,
            'avgWorkTime' => $avgWorkTime,
            'avgRSPS' => $avgRSPS
        );

        return view('home', compact ('unique','regionName','dbAvgExcel','pAwal','pAkhir', 'cardArray'));
    }
}