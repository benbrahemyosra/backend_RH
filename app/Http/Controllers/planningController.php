<?php

namespace App\Http\Controllers;

use App\planning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class planningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allplanning= planning::paginate(5);
        return response()->json($allplanning,200);
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
        $planning= new planning();
        $planning->titre= $request->name;
        $planning->description= $request->description;
        $planning->type_planning= $request->type_Planning;
        $planning->date_debut= $request->start_date;
        $planning->date_fin= $request->end_date;
        $planning->liste_employees=json_encode($request->liste_employe);
        $planning->MinPersonne	=$request->MinPersonne;
        $planning->dateProd=$request->dateProd;
        $planning->save();
        return response()->json($planning,200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plannig=planning::findOrFail($id);
        return response()->json($plannig,200);
    }


    public function showPlanning(Request $request) 
    {
         
         $projets= DB::table('plannings')
         ->WhereBetween('date_debut',[$request->date_debut, $request->date_fin])
         ->orWhereBetween('dateProd',[$request->date_debut, $request->date_fin])
         ->whereJsonContains('liste_employees->id',$request->idUser)
         ->get();
         return ($projets);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function edit(planning $planning)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $planning=planning::findOrFail($id);
        $planning->titre= $request->name;
        $planning->description= $request->description;
        $planning->type_planning= $request->type_Planning;
        $planning->date_debut= $request->start_date;
        $planning->date_fin= $request->end_date;
        $planning->liste_employees=json_encode($request->liste_employe);
        $planning->MinPersonne=$request->MinPersonne;
        $planning->dateProd=$request->dateProd;
        $planning->save(); 
        return response()->json($planning, 200); 
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\planning  $planning
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plannig=planning::findOrFail($id);
        $plannig->delete(); 
        return response()->json('deleted succefuly',200); 
    }

    public function planningbyIdUser($id)
    {       $planning=DB::table('plannings')->get(); 
            $i=0 ; 
            $obj = array();
            foreach ( $planning as $p) 
            {  
             
             $ArrayLIsteEmp=json_decode($p->liste_employees, true);
             foreach ($ArrayLIsteEmp as $a)
              { if ($a['id'] == $id) { $obj[$i]=$p; $i++; } } 
            }
             return response()->json($obj,200);
          
    }

    public function search_planning(Request $request)
    {
         $date_debut=$request->query('date_debut');
         $date_fin=$request->query('date_fin');
         $type_planning=$request->query('type_planning');

         // get from database if name != null where name
        if($type_planning!="" ){
            $planning = DB::table('plannings')
             ->where('type_planning', '=',$type_planning)
             ->get();
  
        }else{ 
            //  if name == null so we getAll from database 
             $planning = DB::table('plannings')->get();  }

            //filter by status if != null
        if($date_debut!="" && $date_fin !=""){
            $newPlanning=$planning->filter(function($item) use ($date_debut,$date_fin){
                return $item->date_debut >= $date_debut && $item->date_fin <= $date_fin;
            });

        }else if($date_debut !="" && $date_fin ==""){
                $newPlanning=$planning->filter(function($item) use ($date_debut){
                    return $item->date_debut >= $date_debut ;
                });

        } else if($date_fin !="" && $date_debut ==""){
                $newPlanning=$planning->filter(function($item) use ($date_fin){
                    return $item->date_fin <= $date_fin;
                });
            }
        
    if($date_debut!="" || $date_fin !=""){
        return response()->json($newPlanning, 200);    
    }else{
        return response()->json($planning, 200);    

    }      
    }
}

  

