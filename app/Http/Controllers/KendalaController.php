<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kendala;
use App\DataKendala;
use App\Excel;
use DateTime;
use DateInterval;
use Gate;

class KendalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Gate::allows('isAdmin')){
            abort(404, "Sorry, You have no permission");
        }

        $kendalas = Kendala::orderBy('kategori_kendala','asc')->orderBy('parameter','asc')->paginate(10);
        return view('kendala.index', compact('kendalas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datas = DataKendala::all();
        return view('kendala.download', compact('datas'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Kendala::create($request->all());
        return back();
    }

    public function kendalaData($label, $region, $pAwal, $pAkhir){
        DataKendala::truncate();
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
            $dataKendala = Excel::where('region' , $region)->get();
        }else{
            $dataKendala = Excel::all();
        }
        $dataKendala = $dataKendala->where('wo_date','>=',$pAwal)->where('wo_date','<=',$addOneDay)
        ->where('kendala',$label);
        foreach($dataKendala as $dk){
            $data = new DataKendala;
            $data->ar_id = $dk->ar_id;
            $data->prob_id = $dk->prob_id;
            $data->kode_wo = $dk->kode_wo;
            $data->region = $dk->region;
            $data->basecamp = $dk->basecamp;
            $data->serpo = $dk->serpo;
            $data->wo_date = $dk->wo_date;
            $data->durasi_sbu = $dk->durasi_sbu;
            $data->prep_time = $dk->prep_time;
            $data->travel_time = $dk->travel_time;
            $data->work_time = $dk->work_time;
            $data->rsps = $dk->rsps;
            $data->total_durasi = $dk->total_durasi;
            $data->root_cause = $dk->root_cause;
            $data->kendala = $dk->kendala;
            $data->root_cause_description = $dk->root_cause_description;
            $data->kendala_description = $dk->kendala_description;
            $data->save();
        }
        $dataKendala = DataKendala::paginate(50);
        if($region=='national'){
            $label = 'National '.$label;
        }
        return view('kendala.data', compact('dataKendala','label'));
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
        $kendala = Kendala::findOrFail($request->kendala_id);
        $kendala->delete();
        return redirect('/kendala');
    }
}
