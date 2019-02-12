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
        // ini_set('upload_max_filesize', '4M');
        
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

        for ($i = 1; $i <= 2; $i++) { 
            if ($getSheet[$i][0] != '') {
                $rsps = 0;
            // <!-- Menghitung Durasi SBU -->
            // <!-- Selisih Antara AR_Date dengan WO Date -->
                $SBU = null;
                $AR_Date = new DateTime($getSheet[$i][8]);
                $WO_Date = DateTime::createFromFormat('d M Y H:i:s',$getSheet[$i][9]);
                $SBU = date_diff($WO_Date, $AR_Date);
                $SBU = filterMinute($SBU);
                $rsps ++;
                
                // Adds on Start Here
                $stringStartTravel = str_replace(array( '(', ')' ), '', $getSheet[$i][11]);
                $arrayStartTravel = getDateTime('st', $stringStartTravel);
                
                $stringStartWork = str_replace(array( '(', ')' ), '', $getSheet[$i][12]);
                $arrayStartWork = getDateTime('sw', $stringStartWork);

                $stringStopClock = str_replace(array( '(', ')' ), '', $getSheet[$i][14]);
                $arrayStopClock = getDateTime('sc',$stringStopClock);

                $stringComplete = str_replace(array( '(', ')' ), '', $getSheet[$i][16]);
                $arrayComplete = getDateTime('cp',$stringComplete);
                
                // print_r($arrayStartTravel);
                // print_r($arrayStartWork);
                // print_r($arrayStopClock);
                // print_r($arrayComplete);
                $arrayMerge = array_merge($arrayStartTravel, $arrayStartWork, $arrayComplete);
                
                $startTravel = new DateTime($arrayStartTravel['st0']);
                $startWork = new DateTime($arrayStartWork['sw0']);
                $complete = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][16]),0,19));
                
                $prepTime = round(filterMinute(date_diff($WO_Date, $startTravel)),2);
                $travelTime = round(filterMinute(date_diff($startTravel, $startWork)),2);
                // baru, start working to complete
                $workTime = round(filterMinute(date_diff($startWork, $complete)),2);
                
                echo 'Data Ke '.$i.'<br>';
                foreach ($arrayStopClock as $key => $value) {
                    $tempAm = array();
                    foreach ($arrayMerge as $am => $arr) {
                        if($arr > $value){
                            $tempSCValue = filterMinute(date_diff(new DateTime($arr),new DateTime($value)));
                            $tempAm[$am] = $tempSCValue;
                        }
                    }
//                    print_r($tempAm);
                    $minValue = round(min($tempAm),2);
                    $indeks = array_search(min($tempAm),$tempAm);
                    if(substr($indeks,0,2)=='st'){
                        $travelTime -= $minValue;
                    }
                    if(substr($indeks,0,2)=='sw'){
                        $workTime -= $minValue;
                    }
                    // echo "{$key} => {$value} ";
                    // if($arr){                    }
                }
                echo 'Prep Time : '.$prepTime.'<br>';
                echo 'Travel Time : '.$travelTime.'<br>';
                echo 'Work Time : '.$workTime.'<br>';
                // Adds on end here
            // <!-- Menghitung Durasi Preparation -->
            // <!-- Selisih Antara WO Date dengan Start Driving -->
                $preparation = null;
                $start_driving = null;
                $start_driving = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][11]),0,19));
                if($getSheet[$i][11]=='' || $getSheet[$i][9]==''){
                    $preparation = date_diff($WO_Date, $WO_Date);
                }else{
                    $preparation = date_diff($start_driving, $WO_Date);
                    $rsps++;
                }
                
                $preparation = filterMinute($preparation);
            // <!-- Menghitung Durasi Travel Time -->
            // <!-- Selisih Antara Start Travel dengan Start Work -->
                $travel = null;
                $start_working = null;
                $start_working = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][12]),0,19));
                if($getSheet[$i][12]=='' || $getSheet[$i][11]==''){
                    $travel = date_diff($start_driving, $start_driving);
                }else{
                    $travel = date_diff($start_working, $start_driving);
                    $rsps++;
                }
                $travel = filterMinute($travel);
            // <!-- Menghitung Durasi Work Time -->
            // <!-- Selisih Antara Start Work dengan Request Complete -->
                $working = null;
                $req_complete = null;
                $req_complete = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][15]),0,19));
                if($getSheet[$i][15]=='' || $getSheet[$i][12]==''){
                    $working = date_diff($start_working, $start_working);
                }else{
                    $working = date_diff($req_complete, $start_working);
                    $rsps++;
                }
                $working = filterMinute($working);
            // <!-- Menghitung Durasi Reuest Complete Time -->
            // <!-- Selisih Antara Request Complete dengan Complete -->
                $complete_time = null;
                $complete = null;
                $complete = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][16]),0,19));
                if($getSheet[$i][16]=='' || $getSheet[$i][15]==''){
                    $complete_time = date_diff($req_complete, $req_complete);
                }else{
                    $complete_time = date_diff($complete, $req_complete);
                }
                $complete_time = filterMinute($complete_time);
                // Menghitung Stop CLock Time
                $sc_time = null;
                $stop_clock = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][14]),0,19));
                if($getSheet[$i][14]==''){
                    $sc_time = null;
                }else{
                    $diffPrepTime = 0;
                    $diffStartDriving = 0;
                    $diffStartWorking = 0;
                    $diffReqComplete = 0;
                    if($WO_Date > $stop_clock && $getSheet[$i][9]!=''){
                        $diffPrepTime = date_diff($WO_Date, $stop_clock);
                        $diffPrepTime = filterMinute($diffPrepTime);
                        if($sc_time < $diffPrepTime)
                            $sc_time = $diffPrepTime;
                    }
                    if($start_driving > $stop_clock && $getSheet[$i][11]!=''){
                        $diffStartDriving = date_diff($start_driving, $stop_clock);
                        $diffStartDriving = filterMinute($diffStartDriving);
                    }
                    if($start_working > $stop_clock && $getSheet[$i][12]!=''){
                        $diffStartWorking = date_diff($start_working, $stop_clock);
                        $diffStartWorking = filterMinute($diffStartWorking);
                    }
                    if($req_complete > $stop_clock && $getSheet[$i][15]!=''){
                        $diffReqComplete = date_diff($req_complete, $stop_clock);
                        $diffReqComplete = filterMinute($diffReqComplete);
                    }
                    $unsortedSCTime = array($diffPrepTime, $diffStartDriving, $diffStartWorking, $diffReqComplete);
                    $output = null;
                    foreach ($unsortedSCTime as $key => $value) {
                        if($value > 0) {
                            $output[$key] = $value;
                            $sc_time = min($output);
                        }
                    }
                }
            // <!-- Menghitung Semua End Here -->

             $data = new Excel();
                $data->ar_id = $getSheet[$i][0];
                $data->prob_id = $getSheet[$i][1];
                $data->kode_wo = $getSheet[$i][2];
                $data->region = $getSheet[$i][5];
                $data->basecamp = $getSheet[$i][6];
                $data->serpo = $getSheet[$i][7];
                $data->wo_date = $WO_Date;
                $data->durasi_sbu = $SBU;
                $data->prep_time = $preparation;
                $data->travel_time = $travel;
                $data->work_time = $working;
                $data->sc_time = $sc_time;
                $data->complete_time = $complete_time;
                $data->rsps = $rsps * 0.25;
             $data->save();
            }
        }
        // $datas = Excel::pluck('region');
        // $unique = $datas->unique();
        // return redirect()->route('allData.index');
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
