<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPExcel_IOFactory;
use DateTime;
use App\Excel;

class excelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('excel');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datas = Excel::all();
        return view('download', compact('datas'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        // in case maks upload to server 2MB, dirubah ke 4MB
        // ini_set('upload_max_filesize', '20M');
        
        // Delete Database Sebelum Upload Baru
        Excel::truncate();
        
        // maksimum time limit 900 seconds, bisa disesuaikan
        ini_set('max_execution_time', 900);
        
        function getDateTime($code, $paramsArray){
            $tempArray = array();
            for ($j=0; $j < strlen($paramsArray)/20 ; $j++) {
                $start = $j*20;
                $tempArray[$code.$j] = substr($paramsArray,$start,20);
            }
            return $tempArray;
        }

        function filterMinute($dateDiff){
            $value = null;
            if($dateDiff->d == 0 && $dateDiff->h == 0 && $dateDiff->i == 0 && $dateDiff->s == 0){
            }
            else{
                $value += $dateDiff->i + ($dateDiff->h * 60) + ($dateDiff->d * 24 * 60);
                if($dateDiff->s>0){
                    $value += ($dateDiff->s/60);
                }
            }    
            return $value;
        }

        $getSheet = null;
        $highestRow = null;
        require_once '../classes/PHPExcel/IOFactory.php';
        if(isset($_FILES['excelFile']) && !empty($_FILES['excelFile']['tmp_name']))
        {
            $excelObject = PHPExcel_IOFactory::load($_FILES['excelFile']['tmp_name']);
            $getSheet = $excelObject->getActiveSheet()->toArray(null);
            $highestRow = $excelObject->setActiveSheetIndex(0)->getHighestDataRow();
        }

        for ($i = 1; $i < $highestRow; $i++) { 
            if ($getSheet[$i][0] != '') {
                $arrayStartTravel = null;
                $arrayStartWork = null;
                $arrayComplete = null;
                $rsps = 0;
            // <!-- Menghitung Durasi SBU -->
            // <!-- Selisih Antara AR_Date dengan WO Date -->
                $SBU = null;
                $AR_Date = new DateTime($getSheet[$i][8]);
                $WO_Date = DateTime::createFromFormat('d M Y H:i:s',$getSheet[$i][9]);
                $SBU = date_diff($WO_Date, $AR_Date);
                $SBU = filterMinute($SBU);
                $rsps += 25;
                
                if($getSheet[$i][11]==''){
                    $prepTime = null;
                }else{
                    $stringStartTravel = str_replace(array( '(', ')' ), '', $getSheet[$i][11]);
                    $arrayStartTravel = getDateTime('st', $stringStartTravel);
                    $startTravel = new DateTime($arrayStartTravel['st0']);
                    $prepTime = round(filterMinute(date_diff($WO_Date, $startTravel)),2);
                    $rsps += 25;
                }
                
                $startWork = null;
                if($getSheet[$i][12]=='' || $getSheet[$i][11]==''){
                    $travelTime = null;
                }else{
                    $stringStartWork = str_replace(array( '(', ')' ), '', $getSheet[$i][12]);
                    $arrayStartWork = getDateTime('sw', $stringStartWork);
                    $startWork = new DateTime($arrayStartWork['sw0']);
                    $travelTime = date_diff($startTravel, $startWork);
                    $travelTime = round(filterMinute($travelTime),2);
                    $rsps += 25;
                }

                if($getSheet[$i][16]=='' || $getSheet[$i][12]==''){
                    $workTime = null;
                }else{
                    $stringComplete = str_replace(array( '(', ')' ), '', $getSheet[$i][16]);
                    $arrayComplete = getDateTime('cp',$stringComplete);
                    $complete = new DateTime($arrayComplete['cp0']);
                    $workTime = date_diff($startWork, $complete);
                    $workTime = round(filterMinute($workTime),2);
                    $rsps += 25;
                }

                // stop clock starts here
                $stringStopClock = str_replace(array( '(', ')' ), '', $getSheet[$i][14]);
                $arrayStopClock = getDateTime('sc',$stringStopClock);
                
                if($arrayStartTravel != null && $arrayStartWork != null && $arrayComplete != null){
                    $arrayMerge = array_merge($arrayStartTravel, $arrayStartWork, $arrayComplete);
                    foreach ($arrayStopClock as $key => $value) {
                        $tempAm = array();
                        foreach ($arrayMerge as $am => $arr) {
                            if($arr > $value){
                                $tempSCValue = round(filterMinute(date_diff(new DateTime($arr),new DateTime($value))),2);
                                $tempAm[$am] = $tempSCValue;
                            }
                        }
                        $minValue = round(min($tempAm),2);
                        $indeks = array_search(min($tempAm),$tempAm);
                        if($indeks == 'st0' && $prepTime > $minValue){
                            $prepTime -= $minValue;
                        }else if(substr($indeks,0,2)=='st' && $travelTime > $minValue){
                            $travelTime -= $minValue;
                        }else if(substr($indeks,0,2)=='sw' && $workTime > $minValue){
                            $workTime -= $minValue;
                        }
                    }
                }

             $data = new Excel();
                $data->ar_id = $getSheet[$i][0];
                $data->prob_id = $getSheet[$i][1];
                $data->kode_wo = $getSheet[$i][2];
                $data->region = $getSheet[$i][5];
                $data->basecamp = $getSheet[$i][6];
                $data->serpo = $getSheet[$i][7];
                $data->wo_date = $WO_Date;
                $data->durasi_sbu = $SBU;
                $data->prep_time = $prepTime;
                $data->travel_time = $travelTime;
                $data->work_time = $workTime;
                $data->rsps = $rsps;
             $data->save();
            }
        }
        return redirect()->route('allData.index');
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
