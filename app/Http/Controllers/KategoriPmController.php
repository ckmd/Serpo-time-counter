<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KategoriPm;
use App\PrevMain;
use App\Asset;

class KategoriPmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategoriPM = KategoriPm::orderBy('kategori_pm','asc')->orderBy('parameter','asc')->paginate(10);
        return view('kategoriPM.index', compact('kategoriPM'));
    }

    public function refreshPM(){
        function findKategoriPM($desc, $kategoriPM, $uniqueKategoriPM){
            $kategoriPMConclusion = null;
            if($kategoriPM->count()!=null){

                $desc = explode(" ", $desc);
                
                $kategoriPMDict = array();
                foreach ($uniqueKategoriPM as $ukKey => $ukValue) {
                    $kategoriPMDict[$ukValue] = $kategoriPM->where('kategori_pm','=',$ukValue)->pluck('parameter')->toArray();
                }
                
                $resultArray = array();
                foreach ($kategoriPMDict as $kdKey => $kdValue) {
                    $kResult = count(array_intersect($desc, $kdValue));
                    $resultArray[$kdKey] = $kResult;
                }
                $maxResult = max($resultArray);
                $indeksResult = array_search(max($resultArray),$resultArray);
            // Check Highest Root Cause
                if($maxResult>0){
                    $kategoriPMConclusion = $indeksResult;
                }else if($desc!=null){
                    $kategoriPMConclusion = "Lain - Lain";
                }
            }
        return $kategoriPMConclusion;
        }

        function findPOP($code){
            return Asset::where('site_id',$code)->value('type');
        }

        ini_set('max_execution_time', 900);
        $pmData = PrevMain::all();
        PrevMain::truncate();

        $kategoriPM = KategoriPm::get();
        $uniqueKategoriPM = $kategoriPM->pluck('kategori_pm')->unique();

        foreach($pmData as $e){
                $data = new PrevMain();
                $data->status = $e->status;
                $data->scheduled_date = $e->scheduled_date;
                $data->duration = $e->duration;
                $data->wo_code = $e->wo_code;
                $data->description = $e->description;
                $data->wo_date = $e->wo_date;
                $data->asset_code = $e->asset_code;
                $data->asset_code_desc = $e->asset_code_desc;
                $data->material_code = $e->material_code;
                $data->classification = $e->classification;
                $data->child_asset = $e->child_asset;
                $data->address = $e->address;
                $data->region = $e->region;
                $data->serpo = $e->serpo;
                $data->basecamp = $e->basecamp;
                $data->company = $e->company;
                if($e->description!=null){
                    $data->category_pm = findKategoriPM($e->description, $kategoriPM, $uniqueKategoriPM);
                }else{
                    $data->category_pm = null;
                }
                if($data->category_pm == "PM POP"){
                    $data->category_pop = findPOP($e->asset_code);
                }else{
                    $data->category_pop = null;
                }
                $data->save();        
            }
        return redirect('prevMainData');
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
        KategoriPm::create($request->all());
        return back();
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
        $data = KategoriPm::findOrFail($request->kategoriPM_id);
        $data->delete();
        return redirect('/kategoriPM');
    }
}
