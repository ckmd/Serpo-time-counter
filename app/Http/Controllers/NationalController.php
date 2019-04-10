<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Excel;
use App\NationalData;
use DateTime;
use DateInterval;

class NationalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nationalDataForView = null;
        $rspsArray = null;
        $woArray = null;
        $chartArray = null;
        $cardArray = null;
        $urcdArray = null;
        $urcArray = null;
        $ukArray = null;
        return view('national.NationalView', compact('nationalDataForView', 'rspsArray','woArray','chartArray','cardArray','urcdArray','urcArray','ukArray'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datas = NationalData::all();
        return view('national.download', compact('datas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        NationalData::truncate();
        ini_set('memory_limit', '-1');
        $pAwal = $request->pawal;
        $pAkhir = $request->pakhir;
        $addOneDay = (new DateTime($pAkhir))->add(new DateInterval('P1D'))->format('Y-m-d');
        $datas = Excel::orderBy('wo_date','asc')->get()->where('wo_date','>=',$pAwal)->where('wo_date','<=',$addOneDay);
        $datasRsps1 = $datas->where('rsps',1);
        // Filter apabila hasil filter data yang berjumlah nol
        if($datas->count()==null){
            return redirect('national');
        }
        $totalWO = $datas->count();
        $region = $datas->pluck('region')->unique();
        $woArray = array();
        $rspsArray = array();
        $urcdArray = array();//Unique Root Cause Duration Array
        $urcArray = array();//Unique Root Cause Array
        $ukArray = array();//Unique Kendala Array
        foreach ($region as $key => $value) {
            $regionRow = $datas->where('region',$value);
            
//            $regionName = $regionRow->pluck('region')->unique();
            $regionSum = $regionRow->pluck('region')->count();

            // Digunakan untuk pembagi sesuai banyaknya total_durasi
            // $avgTotalDurasi = round($regionRow->pluck('total_durasi')->sum()/$regionRow->where('rsps',100)->pluck('region')->count(),2);
            $avgTotalDurasi = round($regionRow->pluck('total_durasi')->sum()/$regionSum,2);
            $avgDurasiSBU = round($regionRow->pluck('durasi_sbu')->sum()/$regionSum,2);
            $avgPrepTime = round($regionRow->pluck('prep_time')->sum()/$regionSum,2);
            $avgtravelTime = round($regionRow->pluck('travel_time')->sum()/$regionSum,2);
            $avgWorkTime = round($regionRow->pluck('work_time')->sum()/$regionSum,2);
            $avgRSPS = round($regionRow->pluck('rsps')->sum()/$regionSum,4);

            $nationalData = new NationalData();
                $nationalData->region = $value;
                $nationalData->jumlah_wo = $regionSum;
                $nationalData->total_durasi = $avgTotalDurasi;
                $nationalData->durasi_sbu = $avgDurasiSBU;
                $nationalData->prep_time = $avgPrepTime;
                $nationalData->travel_time = $avgtravelTime;
                $nationalData->work_time = $avgWorkTime;
                $nationalData->rsps = $avgRSPS;
            $nationalData->save();

            $regionRowRsps1 = $datasRsps1->where('region',$value);
            $regionSumRsps1 = $regionRowRsps1->pluck('region')->count();

            if($regionSumRsps1 < 1){
                $nationalDataRsps1[] = array(
                    'region' => $value,
                    'jumlah_wo' => $regionSumRsps1,
                    'total_durasi' => 0,
                    'durasi_sbu' => 0,
                    'prep_time' => 0,
                    'travel_time' => 0,
                    'work_time' => 0,
                    'rsps' => 0
                );
            }else{
                // Menghitung Rata2 RSPS 100 Starts Here
                $nationalDataRsps1[] = array(
                    'region' => $value,
                    'jumlah_wo' => $regionSumRsps1,
                    'total_durasi' => round($regionRowRsps1->pluck('total_durasi')->sum()/$regionSumRsps1,2),
                    'durasi_sbu' => round($regionRowRsps1->pluck('durasi_sbu')->sum()/$regionSumRsps1,2),
                    'prep_time' => round($regionRowRsps1->pluck('prep_time')->sum()/$regionSumRsps1,2),
                    'travel_time' => round($regionRowRsps1->pluck('travel_time')->sum()/$regionSumRsps1,2),
                    'work_time' => round($regionRowRsps1->pluck('work_time')->sum()/$regionSumRsps1,2),
                    'rsps' => round($regionRowRsps1->pluck('rsps')->sum()/$regionSumRsps1,4)
                );
            }    
            
            $rspsArray[] = array('y' => $avgRSPS*100, 'label'=>$value);
            $woArray[] = array('label'=>$value, 'y'=>$regionSum/$totalWO*100);
        }
        // Kalkulasi data pada card starts here
        $cardArray = array(
            'regionSum' => $totalWO,
            'avgTotalDurasi'=> round($datas->pluck('total_durasi')->sum()/$datas->where('rsps', 1)->count(),2),
            'avgDurasiSBU' => round($datas->pluck('durasi_sbu')->sum()/$totalWO,2),
            'avgPrepTime' => round($datas->pluck('prep_time')->sum()/$totalWO,2),
            'avgTravelTime' => round($datas->pluck('travel_time')->sum()/$totalWO,2),
            'avgWorkTime' => round($datas->pluck('work_time')->sum()/$totalWO,2),
            'avgRSPS' => round($datas->pluck('rsps')->sum()/$totalWO,4)
        );
        // Kalkulasi data pada cart ends here
        // Menghitung Trend Performa / Bulan starts here
        $chartArray = array();
        $trendArray = array();
        $dateTemp = null;
        // Merubah Menjadi array untuk menghemat database
        foreach ($datas as $key => $data) {
            $month = date_format(new DateTime($data->wo_date),"Y-m");
            $rsps = $data->rsps;
            // echo $data->wo_date.' : '.$rsps.'<br>';
            $trendArray[] = array('month' => $month, 'rsps'=>$rsps*100);
        }
        $uniqueMonth = array_unique(array_column($trendArray, 'month'));
        foreach ($uniqueMonth as $um) {
            $counter = 0;
            $result = 0;
            foreach ($trendArray as $ta) {
                if($ta['month']==$um){
                    $result += $ta['rsps'];
                    $counter++;
                }
            }
            $result = round($result/$counter,2);
            $chartArray[] = array('label'=>$um,'y'=>$result);
        }
        // Menghitung performa / bulan ends here   
        // Menghitung Root Cause dengan durasi Secara Nasional Starts Here
        $rootCaseDuration = $datas->where('total_durasi','<>','')->where('root_cause','<>','')->pluck('root_cause');
        $uniqueRootCaseDuration = $rootCaseDuration->unique();
        foreach ($uniqueRootCaseDuration as $urcd => $urcdName) {
            $urcdValue = $datas->where('total_durasi','<>','')->where('root_cause',$urcdName)->count();
            $urcdDuration = round($datas->where('total_durasi','<>','')->where('root_cause',$urcdName)->pluck('total_durasi')->sum()/$urcdValue,2);
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
        // Menghitung Root Cause dengan durasi Secara Nasional Ends Here
        // Menghitung Root Cause tanpa durasi starts here
        $uniqueRootCase = $datas->where('total_durasi','=','')->pluck('root_cause')->unique();
        foreach ($uniqueRootCase as $urc => $urcName) {
            $urcValue = $datas->where('total_durasi','=','')->where('root_cause',$urcName)->count();
            if($urcName!=""){
                $urcArray[] = array( 
                    'label' =>$urcName,
                    'y'=>$urcValue,
                );
            }
        }
        array_multisort (array_column($urcArray, 'y'), SORT_DESC, $urcArray);
        // Menghitung Root Cause tanpa durasi ends here
        // Menghitung Kendala Secara Nasional Starts Here
        $kendala = $datas->where('kendala','<>','')->pluck('kendala');
        $uniqueKendala = $kendala->unique();
        foreach ($uniqueKendala as $ukKey => $ukName) {
            $ukValue = $datas->where('kendala',$ukName)->count();
            if($ukName!=""){
                $ukArray[] = array( 
                    'label' =>$ukName,
                    'y'=>$ukValue/$kendala->count()*100,
                    'total'=>$ukValue,
                );
            }
        }
        array_multisort (array_column($ukArray, 'y'), SORT_DESC, $ukArray);
        // Menghitung Kendala Secara Nasional Ends Here
     
        $nationalDataForView = NationalData::all();
        return view('national.NationalView', compact('nationalDataRsps1','nationalDataForView', 'rspsArray','woArray','pAwal','pAkhir','chartArray','cardArray','urcdArray','urcArray','ukArray'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
