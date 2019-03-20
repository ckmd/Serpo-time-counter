<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use PHPExcel_IOFactory;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('asset.index');
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
        // Asset::truncate();
        $getSheet = null;
        $highestRow = null;
        require_once '../Classes/PHPExcel/IOFactory.php';
        if(isset($_FILES['excelFile']) && !empty($_FILES['excelFile']['tmp_name']))
        {
            $excelObject = PHPExcel_IOFactory::load($_FILES['excelFile']['tmp_name']);
            $getSheet = $excelObject->getActiveSheet()->toArray(null);
            $highestRow = $excelObject->setActiveSheetIndex(0)->getHighestDataRow();
        }
        for ($i=1; $i < $highestRow; $i++) {
            if($getSheet[$i][0]!=null){
                $filteredWO = Asset::where('site_id',$getSheet[$i][1])->value('site_id');
                // seleksi untuk menyimpan daftar PM yang unik
                if($filteredWO!=$getSheet[$i][1]){
                    $datas = new Asset;
                    $datas->site_id = $getSheet[$i][1];
                    $datas->site = $getSheet[$i][2];
                    $datas->kota = $getSheet[$i][3];
                    $datas->propinsi = $getSheet[$i][4];
                    $datas->sbu = $getSheet[$i][5];
                    $datas->model = $getSheet[$i][6];
                    $datas->type = $getSheet[$i][7];
                    $datas->updated_time = $getSheet[$i][8];
                    $datas->updated_by = $getSheet[$i][9];
                    $datas->status = $getSheet[$i][10];
                    $datas->save();
                }
            }
        }    
        return redirect('assetData');
    }

    public function data(){
        $datas = Asset::paginate(100);
        return view('asset.data', compact('datas'));
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
