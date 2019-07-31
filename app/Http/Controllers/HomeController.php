<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Excel;
use App\AvgExcel;
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
        $urcdArray = null;
        $ukArray = null;
        $category = null;

        $arrayUrc = null;
        $countPop = null;
        return view('region.home', compact('unique','dbAvgExcel','chartArray','urcdArray','ukArray','arrayUrc', 'category','countPop'));
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
        AvgExcel::truncate();
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
            $totalDurasi = null;
            $avgDurasiSBU = null;
            $avgPrepTime = null;
            $avgTravelTime = null;
            $avgWorkTime = null;
            $avgRSPS = null;
            $basecamp = null;
            
            $uniqueSerpoCount = $getFilteredDate->where('serpo',$key)->count();
            $uniqueSerpoRow = $getFilteredDate->where('serpo',$key);
            
            foreach ($uniqueSerpoRow as $ubc) {
                $totalDurasi += $ubc->total_durasi;
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
            // Total Durasi dibagi dengan jumlah WO
            $totalDurasi /= $uniqueSerpoCount;
            // Total Durasi dibagi dengan jumlah banyaknya total_durasi
            // $totalDurasi /= $getFilteredDate->where('serpo',$key)->where('rsps', 1)->count();
            // Zero is Null
            $avgPrepTime = zeroIsNull($avgPrepTime);
            $avgTravelTime = zeroIsNull($avgTravelTime);
            $avgWorkTime = zeroIsNull($avgWorkTime);
            $totalDurasi = zeroIsNull($totalDurasi);
            // save into database
            $avgExcel = new AvgExcel();
                $avgExcel->basecamp = $basecamp;
                $avgExcel->serpo = $key;
                $avgExcel->jumlah_wo = $uniqueSerpoCount;
                $avgExcel->total_durasi = $totalDurasi;
                $avgExcel->durasi_sbu = $avgDurasiSBU;
                $avgExcel->prep_time = $avgPrepTime;
                $avgExcel->travel_time = $avgTravelTime;
                $avgExcel->work_time = $avgWorkTime;
                $avgExcel->rsps = $avgRSPS;
            $avgExcel->save();
        }
        
        // Filter untuk dropdown kosong
        $chartArray = array();
        $urcdArray = array();
        $urcArray = array();
        $ukArray = array();
        $category = array();
        $arrayUrc = array();
        if($regionName!=null && $getFilteredDate->count()!=null){
            // Code untuk rename Region
            switch ($regionName) {
                case 'BLI':
                    $regionLongName = 'Denpasar';
                    break;
                case 'JKT':
                    $regionLongName = 'Jakarta';
                    break;
                case 'IBT':
                    $regionLongName = 'Makassar';
                    break;
                case 'JBR':
                    $regionLongName = 'Bandung';
                    break;
                case 'JTG':
                    $regionLongName = 'Semarang';
                    break;
                case 'JTM':
                    $regionLongName = 'Surabaya';
                    break;
                case 'KAL':
                    $regionLongName = 'Balikpapan';
                    break;
                case 'OA':
                    $regionLongName = 'Open Access';
                    break;
                case 'SMSLT':
                    $regionLongName = 'Palembang';
                    break;
                case 'SMT':
                    $regionLongName = 'Pekanbaru';
                    break;
                case 'SMU':
                    $regionLongName = 'Medan';
                    break;                
                default:
                    $regionLongName = null;
                    break;
            }

            $dbAvgExcel = AvgExcel::orderBy('basecamp','asc')->get();
            // Get the total WO and Average data
            // Assign the calculated value into array
            $regionSum = $getFilteredDate->count();
            $rsps100Sum = $getFilteredDate->where('rsps', 1)->count();
            // Filter if found rsps 100 average is null
            if($rsps100Sum == 0){
                $rsps100Sum = 1;
            }
            $cardArray = array(
                'regionSum' => $regionSum,
                'totalDurasiWO' => round(($getFilteredDate->pluck('total_durasi')->sum()+$getFilteredDate->pluck('durasi_sbu')->sum())/$regionSum,2),
                'avgTotalDurasi'=> round($getFilteredDate->pluck('total_durasi')->sum()/$regionSum,2),
                'avgDurasiSBU' => round($getFilteredDate->pluck('durasi_sbu')->sum()/$regionSum,2),
                'avgPrepTime' => round($getFilteredDate->pluck('prep_time')->sum()/$regionSum,2),
                'avgTravelTime' => round($getFilteredDate->pluck('travel_time')->sum()/$regionSum,2),
                'avgWorkTime' => round($getFilteredDate->pluck('work_time')->sum()/$regionSum,2),
                'avgRSPS' => round($getFilteredDate->pluck('rsps')->sum()/$regionSum,4)
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
                $result = round($result/$counter,4);
                $chartArray[] = array('label'=>$ud,'y'=>$result*100);
            }
            // Menghitung Kendala
            $kendala = $getFilteredDate->where('kendala','<>','')->pluck('kendala');
            $uniqueKendala = $kendala->unique();
            foreach ($uniqueKendala as $ukKey => $ukName) {
                $ukValue = $getFilteredDate->where('kendala',$ukName)->count();
                if($ukName!=""){
                    $ukArray[] = array( 
                        'label' =>$ukName,
                        'y'=>$ukValue,
                        'indexLabel'=>$ukValue."/".round($ukValue/$kendala->count()*100,1)."%",
                    );
                }
            }
            array_multisort (array_column($ukArray, 'y'), SORT_DESC, $ukArray);        
            // Menghitung Root Cause dengan durasi
            $rootCaseDuration = $getFilteredDate->where('total_durasi','<>','')->where('root_cause','<>','')->pluck('root_cause');
            $uniqueRootCaseDuration = $rootCaseDuration->unique();
            foreach ($uniqueRootCaseDuration as $urcd => $urcdName) {
                $urcdValue = $getFilteredDate->where('total_durasi','<>','')->where('root_cause',$urcdName)->count();
                $urcdDuration = round($getFilteredDate->where('total_durasi','<>','')->where('root_cause',$urcdName)->pluck('total_durasi')->sum()/$urcdValue,2);
                if($urcdName!=""){
                    $urcdArray[] = array( 
                        'label' =>$urcdName,
                        'y'=>$urcdValue/$rootCaseDuration->count()*100,
                        'total'=>$urcdValue,
                        'durasi'=>$urcdDuration
                    );
                }
            }
            array_multisort (array_column($urcdArray, 'y'), SORT_DESC, $urcdArray);
            // return $getFilteredDate->where('total_durasi','<>','');

            // Menghitung Root Cause tanpa durasi
            $uniqueRootCase = $getFilteredDate->where('total_durasi','=','')->pluck('root_cause')->unique();
            foreach ($uniqueRootCase as $urc => $urcName) {
                $urcValue = $getFilteredDate->where('total_durasi','=','')->where('root_cause',$urcName)->count();
                if($urcName!=""){
                    $urcArray[] = array( 
                        'label' =>$urcName,
                        'y'=>$urcValue,
                    );
                }
            }
            array_multisort (array_column($urcArray, 'y'), SORT_DESC, $urcArray);

            // Menghitung persebaran category
            $uniqueCategory = $getFilteredDate->pluck('category')->unique();
            foreach ($uniqueCategory as $key => $value) {
                if($value != null){
                    $durasi = $getFilteredDate->where('category',$value)->avg('total_durasi')  + $getFilteredDate->where('category',$value)->avg('durasi_sbu');
                    $category[] = array(
                        'label' => $value,
                        'y' => $getFilteredDate->where('category', $value)->count(),
                        'durasi' => round($durasi,2)
                    );
                }
            }
            // return $category;

            // Menghitung Top 5 Terminasi POP
            $countPop = array();
            $uniquePop = $getFilteredDate->pluck('terminasi_pop')->unique();
            $totalPop = $getFilteredDate->where('terminasi_pop','<>','')->count();
            foreach ($uniquePop as $key => $value) {
                if($value!=null){
                    $valuePop = $getFilteredDate->where('terminasi_pop',$value)->count();
                    $countPop[] = array(
                        'label' => $value,
                        'y' => $valuePop,
                        'presentase' => round($valuePop/$totalPop*100,1)
                    );
                }
            }
            array_multisort (array_column($countPop, 'y'), SORT_DESC, $countPop);
            $countPop = array_slice($countPop, 0, 10);

            $staticUniqueCategory = Excel::pluck('category')->unique();
            foreach ($staticUniqueCategory as $key => $value) {
                if($value!=null){
                    $totalCategory = $getFilteredDate->where('category',$value)->count();
                    // filter untuk mengisi index yg tidak ada
                    if($totalCategory == 0){
                        $arrayUrc[$value][] = array('label' => 'tidak ada gangguan', 'y' => 0);
                    }
                    $uniqueRootCause = $getFilteredDate->where('category',$value)->pluck('root_cause')->unique();
                    // return $uniqueRootCase;
                    foreach ($uniqueRootCause as $keyUrc => $valueUrc) {
                        $eachValue = $getFilteredDate->where('category',$value)->where('root_cause',$valueUrc)->count();
                        $arrayUrc[$value][] = array(
                            'label' => $valueUrc,
                            'y' => $eachValue,
                            'indexLabel' => $eachValue."/".round($eachValue/$totalCategory*100,1)."%",
                        );
                    }
                }
            }
            
        }else{
            $dbAvgExcel = null;
            $arrayUrc = array();
            $staticUniqueCategory = Excel::pluck('category')->unique();
            foreach ($staticUniqueCategory as $key => $value) {
                $arrayUrc[$value][] = array();
            }
        }
        return view('region.home', compact ('unique','regionName','regionLongName','dbAvgExcel','pAwal','pAkhir', 'cardArray','chartArray','urcdArray','urcArray','ukArray','arrayUrc','category','countPop'));
    }
}