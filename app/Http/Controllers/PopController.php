<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPExcel_IOFactory;
use App\Pop;
use Gate;
require_once '../Classes/PHPExcel/IOFactory.php';

class PopController extends Controller
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
        $pop = Pop::orderBy('sbu')->orderBy('location')->paginate(50);
        return view('pop.index', compact('pop'));
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
        ini_set('memory_limit', '-1');
        // Delete Database Sebelum Upload Baru
        Pop::truncate();
        
        // maksimum time limit 900 seconds, bisa disesuaikan
        ini_set('max_execution_time', 900);
        
        // Method untuk menrubah Selisih menjadi menit
        
        $getSheet = null;
        $highestRow = null;
        if(isset($_FILES['popFile']) && !empty($_FILES['popFile']['tmp_name']))
        {
            $excelObject = PHPExcel_IOFactory::load($_FILES['popFile']['tmp_name']);
            $getSheet = $excelObject->getActiveSheet()->toArray(null);
            $highestRow = $excelObject->setActiveSheetIndex(0)->getHighestDataRow();
        }
        
        for ($i = 1; $i < $highestRow; $i++) { 
            if ($getSheet[$i][0] != '') {
                // Filter By pop_id Starts Here
                $filteredData = Pop::where('pop_id',$getSheet[$i][1])->value('pop_id');
                if($filteredData!=$getSheet[$i][1]){

                // code untuk menyimpan ke db (tabel excel)
                $data = new Pop();
                    $data->pop_id = $getSheet[$i][1];
                    $data->pop_name = $getSheet[$i][2];
                    $data->sbu = $getSheet[$i][3];
                    $data->type = $getSheet[$i][4];
                    $data->location = $getSheet[$i][5];
                    $data->save();
                }
            }
        }
        return redirect()->route('pop.index');
        //
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

    public function delete()
    {
        // return 'wkwk';
        Pop::truncate();
        return redirect('pop');
        //
    }
}
