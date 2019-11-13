<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Excel;
use App\Pop;
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
        $filteredRegion = Excel::orderBy('wo_complete','asc')->where('region' , $regionName)->get();
        $pAwal = $request->pawal;
        $pAkhir = $request->pakhir;
        $addOneDay = (new DateTime($pAkhir))->add(new DateInterval('P1D'))->format('Y-m-d');

        // Menampilkan Region yang ada di DB
        $region = Excel::pluck('region');
        $unique = $region->unique();
        
        // Menghitung rataan nilai per serpo yang difilter berdasarkan region
        $getFilteredDate = $filteredRegion->where('wo_complete','>=',$pAwal)->where('wo_complete','<=',$addOneDay);
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
                $totalDurasi += $ubc->total_durasi_serpo;
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
        $regionLongName = null;
        $countPop = array();
        $currentDate = date('Y-m-d H:i:s');
        $currentDate = (new DateTime($currentDate))->add(new DateInterval('PT7H'))->format('Y-m-d H:i:s');
        $staticUniqueCategory = array("FOC", "FOT/Perangkat","Bukan Gangguan","PS","Software");
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
                'totalDurasiWO' => round($getFilteredDate->pluck('total_durasi_wo')->sum()/$regionSum,0),
                'avgTotalDurasi'=> round($getFilteredDate->pluck('total_durasi_serpo')->sum()/$regionSum,0),
                'avgDurasiSBU' => round($getFilteredDate->pluck('durasi_sbu')->sum()/$regionSum,0),
                'avgPrepTime' => round($getFilteredDate->pluck('prep_time')->sum()/$regionSum,0),
                'avgTravelTime' => round($getFilteredDate->pluck('travel_time')->sum()/$regionSum,0),
                'avgWorkTime' => round($getFilteredDate->pluck('work_time')->sum()/$regionSum,0),
                'avgRSPS' => round($getFilteredDate->pluck('rsps')->sum()/$regionSum,2)
            );
            // Menghitung trend performa rsps / bulan
            $rspsArray = array();
            $dateTemp = null;
            foreach ($getFilteredDate as $key => $value) {
                if($value->wo_complete!=null){
                    $date = date_format(new DateTime($value->wo_complete),"Y-m");
                    $rsps = $value->rsps;
                    $rspsArray[] = array('date' => $date, 'rsps'=>$rsps);
                }
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
            array_multisort (array_column($chartArray, 'label'), SORT_ASC, $chartArray);

            // Menghitung Kendala
            $kendala = $getFilteredDate->where('kendala','<>','')->where('kendala','<>','Tidak Ada Kendala')->pluck('kendala');
            $uniqueKendala = $kendala->unique();
            foreach ($uniqueKendala as $ukKey => $ukName) {
                $ukValue = $getFilteredDate->where('kendala',$ukName)->count();
                $ukArray[] = array( 
                    'label' =>$ukName,
                    'y'=>$ukValue,
                    'indexLabel'=>$ukValue." [".round($ukValue/$kendala->count()*100,1)."%]",
                );
            }
            array_multisort (array_column($ukArray, 'y'), SORT_DESC, $ukArray);        
            // Menghitung Root Cause dengan durasi
            $rootCaseDuration = $getFilteredDate->where('total_durasi_wo','<>','')->where('root_cause','<>','')->pluck('root_cause');
            $uniqueRootCaseDuration = $rootCaseDuration->unique();
            foreach ($uniqueRootCaseDuration as $urcd => $urcdName) {
                $urcdValue = $getFilteredDate->where('total_durasi_wo','<>','')->where('root_cause',$urcdName)->count();
                $urcdDuration = round($getFilteredDate->where('total_durasi_wo','<>','')->where('root_cause',$urcdName)->pluck('total_durasi_wo')->sum()/$urcdValue,2);
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

            // Menghitung Root Cause tanpa durasi
            $uniqueRootCase = $getFilteredDate->where('total_durasi_wo','=','')->pluck('root_cause')->unique();
            foreach ($uniqueRootCase as $urc => $urcName) {
                $urcValue = $getFilteredDate->where('total_durasi_wo','=','')->where('root_cause',$urcName)->count();
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
                    $durasi = $getFilteredDate->where('category',$value)->avg('total_durasi_wo');
                    $category[] = array(
                        'label' => $value,
                        'y' => $getFilteredDate->where('category', $value)->count(),
                        'durasi' => round($durasi,0)
                    );
                }
            }
            // return $category;

            // Menghitung Top 10 Terminasi POP
            $pop = Pop::all();
            $uniquePop = $getFilteredDate->pluck('terminasi_pop')->unique();
            $totalPop = $getFilteredDate->where('terminasi_pop','<>','')->count();
            foreach ($uniquePop as $key => $value) {
                if($value!=null && $value != "DARK FIBER/NO TERIMNATION"){
                    $valuePop = $getFilteredDate->where('terminasi_pop',$value)->count();
                    $countPop[] = array(
                        'label' => $value,
                        'y' => $valuePop,
                        'desc' => $pop->where('pop_id',$value)->pluck('pop_name')->first(),
                        'presentase' => round($valuePop/$totalPop*100,1),
                        'foc' => $getFilteredDate->where('terminasi_pop',$value)->where('category','FOC')->count(),
                        'fot' => $getFilteredDate->where('terminasi_pop',$value)->where('category','FOT/Perangkat')->count(),
                        'software' => $getFilteredDate->where('terminasi_pop',$value)->where('category','Software')->count(),
                        'bukangg' => $getFilteredDate->where('terminasi_pop',$value)->where('category','Bukan Gangguan')->count(),
                        'ps' => $getFilteredDate->where('terminasi_pop',$value)->where('category','PS')->count()
                    );
                }
            }
            array_multisort (array_column($countPop, 'y'), SORT_DESC, $countPop);
            $countPop = array_slice($countPop, 0, 10);
            
            // Proses menghitung Kategori beserta isinya
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
                            'indexLabel' => $eachValue." [".round($eachValue/$totalCategory*100,1)."%]",
                        );
                    }
                    array_multisort (array_column($arrayUrc[$value], 'y'), SORT_DESC, $arrayUrc[$value]);
                }
            }
            
        }else{
            $dbAvgExcel = null;
            $arrayUrc = array();
            foreach ($staticUniqueCategory as $key => $value) {
                $arrayUrc[$value][] = array();
            }
        }
        // return $ukArray;
        return view('region.home', compact ('unique','regionName','regionLongName','dbAvgExcel','pAwal','pAkhir', 'cardArray','chartArray','urcdArray','urcArray','ukArray','arrayUrc','category','countPop','currentDate'));
    }
}