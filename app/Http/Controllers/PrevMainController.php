<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPExcel_IOFactory;
use App\PrevMain;
use App\Asset;
use App\Report;
use App\KategoriPm;
use Gate;

class PrevMainController extends Controller
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
        return view('prevMain.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        function findKategoriPM($desc){
            $kategoriPMConclusion = null;
            $kategoriPM = KategoriPm::get();
            
            if($kategoriPM->count()!=null){
                $uniqueKategoriPM = $kategoriPM->pluck('kategori_pm')->unique();

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

        $getSheet = null;
        $highestRow = null;
        require_once '../Classes/PHPExcel/IOFactory.php';
        if(isset($_FILES['excelFile']) && !empty($_FILES['excelFile']['tmp_name']))
        {
            $excelObject = PHPExcel_IOFactory::load($_FILES['excelFile']['tmp_name']);
            $getSheet = $excelObject->getActiveSheet()->toArray(null);
            $highestRow = $excelObject->setActiveSheetIndex(0)->getHighestDataRow();
        }
        $assets = Asset::all();
        for ($i=1; $i < $highestRow; $i++) {
            $filteredWO = PrevMain::where('wo_code',$getSheet[$i][3])->value('wo_code');
            // seleksi untuk menyimpan daftar PM yang unik
            if($filteredWO!=$getSheet[$i][3]){
                // Code untuk memecah Asset menjadi asset code dan asset description
                $asset = explode(" ", $getSheet[$i][6]);
                $assetCode = $asset[0];
                $assetCodeDesc = $asset[1]." ".$asset[2];
                
                $categoryPM = findKategoriPM($getSheet[$i][4]);
                $categoryPOP = null;
                if($categoryPM == "PM POP"){
                    $categoryPOP = findPOP($assetCode);
                }
                $prevMantData = new PrevMain(); 
                $prevMantData->status = $getSheet[$i][0];
                $prevMantData->scheduled_date = $getSheet[$i][1];
                $prevMantData->duration = $getSheet[$i][2];
                $prevMantData->wo_code = $getSheet[$i][3];
                $prevMantData->description = $getSheet[$i][4];
                $prevMantData->wo_date = $getSheet[$i][5];
                $prevMantData->asset_code = $assetCode;
                $prevMantData->asset_code_desc = $assetCodeDesc;
                $prevMantData->material_code = $getSheet[$i][7];
                $prevMantData->classification = $getSheet[$i][8];
                $prevMantData->child_asset = $getSheet[$i][9];
                $prevMantData->address = $getSheet[$i][10];
                $prevMantData->region = $getSheet[$i][11];
                $prevMantData->basecamp = $getSheet[$i][12];
                $prevMantData->serpo = $getSheet[$i][13];
                $prevMantData->company = $getSheet[$i][14];
                $prevMantData->category_pm = $categoryPM;
                $prevMantData->category_pop = $categoryPOP;
                $prevMantData->save();
            }
        }
        return redirect('prevMainData');
    }
    
    public function data(){
        $datas = PrevMain::paginate(100);
        return view('prevMain.data', compact('datas'));
    }

    public function reportRegion($region){
        $datas = Asset::where('sbu', $region)->get();
        $arrayRegion = array();
        $totalPM = 0;
        foreach ($datas as $data) {
            $PM = PrevMain::where('category_pm', 'PM POP')->where('asset_code', $data->site_id)->count();
            if($PM != 0){
                $totalPM += $PM;
                $arrayRegion[] = array(
                    'site_id' => $data->site_id,
                    'site_name' => $data->site,
                    'category' => $data->type,
                    'pm' => $PM
                );
            }
        }
        array_multisort (array_column($arrayRegion, 'pm'), SORT_DESC, $arrayRegion);        

        return view('prevMain.reportRegion', compact('region', 'arrayRegion', 'totalPM'));
    }

    public function reportDataSite($asset_code){
        $title = Asset::where('site_id',$asset_code)->first(); // + nama asset + region
        $datas = PrevMain::where('category_pm', 'PM POP')->where('asset_code', $asset_code)->get();
        return view('prevMain.PMsite', compact('datas', 'title'));
    }

    public function pmFOC($region){
        $datas = Asset::where('sbu', $region)->get();
        $arrayRegion = array();
        $totalPMFOC = 0;
        foreach ($datas as $data) {
            $PM = PrevMain::where('category_pm', 'PM FOC')->where('asset_code', $data->site_id)->count();
            if($PM != 0){
                $totalPMFOC += $PM;
                $arrayRegion[] = array(
                    'site_id' => $data->site_id,
                    'site_name' => $data->site,
                    'category' => $data->type,
                    'pm' => $PM
                );
            }
        }
        array_multisort (array_column($arrayRegion, 'pm'), SORT_DESC, $arrayRegion);        

        return view('prevMain.pmFOC', compact('region', 'arrayRegion', 'totalPMFOC'));
    }

    public function pmLain($region){
        $datas = Asset::where('sbu', $region)->get();
        $arrayRegion = array();
        $totalPMLain = 0;
        foreach ($datas as $data) {
            $PM = PrevMain::where('category_pm', 'Lain - Lain')->where('asset_code', $data->site_id)->count();
            if($PM != 0){
                $totalPMLain += $PM;
                $arrayRegion[] = array(
                    'site_id' => $data->site_id,
                    'site_name' => $data->site,
                    'category' => $data->type,
                    'pm' => $PM
                );
            }
        }
        array_multisort (array_column($arrayRegion, 'pm'), SORT_DESC, $arrayRegion);        

        return view('prevMain.pmLain', compact('region', 'arrayRegion', 'totalPMLain'));
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
