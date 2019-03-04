<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Excel;
use App\Gangguan;
use App\Kendala;
use App\avgExcel;

class DBController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Excel::paginate(100);
        return view('allData',compact('datas'));
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
    public function refresh(){
        function findRootCause($string, $gangguan, $uniqueGangguan){
            $rootCauseConclusion = null;
            $string = explode(" ", $string);

            $cause = array();
            foreach ($uniqueGangguan as $ugKey => $ugValue) {
                $cause[$ugValue] = $gangguan->where('kategori_gangguan','=',$ugValue)->pluck('parameter')->toArray();
            }
            $resultArray = array();
            foreach ($cause as $causeKey => $causeValue) {
                $causeResult = count(array_intersect($causeValue, $string));
                $resultArray[$causeKey] = $causeResult;
            }
            $maxResult = max($resultArray);
            $indeksResult = array_search(max($resultArray),$resultArray);

            // Check Highest Root Cause
            if($maxResult>0){
                $rootCauseConclusion = $indeksResult;
            }else if($string!=null){
                $rootCauseConclusion = "Lain";
            }
            return $rootCauseConclusion;
        }

        function findKendala($k, $kendala, $uniqueKendala){
            $kendalaConclusion = null;
            $k = explode(" ", $k);

            $kendalaDict = array();
            foreach ($uniqueKendala as $ukKey => $ukValue) {
                $kendalaDict[$ukValue] = $kendala->where('kategori_kendala','=',$ukValue)->pluck('parameter')->toArray();
            }

            $resultArray = array();
            foreach ($kendalaDict as $kdKey => $kdValue) {
                $kResult = count(array_intersect($k, $kdValue));
                $resultArray[$kdKey] = $kResult;
            }
            $maxResult = max($resultArray);
            $indeksResult = array_search(max($resultArray),$resultArray);
            // Check Highest Root Cause
            if($maxResult>0){
                $kendalaConclusion = $indeksResult;
            }else if($k!=null){
                $kendalaConclusion = "Lain";
            }
            return $kendalaConclusion;
        }

        ini_set('max_execution_time', 900);
        $excel = Excel::get();

        $gangguan = Gangguan::get();
        $uniqueGangguan = $gangguan->pluck('kategori_gangguan')->unique();

        $kendala = Kendala::get();
        $uniqueKendala = $kendala->pluck('kategori_kendala')->unique();

        foreach($excel as $e){
                $id = $excel->find($e->id);
                if($e->root_cause_description!=null){
                    $id->root_cause = findRootCause($e->root_cause_description,$gangguan,$uniqueGangguan);
                }
                if($e->kendala_description!=null){
                    $id->kendala = findKendala($e->kendala_description, $kendala, $uniqueKendala);
                }
                $id->save();
            }
        return redirect()->route('allData.index');
    }

    public function store(Request $request)
    {
        $pAwal = $request->awal;
        $pAkhir = $request->akhir;
        $region = "Rataan ".$request->region;

        if(($pAwal==null) && ($pAkhir==null)){
            $nameFile = $region." All Data";
        }
        elseif($pAwal==null){
            $nameFile = $region." awal s.d. ".$pAkhir;
        }
        elseif($pAkhir==null){
            $nameFile = $region." ".$pAwal." s.d. akhir";
        }
        else{
            $nameFile = $region." ".$pAwal." s.d ".$pAkhir;
        }
        $dbAvgExcel = avgExcel::orderBy('basecamp','asc')->get();
        return view('avgDownload', compact('nameFile','dbAvgExcel'));
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
