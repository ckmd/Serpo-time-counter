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
        Excel::truncate();
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
        function filterMinute($dateDiff){
            $value = 0;
            if($dateDiff->d == 0 && $dateDiff->h == 0 && $dateDiff->i == 0 && $dateDiff->s == 0){
            }
            else{
                $value += $dateDiff->i + ($dateDiff->h * 60) + ($dateDiff->d * 24 * 60);
                if($dateDiff->s>30){
                    $value += 1;
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

        for ($i = 1; $i < 100; $i++) { 
            if ($getSheet[$i][0] != '') {
            // <!-- Menghitung Durasi SBU -->
            // <!-- Selisih Antara AR_Date dengan WO Date -->
                $SBU = null;
                $AR_Date = new DateTime($getSheet[$i][8]);
                $WO_Date = DateTime::createFromFormat('d M Y H:i:s',$getSheet[$i][9]);
                $SBU = date_diff($WO_Date, $AR_Date);
                $SBU = filterMinute($SBU);
                
            // <!-- Menghitung Durasi Preparation -->
            // <!-- Selisih Antara WO Date dengan Start Driving -->
                $preparation = null;
                $start_driving = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][11]),0,19));
                if($getSheet[$i][11]=='' || $getSheet[$i][9]==''){
                    $preparation = date_diff($WO_Date, $WO_Date);
                }else{
                    $preparation = date_diff($start_driving, $WO_Date);
                }
                
                $preparation = filterMinute($preparation);
            // <!-- Menghitung Durasi Travel Time -->
            // <!-- Selisih Antara Start Travel dengan Start Work -->
                $travel = null;
                $start_working = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][12]),0,19));
                if($getSheet[$i][12]=='' || $getSheet[$i][11]==''){
                    $travel = date_diff($start_driving, $start_driving);
                }else{
                    $travel = date_diff($start_working, $start_driving);
                }
                $travel = filterMinute($travel);
            // <!-- Menghitung Durasi Work Time -->
            // <!-- Selisih Antara Start Work dengan Request Complete -->
                $working = null;
                $req_complete = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][15]),0,19));
                if($getSheet[$i][15]=='' || $getSheet[$i][12]==''){
                    $working = date_diff($start_working, $start_working);
                }else{
                    $working = date_diff($req_complete, $start_working);
                }
                $working = filterMinute($working);
            // <!-- Menghitung Durasi Reuest Complete Time -->
            // <!-- Selisih Antara Request Complete dengan Complete -->
                $complete_time = null;
                $complete = new DateTime(substr(str_replace(array( '(', ')' ), '', $getSheet[$i][16]),0,19));
                if($getSheet[$i][16]=='' || $getSheet[$i][15]==''){
                    $complete_time = date_diff($req_complete, $req_complete);
                }else{
                    $complete_time = date_diff($complete, $req_complete);
                }
                $complete_time = filterMinute($complete_time);
            // <!-- Menghitung Semua End Here -->
             $data = new Excel();
                $data->ar_id = $getSheet[$i][0];
                $data->prob_id = $getSheet[$i][1];
                $data->kode_wo = $getSheet[$i][2];
                $data->region = $getSheet[$i][5];
                $data->basecamp = $getSheet[$i][6];
                $data->serpo = $getSheet[$i][7];
                $data->durasi_sbu = $SBU;
                $data->prep_time = $preparation;
                $data->travel_time = $travel;
                $data->work_time = $working;
                $data->complete_time = $complete_time;
             $data->save();
            }
        }
        $datas = Excel::all();
        return view('table', compact('datas'));
        // return view('excel', compact('getSheet','highestRow'));
//        return back();
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
