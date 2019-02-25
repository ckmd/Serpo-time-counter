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
        return view('NationalView', compact('nationalDataForView', 'rspsArray','woArray','chartArray','cardArray','urcdArray','urcArray','ukArray'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $pAwal = $request->pawal;
        $pAkhir = $request->pakhir;
        $addOneDay = (new DateTime($pAkhir))->add(new DateInterval('P1D'))->format('Y-m-d');
        $datas = Excel::orderBy('wo_date','asc')->get()->where('wo_date','>=',$pAwal)->where('wo_date','<=',$addOneDay);
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

            $avgDurasiSBU = round($regionRow->pluck('durasi_sbu')->sum()/$regionSum,2);
            $avgPrepTime = round($regionRow->pluck('prep_time')->sum()/$regionSum,2);
            $avgtravelTime = round($regionRow->pluck('travel_time')->sum()/$regionSum,2);
            $avgWorkTime = round($regionRow->pluck('work_time')->sum()/$regionSum,2);
            $avgRSPS = round($regionRow->pluck('rsps')->sum()/$regionSum,2);

            $nationalData = new NationalData();
                $nationalData->region = $value;
                $nationalData->jumlah_wo = $regionSum;
                $nationalData->durasi_sbu = $avgDurasiSBU;
                $nationalData->prep_time = $avgPrepTime;
                $nationalData->travel_time = $avgtravelTime;
                $nationalData->work_time = $avgWorkTime;
                $nationalData->rsps = $avgRSPS;
            $nationalData->save();

            
            $rspsArray[] = array('y' => $avgRSPS, 'label'=>$value);
            $woArray[] = array('label'=>$value, 'y'=>$regionSum/$totalWO*100);
        }
        // Kalkulasi data pada card starts here
        $cardArray = array(
            'regionSum' => $totalWO,
            'avgDurasiSBU' => round($datas->pluck('durasi_sbu')->sum()/$totalWO,2),
            'avgPrepTime' => round($datas->pluck('prep_time')->sum()/$totalWO,2),
            'avgTravelTime' => round($datas->pluck('travel_time')->sum()/$totalWO,2),
            'avgWorkTime' => round($datas->pluck('work_time')->sum()/$totalWO,2),
            'avgRSPS' => round($datas->pluck('rsps')->sum()/$totalWO,2)
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
            $trendArray[] = array('month' => $month, 'rsps'=>$rsps);
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
        // Menghitung Kendala Secara Nasional Ends Here
     
        $nationalDataForView = NationalData::all();
        return view('NationalView', compact('nationalDataForView', 'rspsArray','woArray','pAwal','pAkhir','chartArray','cardArray','urcdArray','urcArray','ukArray'));
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
