<?php

namespace App\Http\Controllers;

use App\infoprive;
use Illuminate\Http\Request;
use DB ;

class infopriveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allinfo= infoprive::all();
        return response()->json($allinfo);
    }

    public function getinfoprive( $id)
    {
        $infoprive=infoprive::findOrFail($id);
        return response()->json($infoprive);
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
        $infoprive= new infoprive();
        $infoprive->id_employee= $request->id_employee;
        $infoprive->adress= $request->adress;
        $infoprive->phone= $request->phone;
        $infoprive->num_bank= $request->num_bank;
        $infoprive->genre= $request->genre;
        $infoprive->civil= $request->civil;
        $infoprive->nationality= $request->nationality ;
        $infoprive->cin= $request->cin;
        $infoprive->date_birth= $request->date_birth;
        $infoprive->lieu_birth= $request->lieu_birth;
        $infoprive->country_birth= $request->country_birth;
        $infoprive->save();
       return response()->json('added succefuly ');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\infoprive  $infoprive
     * @return \Illuminate\Http\Response
     */
    public function show(infoprive $infoprive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\infoprive  $infoprive
     * @return \Illuminate\Http\Response
     */
    public function edit(infoprive $infoprive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\infoprive  $infoprive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $infoprive=infoprive::findOrFail($id);
        $infoprive->id_employee= $request->id_employee;
        $infoprive->adress= $request->adress;
        $infoprive->phone= $request->phone;
        $infoprive->num_bank= $request->num_bank;
        $infoprive->genre= $request->genre;
        $infoprive->civil= $request->civil;
        $infoprive->nationality= $request->nationality ;
        $infoprive->cin= $request->cin;
        $infoprive->date_birth= $request->date_birth;
        $infoprive->lieu_birth= $request->lieu_birth;
        $infoprive->country_birth= $request->country_birth;
        $infoprive->save();
       return response()->json('added succefuly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\infoprive  $infoprive
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $infoprive=infoprive::findOrFail($id);
        $infoprive->delete();
        return response()->json('deleted succefuly');
    }
}