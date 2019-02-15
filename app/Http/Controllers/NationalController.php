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
        $totalWO = null;
        return view('NationalView', compact('nationalDataForView', 'rspsArray','woArray','totalWO'));
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
        $datas = Excel::all()->where('wo_date','>=',$pAwal)->where('wo_date','<=',$addOneDay);
        $totalWO = $datas->count();
        $region = $datas->pluck('region')->unique();
        $woArray = array();
        $rspsArray = array();
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
        $nationalDataForView = NationalData::all();
        return view('NationalView', compact('nationalDataForView', 'rspsArray','woArray','pAwal','pAkhir','totalWO'));
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
