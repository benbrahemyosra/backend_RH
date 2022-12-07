<?php

namespace App\Http\Controllers;

use App\tache;
use Illuminate\Http\Request;
use DB ;

class tacheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tache=tache::all();
        return response()->json($tache);
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
        $tache= new tache();
        $tache->titre= $request->titre;
        $tache->description= $request->description;
        $tache->date_debut= $request->start_date;
        $tache->date_fin= $request->end_date;
        $tache->id_employee= $request->id_Employe;
        $tache->id_planning= $request->idplanning;
        $tache->save();
        return response()->json($tache,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {    
        $tache = DB::table('taches')
        ->where('id_planning',$id)
        ->get();
        foreach ( $tache as $t) {
            $user = DB::table('users')
            ->where('id',$t->id_employee)
            ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS full_name"))
            ->get();
            $t->full_name=$user[0]->full_name;            }
        return response()->json($tache,200);

    }

    public function show1($id)
    {    
        $tache = DB::table('taches')
        ->where('id_employee',$id)
        ->get();
        return response()->json($tache,200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function edit(tache $tache)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tache=tache::findOrFail($id);
        $tache->titre= $request->titre;
        $tache->description= $request->description;
        $tache->date_debut= $request->start_date;
        $tache->date_fin= $request->end_date;
        $tache->id_employee= $request->id_Employe;
        $tache->save();
        return response()->json($tache,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\tache  $tache
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tache=tache::findOrFail($id);
        $tache->delete(); 
        return response()->json('deleted succefuly',200); 
    }
}
