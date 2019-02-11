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
            // Zero is Null
            $avgPrepTime = zeroIsNull($avgPrepTime);
            $avgTravelTime = zeroIsNull($avgTravelTime);
            $avgWorkTime = zeroIsNull($avgWorkTime);
            $avgCompleteTime = zeroIsNull($avgCompleteTime);
            //  echo $basecamp."<br />\n";
            // save into database
            $avgExcel = new avgExcel();
                $avgExcel->basecamp = $basecamp;
                $avgExcel->serpo = $key;
                $avgExcel->jumlah_wo = $uniqueSerpoCount;
                $avgExcel->durasi_sbu = $avgDurasiSBU;
                $avgExcel->prep_time = $avgPrepTime;
                $avgExcel->travel_time = $avgTravelTime;
                $avgExcel->work_time = $avgWorkTime;
                $avgExcel->complete_time = $avgCompleteTime;
                $avgExcel->rsps = $avgRSPS;
            $avgExcel->save();
            // convert to the array
            // echo $key." : ".$uniqueSerpoCount."<br />\n";
        }

        if($regionName!=null){
            $dbAvgExcel = avgExcel::orderBy('basecamp','asc')->get();
        }else{
            $dbAvgExcel = null;
        }
        return view('home', compact ('unique','regionName','dbAvgExcel','pAwal','pAkhir'));
    }
}
