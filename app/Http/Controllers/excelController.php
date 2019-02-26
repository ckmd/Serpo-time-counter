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
        // in case maks upload to server 2MB, dirubah ke 20MB
        // ini_set('upload_max_filesize', '20M');
        ini_set('memory_limit', '-1');
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

        // Method untuk menrubah Selisih menjadi menit
        function filterMinute($dateDiff){
            $value = null;
            if($dateDiff->days == 0 && $dateDiff->h == 0 && $dateDiff->i == 0 && $dateDiff->s == 0){
            }
            else{
                $value += $dateDiff->i + ($dateDiff->h * 60) + ($dateDiff->days * 24 * 60);
                if($dateDiff->s>0){
                    $value += ($dateDiff->s/60);
                }
            }    
            return $value;
        }

        // AI Method, untuk menemukan konklusi dari beberapa kata
        function findRootCause($string){
            $rootCauseConclusion = null;
            $string = explode(" ", $string);
            $cause = array(
                'FOC' => array('foc','putus','core','kabel','cable','kable'),
                'FOT' => array('fot','comm'),
                'PS' => array('ps'),
            );
            $resultArray = array();
            foreach ($cause as $causeKey => $causeValue) {
                $causeResult = count(array_intersect($string, $causeValue));
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

        function findKendala($k){
            $kendalaConclusion = null;
            $k = explode(" ", $k);
            $kendalaDict = array(
                'tim' => array('tim','idle'),
                'cuaca' => array('cuaca','hujan','banjir'),
                'user' => array('user'),
            );
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
            return $kendalaConclusion;
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
                
                // Filter By kode_wo Starts Here
                $filteredDate = Excel::where('kode_wo',$getSheet[$i][2])->value('kode_wo');
                if($filteredDate!=$getSheet[$i][2]){
                    $SBU = date_diff($WO_Date, $AR_Date);
                    $SBU = filterMinute($SBU);
                    $rsps += 25;
                    
                    // Code untuk menghitung preparation time
                    if($getSheet[$i][11]==''){
                        $prepTime = null;
                    }else{
                        $stringStartTravel = str_replace(array( '(', ')' ), '', $getSheet[$i][11]);
                        $arrayStartTravel = getDateTime('st', $stringStartTravel);
                        $startTravel = new DateTime($arrayStartTravel['st0']);
                        $prepTime = round(filterMinute(date_diff($WO_Date, $startTravel)),2);
                        $rsps += 25;
                    }
                    
                    // Code untuk Menghitung Travel Time
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

                    // Code untuk menghitung Working time
                    if($getSheet[$i][16]=='' || $getSheet[$i][12]==''){
                        $workTime = null;
                    }else{
                        $stringStartWork = str_replace(array( '(', ')' ), '', $getSheet[$i][12]);
                        $arrayStartWork = getDateTime('sw', $stringStartWork);
                        $startWork = new DateTime($arrayStartWork['sw0']);

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
                            // Filter untuk anomalli data (timestamp stopclock diluar timestamp complete)
                            if(new DateTime($value) > $complete){
                                continue;
                            }else{
                                $tempAm = array();
                                foreach ($arrayMerge as $am => $arr) {
                                    if(new DateTime($arr) > new DateTime($value)){
                                        $tempSCValue = round(filterMinute(date_diff(new DateTime($value),new DateTime($arr))),2);
                                        $tempAm[$am] = $tempSCValue;
                                    }
                                }
                                $minValue = round(min($tempAm),2);
                                $indeks = array_search(min($tempAm),$tempAm);
                                // return $tempAm;
                                // return $key.' :: '.$value.' , '.$indeks.' :: '.$minValue;
                                if($indeks == 'st0' && $prepTime > $minValue){
                                    $prepTime -= $minValue;
                                }else if(substr($indeks,0,2)=='st' && $travelTime > $minValue){
                                    $travelTime -= $minValue;
                                }else if(substr($indeks,0,2)=='sw' && $workTime > $minValue){
                                    $workTime -= $minValue;
                                }
                            }
                        }
                    }

                //Menghitung Total durasi starts here
                $total_durasi = null;
                $root_cause = null;
                $kendala = null;
                if($rsps==100){
                    $total_durasi = $prepTime + $travelTime + $workTime;
                }
                if($getSheet[$i][23]!=null){
                    $root_cause = findRootCause($getSheet[$i][23]);
                }
                if($getSheet[$i][20]!=null){
                    $kendala = findKendala($getSheet[$i][20]);
                }
                // code untuk menyimpan ke db (tabel excel)
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
                    $data->total_durasi = $total_durasi;
                    $data->root_cause = $root_cause;
                    $data->kendala = $kendala;
                    $data->save();
                }
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
