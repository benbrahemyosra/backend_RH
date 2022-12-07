<?php

namespace App\Http\Controllers;

use App\typeemployee;
use Illuminate\Http\Request;

class typeemployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alltypeemployee= typeemployee::all();
        return response()->json($alltypeemployee,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $typeemployee= new typeemployee();
        $typeemployee->name= $request->name;
        $typeemployee->actif= $request->actif;
        $typeemployee->save();
        return response()->json('added succefuly',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\typeemployee  $typeemployee
     * @return \Illuminate\Http\Response
     */
    public function show(typeemployee $typeemployee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\typeemployee  $typeemployee
     * @return \Illuminate\Http\Response
     */
    public function edit(typeemployee $typeemployee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\typeemployee  $typeemployee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $typeemployee=typeemployee::findOrFail($id);
        $typeemployee->name= $request->name;
        $typeemployee->actif= $request->actif;
        $typeemployee->save();
        return response()->json('updated succefuly',200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\typeemployee  $typeemployee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typeemployee=typeemployee::findOrFail($id);
        $typeemployee->delete(); 
        return response()->json('deleted succefuly',200); 
    }
}
