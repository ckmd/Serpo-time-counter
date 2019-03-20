<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPExcel_IOFactory;
use App\PrevMain;
use App\Asset;
use App\Report;

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
        $datas = Report::all();
        return view('prevMain.download', compact('datas'));
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
            // Keyword Sementara
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
                $rootCauseConclusion = "Lain - Lain";
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
        $assets = Asset::all();
        for ($i=1; $i < $highestRow; $i++) {
            $filteredWO = PrevMain::where('wo_code',$getSheet[$i][3])->value('wo_code');
            // seleksi untuk menyimpan daftar PM yang unik
            if($filteredWO!=$getSheet[$i][3]){
                // Code untuk memecah Asset menjadi asset code dan asset description
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
        }
        return redirect('prevMainData');
    }
    
    public function data(){
        $datas = PrevMain::paginate(100);
        return view('prevMain.data', compact('datas'));
    }

    public function report(){
        Report::truncate();
        $datas = PrevMain::all();
        $assets = Asset::all();
        $uniqueRegion = $datas->pluck('region')->unique();
        // return $uniqueRegion;
        $arrayPOP = array();
        foreach ($uniqueRegion as $urKey) {
            switch ($urKey) {
                case 'RINT':
                    $region = $assets->where('sbu', 'SBU MAKASSAR');
                    $sbu = 'SBU MAKASSAR';
                    break;
                case 'RSBS':
                    $region = $assets->where('sbu', 'SBU PALEMBANG');
                    $sbu = 'SBU PALEMBANG';
                    break;
                case 'RSBU':
                    $region = $assets->where('sbu', 'SBU MEDAN');
                    $sbu = 'SBU MEDAN';
                    break;
                case 'RBNT':
                    $region = $assets->where('sbu', 'SBU DENPASAR');
                    $sbu = 'SBU DENPASAR';
                    break;
                case 'RKAL':
                    $region = $assets->where('sbu', 'SBU BALIKPAPAN');
                    $sbu = 'SBU BALIKPAPAN';
                    break;
                case 'RJBR':
                    $region = $assets->where('sbu', 'SBU BANDUNG');
                    $sbu = 'SBU BANDUNG';
                    break;
                case 'RJTY':
                    $region = $assets->where('sbu', 'SBU SEMARANG');
                    $sbu = 'SBU SEMARANG';
                    break;
                case 'RSBT':
                    $region = $assets->where('sbu', 'SBU PEKANBARU');
                    $sbu = 'SBU PEKANBARU';
                    break;
                case 'ROJB':
                    $region = $assets->where('sbu', 'SBU JAKARTA');
                    $sbu = 'SBU JAKARTA';
                    break;
                case 'RJTM':
                    $region = $assets->where('sbu', 'SBU SURABAYA');
                    $sbu = 'SBU SURABAYA';
                    break;
            }

            $eachRegion = $datas->where('region',$urKey);
            $sumPOP = $eachRegion->where('category_pm', 'PM POP')->count();

            $assetPOPD = $region->where('type','POP D')->count();
            $sumPOPD = $eachRegion->where('category_pop', 'POP D')->count();
        
            $assetPOPB = $region->where('type','POP B')->count();
            $sumPOPB = $eachRegion->where('category_pop', 'POP B')->count();

            $assetPOPSB = $region->where('type','POP SB')->count();
            $sumPOPSB = $eachRegion->where('category_pop', 'POP SB')->count();

            $sumFOC = $eachRegion->where('category_pm', 'PM FOC')->count();
            $sumLain = $eachRegion->where('category_pm', 'Lain - Lain')->count();

            $report = new Report(); 
            $report->region = $sbu;
            $report->total_POP_asset = $region->count();
            $report->total_PM_POP = $sumPOP;
            $report->ratio_total = round($sumPOP/$region->count(),4);
            $report->asset_POP_D = $assetPOPD;
            $report->PM_POP_D = $sumPOPD;
            $report->ratio_POP_D = round($sumPOPD/$assetPOPD,4);
            $report->asset_POP_B = $assetPOPB;
            $report->PM_POP_B = $sumPOPB;
            $report->ratio_POP_B = round($sumPOPB/$assetPOPB,4);
            $report->asset_POP_SB = $assetPOPSB;
            $report->PM_POP_SB = $sumPOPSB;
            $report->ratio_POP_SB = round($sumPOPSB/$assetPOPSB,4);
            $report->PM_FOC = $sumFOC;
            $report->PM_lain = $sumLain;
            $report->save();
        }
        $report = Report::all();
        return view('prevMain.report', compact('report'));
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
