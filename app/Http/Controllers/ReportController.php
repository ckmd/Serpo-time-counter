<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\PrevMain;
use App\Asset;
use DateTime;
use DateInterval;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $report = null;
        return view('prevMain.report', compact('report'));
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

    public function download(Request $request){
        $pAwal = $request->awal;
        $pAkhir = $request->akhir;
        $region = "Report PM ";

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
        $datas = Report::all();
        return view('prevMain.download', compact('datas', 'nameFile'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Report::truncate();
        $pAwal = $request->pawal;
        $pAkhir = $request->pakhir;
        $addOneDay = (new DateTime($pAkhir))->add(new DateInterval('P1D'))->format('Y-m-d');

        $datas = PrevMain::get()->where('scheduled_date','>=',$pAwal)->where('scheduled_date','<=',$addOneDay);
        // return $datas;
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
        return view('prevMain.report', compact('report', 'pAwal', 'pAkhir'));
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
