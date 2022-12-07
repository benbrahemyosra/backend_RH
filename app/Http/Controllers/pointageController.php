<?php

namespace App\Http\Controllers;

use App\pointage;
use Illuminate\Http\Request;
use DB ;
use Carbon\Carbon;

class pointageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allpointage= pointage::paginate(5);
        foreach ($allpointage as $p) {
            $user = DB::table('users')
            ->where('id',$p->id_employee)
            ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS full_name"))
            ->get();
            $p->Employe=$user[0]->full_name;}
        return response()->json($allpointage,200);

    
    }
    public function getPosition($id){
        $Currentdate = Carbon::now();
        $latestPoint = DB::table('pointages')
        ->where('id_employee', '=',$id)
        ->whereDate('date_debut', '=', $Currentdate)
        ->latest()
        ->get();
        if(count($latestPoint)==1){
            $latestPoint[0]->date_debut=substr($latestPoint [0]->date_debut,0, 10);
            return response()->json( $latestPoint,200); 

        }else{
            return response()->json( $latestPoint,200);
        }
           
       


    }
    
    public function search_pointage(Request $request)
    {
        $name=$request->name;
        if($name){
        $pieces = explode(" ", $name);
        $id = DB::table('users')
        ->where('first_name', '=',$pieces[0])
        ->where('last_name','=',$pieces[1])
        ->select('id')->value('id');
        }
        $datepoi =$request->date;
        $pointage = DB::table('pointages')
        ->Where('date_debut', '=',$datepoi)
        ->orWhere('id_employee', '=',$id)
        ->paginate(5);

        if ( $pointage) 
        { return response()->json($pointage,200); }
        else
        { return response()->json( "n'existe pas ." ,202); }
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
    {   $latestPoint = DB::table('pointages')
        ->where('id_employee', '=',$request->id)
        ->latest()
        ->select('date_debut')->value('date_debut');
        if ($latestPoint!="")
        {
            $date=substr($latestPoint,0, 10);
            $Currentdate = date('Y-m-d');
            if($date==$Currentdate)
            {
                return response()->json(true,200); 
            }
            else{
                $pointage= new pointage();
                $pointage->id_employee= $request->id;
                $pointage->position="2";
                $pointage->date_debut= $request->date;
                $pointage->save();
                return response()->json($pointage,200); 
                }
        }
        else{
                $pointage= new pointage();
                $pointage->id_employee= $request->id;
                $pointage->position="2";
                $pointage->date_debut= $request->date;
                $pointage->save();
                return response()->json($pointage,200); 
            }         
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\pointage  $pointage
     * @return \Illuminate\Http\Response
     */
    public function calcul_heures($id)
    {
        $pointage = DB::table('pointages')
        ->where('id', '=',$id)
        ->get(['date_debut','date_debut_pause']) ;  
        $datetime1 = $pointage->date_debut ; 
        $datetime2 = $pointage->date_fin ; 
        $interval = $datetime1->diff($datetime2);
        return response()->json($interval->format('%h')." Hours ".$interval->format('%i')." Minutes",200);  
    }


    public function show($id)
    {
        $pointage = DB::table('pointages')
        ->where('id_employee', '=',$id)
        ->paginate(5);
        return response()->json($pointage,200); 

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\pointage  $pointage
     * @return \Illuminate\Http\Response
     */
    public function edit(pointage $pointage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\pointage  $pointage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $pointage= pointage::findOrFail($id);
        switch($request->whoClicked){
            case "clickedPause": 
            $pointage->date_debut_pause= $request->date;
            $pointage->position="3";
            $pointage->save();
           break;
           case "clickedEndPause":
            $pointage->date_fin_pause= $request->date;
            $pointage->position="4";
            $pointage->save();
            break;
            case "clickedEndDay":
                $NbTotal = DB::table('parametres')
                ->select ('NbHeureJour')
                ->get();
                $pointage->date_fin= $request->date;
                $pointage->position="5";
                $pointage->NB_heures=$request->nbheure;
                $pointage->NB_heures_supplementaires=$request->nbheure-$NbTotal[0]->NbHeureJour ;
                $pointage->save();
                return response()->json($NbTotal );
    }
    return response()->json($pointage );
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\pointage  $pointage
     * @return \Illuminate\Http\Response
     */
    public function destroy(pointage $pointage)
    {
        //
    }
}
