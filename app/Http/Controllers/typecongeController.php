<?php

namespace App\Http\Controllers;

use App\typeconge;
use Illuminate\Http\Request;
use DB ; 
class typecongeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
                $alltypeConge= typeconge::paginate(5);
                return response()->json($alltypeConge);
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
        $typeconge= new typeconge();
        $typeconge->name= $request->name;
        $typeconge->actif= $request->actif;
        $typeconge->nbJourAn= $request->nbJourAn;
        $typeconge->MaxJourPris= $request->MaxJourPris;
        $typeconge->anciennete= $request->anciennete;
        $typeconge->maxHeureAuto= $request->maxHeureAuto;
        $typeconge->nbFoisMois= $request->nbFoisMois;
        $typeconge->save();
       return response()->json('added succefuly'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\typeconge  $typeconge
     * @return \Illuminate\Http\Response
     */
    public function show(typeconge $typeconge)
    {
        //
    }
    public function get( request $request)
    {
        $result = DB::table('typeconges')
        ->Where('name','=',$request->type)
        ->first();
       return response()->json($result); 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\typeconge  $typeconge
     * @return \Illuminate\Http\Response
     */
    public function edit(typeconge $typeconge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\typeconge  $typeconge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $typeconge=typeconge::findOrFail($id);
        $typeconge->name= $request->name;
        $typeconge->actif= $request->actif;
        $typeconge->save();
       return response()->json('updated succefuly'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\typeconge  $typeconge
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typeconge=typeconge::findOrFail($id);
        $typeconge->delete(); 
        return response()->json('deleted succefuly',200); 
    }
}
