<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gangguan;
use App\Excel;
use App\DataGangguan;
use DateTime;
use DateInterval;

class GangguanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gangguans = Gangguan::orderBy('kategori_gangguan','asc')->orderBy('parameter','asc')->paginate(10);
        return view('gangguan.index', compact('gangguans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datas = DataGangguan::all();
        return view('gangguan.download', compact('datas'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gangguan::create($request->all());
        return back();
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
        if($region!='national'){
            $dataGangguan = Excel::where('region' , $region)->get();
        }else{
            $dataGangguan = Excel::all();
        }
        $dataGangguan = $dataGangguan->where('wo_date','>=',$pAwal)->where('wo_date','<=',$addOneDay)
        ->where('root_cause',$label)->where('rsps','=','1');
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
            $data->rsps = $dg->rsps;
            $data->total_durasi = $dg->total_durasi;
            $data->root_cause = $dg->root_cause;
            $data->kendala = $dg->kendala;
            $data->root_cause_description = $dg->root_cause_description;
            $data->kendala_description = $dg->kendala_description;
            $data->save();
        }
        $dataGangguan = DataGangguan::paginate(50);
        if($region=='national'){
            $label = 'National '.$label;
        }
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
    public function destroy(Request $request)
    {
        $gangguan = Gangguan::findOrFail($request->gangguan_id);
        $gangguan->delete();
        return redirect('/gangguan');
    }
}
