<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPExcel_IOFactory;
use App\PrevMain;
use App\Asset;

class PrevMainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('prevMain.index');
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
        function findDescription($string){
            $rootCauseConclusion = null;
            $string = explode(" ", $string);
            $cause = array(
                'PM FOC' => array('Patroli'),
                'PM POP' => array('POP'),
            );
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
        PrevMain::truncate();
        $assets = Asset::all();
        for ($i=1; $i < $highestRow; $i++) {
            $asset = explode(" ", $getSheet[$i][6]);
            $assetCode = $asset[0];
            $assetCodeDesc = $asset[1]." ".$asset[2];
            
            $categoryPM = findDescription($getSheet[$i][4]);
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
        return redirect('prevMainData');
    }
    
    public function data(){
        $datas = PrevMain::paginate(100);
        return view('prevMain.data', compact('datas'));
    }

    public function popData(){
        $datas = PrevMain::all();
        $assets = Asset::all();
        $uniqueRegion = $datas->pluck('region')->unique();
        // return $uniqueRegion;
        $arrayPOP = array();
        foreach ($uniqueRegion as $urKey) {
            $eachRegion = $datas->where('region',$urKey);
            $sumEachRegion = $eachRegion->count();
            $sumPOP = $eachRegion->where('category_pm', 'PM POP')->count();
            if($sumPOP!=0){
                $popBdec = round($eachRegion->where('category_pop', 'POP B')->count()/$sumPOP,4);
                $popSBdec = round($eachRegion->where('category_pop', 'POP SB')->count()/$sumPOP,4);
                $popDdec = round($eachRegion->where('category_pop', 'POP D')->count()/$sumPOP,4);
            }else{
                $popBdec = 0;
                $popSBdec = 0;
                $popDdec = 0;
            }
            $arrayPOP[] = array(
                'region' => $urKey,
                'total_wo' => $sumEachRegion,
                'total_pop' => $sumPOP,
                'POP B' => $popBdec,
                'POP SB' => $popSBdec,
                'POP D' => $popDdec,
            );
        }
        return view('prevMain.popData', compact('arrayPOP'));
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
