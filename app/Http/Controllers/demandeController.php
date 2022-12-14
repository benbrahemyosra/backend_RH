<?php

namespace App\Http\Controllers;

use App\demande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB ;

class demandeController extends Controller
{
protected  $accepte   = '1';
protected  $refus    = '2';
protected  $attente    = '3';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $conges = DB::table('conges')
        ->where('status','attente')
        ->paginate(5);
        foreach ( $conges as $c) {
            $user = DB::table('users')
            ->where('id',$c->id_employee)
            ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS Employe"))
            ->get();
            $typeconge = DB::table('typeconges')
            ->where('actif',$c->code_typeC )
            ->select("*")
            ->get();
           if( $typeconge !=[]) {
            $c->TypeConge=$typeconge[0]->name;
            $c->Employe=$user[0]->Employe;
           }else{
            $c->Employe='';
           }
           }
          
        return response()->json($conges,200);
        }

    public function demande_modification()
    {
        $conges = DB::table('demandes')
            ->where('status_demand','modification')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_creation()
    {
        $demandes = DB::table('demandes')
            ->where('status_demand','creation')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_suppression()
    {
        $demandes = DB::table('demandes')
            ->where('status_demand','suppression')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_accept()
    {
        $demandes = DB::table('demandes')
            ->where('status','accept')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_accept_creation()
    {
        $demandes = DB::table('demandes')
            ->where('status','accept')
            ->where('status_demand','creation')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_accept_modification()
    {
        $conges = DB::table('demandes')
            ->where('status','accept')
            ->where('status_demand','modification')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_attent()
    {
        $demandes = DB::table('demandes')
            ->where('status','attente')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_attent_creation()
    {
        $conges = DB::table('demandes')
            ->where('status','attente')
            ->where('status_demand','creation')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_attent_modification()
    {
        $conges = DB::table('demandes')
            ->where('status','attente')
            ->where('status_demand','modification')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_refus()
    {
        $conges = DB::table('demandes')
            ->where('status','refus')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_refus_creation()
    {
        $conges = DB::table('demandes')
            ->where('status','refus')
            ->where('status_demand','creation')
            ->get();
            return response()->json($demandes,200);
    }

    public function demande_refus_modification()
    {
        $conges = DB::table('demandes')
            ->where('status','refus')
            ->where('status_demand','modification')
            ->get();
            return response()->json($demandes,200);
    }

    public function create()
    {
        //
    }     


    public function store(Request $request)
    {
        $demandes= new demande();
        $demandes->id_employee= $request->id; 
        $demandes->type_demande= $request->type_demande;
        $demandes->date_demande= Carbon::now() ;
        $demandes->objet= $request->objet;
        $demandes->status= 'attente';
        $demandes->status_demand= 'creation';
        $demandes->save();
        return response()->json('added succefuly'); 
    }


// SHOW ONE DEMANDE
    public function show($id)
    {
        $demandes=demande::findOrFail($id);
        return response()->json($demandes,200);
    }



// SHOW DEMANDE USER CONNECTE
    public function affiche_mes_demande()
    {
        $demandes = DB::table('demandes')
            ->where('id_employee',Auth::user()->id)
            ->get();
            return response()->json($demandes,200);
    }


    // SHOW DEMANDE USER   
    public function affiche_demande_user($id)
    {
        $demandes = DB::table('demandes')
            ->where('id_employee','=', $id)
            ->get();
            return response()->json($demandes,200) ;
    }


    // UPDATE DEMANDE 
    public function update(Request $request, $id)
    {
        $etatdemande = DB::table('demandes')
        ->where('id',$id)
        ->select('status')->value('status');
        while ($etatdemande == 'attent')
        {
        $demandes=demande::findOrFail($id);
        $demandes->id_employee= Auth::user()->id; 
        $demandes->type_demande= $request->type_demande;
        $demandes->date_demande= Carbon::now();
        $demandes->objet= $request->objet;
        $demandes->save();
       return response()->json('updated succefuly');
        }    
    }

    public function destroy($id)
    {
        $demandes=demande::findOrFail($id);
        $demandes->delete(); 
        return response()->json('deleted succefuly',200); 
    }

    public function destroy_demand($id)
    {
        //get conge where id 
        $demandes=demande::findOrFail($id);
        $demandes->status="accept" ; 
        $demandes->status_demand = 'suppression';
        return response()->json('send successfully',200); 
    }


    // ACCEPT CONGE
    public function AcceptDemande ($id, Request $request)
    { 
        $demandes=demande::findOrFail($id);
        $demandes->status='accept' ; 
        $demandes->date_reponse= Carbon::now();
        $demandes->save() ; 
        return response()->json('demande accepted',200); 
    }

// REFUS CONGE
    public function RefusDemande ($id, Request $request)
    {
        $demandes=demande::findOrFail($id);
        $demandes->status='refus'; 
        $demandes->date_reponse= Carbon::now();
        $demandes->save() ; 
        return response()->json('demande refused',200); 
    }

    
    public function search_demande(Request $request)
    {

        $name=$request->query('name');
        $date=$request->query('date');
        if($name!=""){
        $pieces = explode(" ", $name);
        $id = DB::table('users')
        ->where('first_name', '=',$pieces[0])
        ->where('last_name','=',$pieces[1])
        ->select('id')->value('id');
        $demande = DB::table('conges')
        ->where('status','attente')
        ->where('id_employee', '=',$id)
        ->paginate(5);
        }else{
            $demande = DB::table('conges')
            ->where('status','attente')
            ->paginate(5);
        }
        if($date!=""){
            $newDemandes= $demande->filter(function($item) use ($date){
                return $item->date_creation==$date; }); }
        if($date!=""){
            foreach ( $newDemandes as $c) {
                $user = DB::table('users')
                ->where('id',$c->id_employee)
                ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS Employe"))
                ->get();
                $typeconge = DB::table('typeconges')
                ->where('actif',$c->code_typeC )
                ->select("*")
                ->get();
               if( $typeconge !=[]) {
                $c->TypeConge=$typeconge[0]->name;
                $c->Employe=$user[0]->Employe;
               }
            }
            return response()->json($newDemandes,200); 

        }else{
        foreach ( $demande as $c) {
            $user = DB::table('users')
            ->where('id',$c->id_employee)
            ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS Employe"))
            ->get();
            $typeconge = DB::table('typeconges')
            ->where('actif',$c->code_typeC )
            ->select("*")
            ->get();
           if( $typeconge !=[]) {
            $c->TypeConge=$typeconge[0]->name;
            $c->Employe=$user[0]->Employe;
           }
            
        }
        
            return response()->json($demande,200); 

        }
   }
    

    public function verif_status($id)
    {
        $etatconge = DB::table('demandes')
        ->where('id',$id)
        ->select('status')->value('status');
        return response()->json($etatconge,200);
    }

    public function verif_demand($id)
    {
        $etatconge = DB::table('demandes')
        ->where('id',$id)
        ->select('status_demand')->value('status_demand');
        return response()->json($etatconge,200);
    }

}
