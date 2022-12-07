<?php

namespace App\Http\Controllers;

use App\typeplanning;
use Illuminate\Http\Request;
use DB;

class typeplanningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alltypeplanning=typeplanning::paginate(5);
        return response()->json($alltypeplanning);
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
        $typeplanning= new typeplanning();
        $typeplanning->name= $request->name;
        $typeplanning->code= $request->code;
        $typeplanning->save();
        return response()->json('added succefuly'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\typeplanning  $typeplanning
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {   $typeplannig= DB::table('typeplannings')
        ->where('name', 'LIKE', $name)
        ->get();
        return response()->json($typeplannig); 

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\typeplanning  $typeplanning
     * @return \Illuminate\Http\Response
     */
    public function edit(typeplanning $typeplanning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\typeplanning  $typeplanning
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $typeplanning=typeplanning::findOrFail($id);
        $typeplanning->name= $request->name;
        $typeplanning->code= $request->code;
        $typeplanning->save();
        return response()->json('added succefuly'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\typeplanning  $typeplanning
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $typeplanning=typeplanning::findOrFail($id);
        $typeplanning->delete(); 
        return response()->json('deleted succefuly',200);
    }

}
