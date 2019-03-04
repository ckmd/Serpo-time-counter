<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Excel;
use App\Gangguan;
use App\Kendala;
use App\avgExcel;
use App\DataGangguan;
use DateTime;
use DateInterval;

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
                $rootCauseConclusion = "Lain";
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
                $kendalaConclusion = "Lain";
            }
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

    public function gangguanData($label, $region, $pAwal, $pAkhir){
        DataGangguan::truncate();
        function removeStar($star){
            if($star=='*'){
                $star = NULL;
            }
            return $star;
        }
        $pAwal = removeStar($pAwal);
        $pAkhir = removeStar($pAkhir);
        $addOneDay = null;
        $addOneDay = (new DateTime($pAkhir))->add(new DateInterval('P1D'))->format('Y-m-d');
        $dataGangguan = Excel::where('region' , $region)->get();
        $dataGangguan = $dataGangguan->where('wo_date','>=',$pAwal)->where('wo_date','<=',$addOneDay)
        ->where('root_cause',$label)->where('rsps','=','100');
        foreach($dataGangguan as $dg){
            $data = new DataGangguan;
            $data->ar_id = $dg->ar_id;
            $data->prob_id = $dg->prob_id;
            $data->kode_wo = $dg->kode_wo;
            $data->region = $dg->region;
            $data->basecamp = $dg->basecamp;
            $data->serpo = $dg->serpo;
            $data->wo_date = $dg->wo_date;
            $data->durasi_sbu = $dg->durasi_sbu;
            $data->prep_time = $dg->prep_time;
            $data->travel_time = $dg->travel_time;
            $data->work_time = $dg->work_time;
            $data->rsps = $dg->rsps/100;
            $data->total_durasi = $dg->total_durasi;
            $data->root_cause = $dg->root_cause;
            $data->kendala = $dg->kendala;
            $data->root_cause_description = $dg->root_cause_description;
            $data->kendala_description = $dg->kendala_description;
            $data->save();
        }
        $dataGangguan = DataGangguan::all();
        return view('gangguan.data', compact('dataGangguan','label'));
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
