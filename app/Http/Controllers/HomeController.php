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
        $chartArray = null;
        $urcArray = null;
        return view('home', compact('unique','dbAvgExcel','chartArray','urcArray'));
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
        $filteredRegion = Excel::orderBy('wo_date','asc')->where('region' , $regionName)->get();
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
            
            $uniqueSerpoCount = $getFilteredDate->where('serpo',$key)->count();
            $uniqueSerpoRow = $getFilteredDate->where('serpo',$key);
            
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
        $chartArray = array();
        $urcArray = array();
        if($regionName!=null){
            $dbAvgExcel = avgExcel::orderBy('basecamp','asc')->get();
            // Get the total WO and Average data
            // Assign the calculated value into array
            $regionSum = $getFilteredDate->count();
            $cardArray = array(
                'regionSum' => $regionSum,
                'avgDurasiSBU' => round($getFilteredDate->pluck('durasi_sbu')->sum()/$regionSum,2),
                'avgPrepTime' => round($getFilteredDate->pluck('prep_time')->sum()/$regionSum,2),
                'avgTravelTime' => round($getFilteredDate->pluck('travel_time')->sum()/$regionSum,2),
                'avgWorkTime' => round($getFilteredDate->pluck('work_time')->sum()/$regionSum,2),
                'avgRSPS' => round($getFilteredDate->pluck('rsps')->sum()/$regionSum,2)
            );
            // Menghitung grafik performa rsps / bulan
            $rspsArray = array();
            $dateTemp = null;
            foreach ($getFilteredDate as $key => $value) {
                $date = date_format(new DateTime($value->wo_date),"Y-m");
                $rsps = $value->rsps;
                $rspsArray[] = array('date' => $date, 'rsps'=>$rsps);
            }
            $uniqueDate = array_unique(array_column($rspsArray, 'date'));
            foreach ($uniqueDate as $ud) {
                $counter = 0;
                $result = 0;
                foreach ($rspsArray as $ra) {
                    if($ra['date']==$ud){
                        $result += $ra['rsps'];
                        $counter++;
                    }
                }
                $result = round($result/$counter,2);
                $chartArray[] = array('label'=>$ud,'y'=>$result);
            }
            // Menghitung Root Cause
            $uniqueRootCase = $getFilteredDate->pluck('root_cause')->unique();
            foreach ($uniqueRootCase as $urc => $urcName) {
                $urcValue = $getFilteredDate->where('root_cause',$urcName)->count();
                if($urcName!=""){
                    $urcArray[] = array( 
                        'label' =>$urcName,
                        'y'=>$urcValue
                    );
                }
            }
            unset($urcArray[""]);
            // return $urcArray;
            // add order by array

        }else{
            $dbAvgExcel = null;
        }
        return view('home', compact ('unique','regionName','dbAvgExcel','pAwal','pAkhir', 'cardArray','chartArray','urcArray'));
    }
}