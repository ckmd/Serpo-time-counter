<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Excel;
use App\avgExcel;

class DBController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $pAwal = $request->awal;
        $pAkhir = $request->akhir;
        $region = "Rataan ".$request->region;

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
        $dbAvgExcel = avgExcel::orderBy('basecamp','asc')->get();
        return view('avgDownload', compact('nameFile','dbAvgExcel'));
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
