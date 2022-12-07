<?php

namespace App\Http\Controllers;

use App\typedemande;
use Illuminate\Http\Request;
use DB ; 

class typedemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $alltype= typedemande::all();
        return response()->json($alltype);
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
        $typedemande= new typedemande();
        $typedemande->name=$request->name;
        $typedemande->actif=$request->actif;
        $typedemande->save();
        return response()->json('added succefuly');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\typedemande  $typedemande
     * @return \Illuminate\Http\Response
     */
    public function show(typedemande $typedemande)
    {
        $typedemande=typedemande::findOrFail($id);
        return response()->json($typedemande,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\typedemande  $typedemande
     * @return \Illuminate\Http\Response
     */

    public function edit(typedemande $typedemande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\typedemande  $typedemande
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $typedemande=typedemande::findOrFail($id);
        $typedemande->name= $request->name;
        $typedemande->actif= $request->actif;
        $typedemande->save();
        return response()->json('updated succefuly ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\typedemande  $typedemande
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $typedemande=typedemande::findOrFail($id);
        $typedemande->delete();
        return response()->json('deleted succefuly'); 
    }
}
