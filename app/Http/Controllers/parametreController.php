<?php

namespace App\Http\Controllers;

use App\parametre;
use Illuminate\Http\Request;

class parametreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allparams= parametre::paginate(5);
        return response()->json($allparams);
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
        $params= new parametre();
        $params->NbJourSemaine= $request->NbJourSemaine;
        $params->NbCongeSolde= $request->NbCongeSolde;
        $params->NbHeureJour= $request->NbHeureJour;
        $params->MinNbHeure= $request->MinNbHeure;
        $params->save();
       return response()->json('added succefuly');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\parametre  $parametre
     * @return \Illuminate\Http\Response
     */
    public function show(parametre $parametre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\parametre  $parametre
     * @return \Illuminate\Http\Response
     */
    public function edit(parametre $parametre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\parametre  $parametre
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $params=parametre::findOrFail($id);
        $params->NbJourSemaine= $request->NbJourSemaine;
        $params->NbCongeSolde= $request->NbCongeSolde;
        $params->NbHeureJour= $request->NbHeureJour;
        $params->MinNbHeure= $request->MinNbHeure;
        $params->save();
       return response()->json('updated succefuly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\parametre  $parametre
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
                $params=parametre::findOrFail($id);
        $params->delete();
        return response()->json('deleted succefuly');
    }
}
