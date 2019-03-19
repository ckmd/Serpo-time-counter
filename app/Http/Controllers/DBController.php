<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Excel;
use App\Gangguan;
use App\Kendala;
use App\AvgExcel;
use App\DataGangguan;

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
            if($gangguan->count()!=null){

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
                $rootCauseConclusion = "Lain - Lain";
            }
        }
        return $rootCauseConclusion;
        }

        function findKendala($k, $kendala, $uniqueKendala){
            $kendalaConclusion = null;
            if($kendala->count()!=null){

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
                $kendalaConclusion = "Lain - Lain";
            }
        }
        return $kendalaConclusion;
        }
        
        ini_set('max_execution_time', 900);
        $excel = Excel::all();
        Excel::truncate();

        $gangguan = Gangguan::get();
        $uniqueGangguan = $gangguan->pluck('kategori_gangguan')->unique();

        $kendala = Kendala::get();
        $uniqueKendala = $kendala->pluck('kategori_kendala')->unique();

        foreach($excel as $e){
            // Old Method -- Find yang Membuat Berats, karena menciptakan query eksponensial
                // $id = $excel->find($e->id);
            // New Method is Much but Lighter
                $data = new Excel();
                $data->ar_id = $e->ar_id;
                $data->prob_id = $e->prob_id;
                $data->kode_wo = $e->kode_wo;
                $data->region = $e->region;
                $data->basecamp = $e->basecamp;
                $data->serpo = $e->serpo;
                $data->wo_date = $e->wo_date;
                $data->durasi_sbu = $e->durasi_sbu;
                $data->prep_time = $e->prep_time;
                $data->travel_time = $e->travel_time;
                $data->work_time = $e->work_time;
                $data->rsps = $e->rsps;
                $data->total_durasi = $e->total_durasi;
                if($e->root_cause_description!=null){
                    $data->root_cause = findRootCause($e->root_cause_description,$gangguan,$uniqueGangguan);
                }else{
                    $data->root_cause = null;
                }
                if($e->kendala_description!=null){
                    $data->kendala = findKendala($e->kendala_description, $kendala, $uniqueKendala);
                }else{
                    $data->kendala = null;
                }
                $data->root_cause_description = $e->root_cause_description;
                $data->kendala_description = $e->kendala_description;
                $data->save();        
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
        $dbAvgExcel = AvgExcel::orderBy('basecamp','asc')->get();
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
