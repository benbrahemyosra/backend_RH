<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\conge;
use App\User;
use App\typeconge;
use App\Rapport;
use Carbon\Carbon;
use DB;
use Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class congesController extends Controller
{

protected  $STATUT_DEMANDE_AJOUT= 1;
//public static   $STATUT_DEMANDE_MODIFICATION = 2;
protected $STATUT_DEMANDE_MODIFICATION = 2;

protected $STATUT_CONGE_ACCEPT= 1;
protected $STATUT_CONGE_REFUS = 2;
protected $STATUT_CONGE_ATTENTE = 3;

protected  $accepte   = '1';
protected  $refus    = '2';
protected  $attente    = '3';



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

// GET ALL CONGES
    public function index(Request $request)
    {
        $date_debut=$request->query('date_debut');
        $date_fin=$request->query('date_fin');
        $ref=$request->query('ref');
        /*$users= DB::table ('users') 
        ->where('id', '=',$ref)
        ->get(); */
        if (($date_debut != NULL) || ($date_fin != NULL) || ($ref != NULL) ){
        $conges = DB::table('conges')
        ->where('id', '=',$ref)
        ->orWhere('date_start', '=',$date_debut)
        ->orWhere('date_end', '=',$date_fin)
        ->orWhereBetween('date_start',[$date_debut, $date_fin])
        ->orWhereBetween('date_end',[$date_debut, $date_fin])
        ->paginate(5) ; 
        return response()->json($conges,200); 
        }
        else
        {    $allconges=DB::table('conges')
            ->where('status','=','Acceptation')
            ->orWhere('status','=','Refus')
            ->paginate(5);
            foreach ( $allconges as $c) {
                $user = DB::table('users')
                ->where('id',$c->id_employee)
                ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS Employe"))
                ->get();
                $typeconge = DB::table('typeconges')
                ->where('actif',$c->code_typeC )
                ->select("*")
                ->get();
               if( $typeconge !=[]) {
                $c->Employe=$user[0]->Employe;
                $c->TypeConge=$typeconge[0]->name;
               }else{
                $c->Employe='';
                $c->TypeConge='';
               }
               }
              
                return response()->json($allconges); 
    }
    }
    public function test(Request $request)
    {
        $collection = collect(['taylor', 'abigail', null])->map(function ($name) {
            return strtoupper($name);
        })->reject(function ($name) {
            return empty($name);
        });
    }
    public function search_conges(Request $request)
    {
         $name=$request->query('name');
         $type=$request->query('type');
         $status=$request->query('status');
        // get from database if name != null where name
        if($name!="" ){
            $pieces = explode(" ", $name);
            $user = DB::table('users')
            ->where('first_name', '=',$pieces[0])
            ->where('last_name','=',$pieces[1])
            ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS Employe"))
            ->get();
            if($status =="" && $type=="" ){
                $conges = DB::table('conges')
                ->where('id_employee', '=',$user[0]->id)
                ->paginate(5);
            }else{
                $conges = DB::table('conges')
                ->where('id_employee', '=',$user[0]->id)
                ->get();
            }
        }else{ 
           //  if name == null so we getAll from database 
            $conges = DB::table('conges')->paginate(5);
          }

           //filter by status if != null
         if($status !="" && $status!=null){
            $newConges=$conges->filter(function($item) use ($status){
                return $item->status==$status; }); }

           // filter by type congé if != null 
            if($type!= ""){
            $idtype = DB::table('typeconges')
            ->where('name', '=',$request->query('type'))
            ->select('actif')->value('actif');
            if($status!= null){
            $newConges=$newConges->filter(function($item) use ($idtype){
                return $item->code_typeC== $idtype; });
            }else{$newConges=$conges->filter(function($item) use ($idtype){ return $item->code_typeC== $idtype; }); }  
}
           // if status or type != null so make a newarray and now we add attribut Employe and typeConge with string value  
         if($status!= "" || $type!="" ){
            foreach ( $newConges as $c) {
                $user = DB::table('users')
                ->where('id',$c->id_employee)
                ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS Employe"))
                ->get();
                $typeconge = DB::table('typeconges')
                ->where('actif',$c->code_typeC)
                ->select("*")
                ->get();
               if( $typeconge !=[]) {
                $c->Employe=$user[0]->Employe;
                $c->TypeConge=$typeconge[0]->name;
               }
               }
               return response()->json($newConges,200); 

        }else{
            // if status and type == null so we add attribut Emlpoye and typeConge with string value
            foreach ( $conges as $c) {
                $user = DB::table('users')
                ->where('id',$c->id_employee)
                ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS Employe"))
                ->get();
                $typeconge = DB::table('typeconges')
                ->where('actif',$c->code_typeC)
                ->select("*")
                ->get();
               if( $typeconge !=[]) {
                $c->Employe=$user[0]->Employe;
                $c->TypeConge=$typeconge[0]->name;
               }
               }    
            return response()->json($conges,200); 

        }
         
    } 

   
     public function congeByStatus(Request $request)
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

    public function test1(Request $request)
    {
        $allconges= conge::all();
        foreach ($allconges as $conge) {
     
             $id_emp=$conge->id_employee;
             $user= DB::table ('users') 
             ->where('id', '=',$id_emp)
             ->get();
             return response()->json($user,200); 
        }     
    }
/*
    public function select_user($id)
    {
        $users = DB::table('conges')
        ->join('users', 'users.id', '=',  $id)
        ->select('users.*', 'conges.*')
        ->get();
        return response()->json($users,200);
    }
*/
    public function conges_modification()
    {
        $conges = DB::table('conges')
            ->where('status_demand','modification')
            ->get();
            return response()->json($conges,200);
    }

    public function conges_creation()
    {
        $conges = DB::table('conges')
            ->where('status_demand','creation')
            ->get();
            return response()->json($conges,200);
    }

    public function conges_suppression()
    {
        $conges = DB::table('conges')
            ->where('status_demand','suppression')
            ->get();
            return response()->json($conges,200);
    }

    public function conges_accept()
    {
        $conges = DB::table('conges')
            ->where('status','accept')
            ->get();
            return response()->json($conges,200);
    }


    public function conges_accept_creation()
    {
        $conges = DB::table('conges')
            ->where('status','accept')
            ->where('status_demand','creation')
            ->get();
            return response()->json($conges,200);
    }


    public function conges_accept_modification()
    {
        $conges = DB::table('conges')
            ->where('status','accept')
            ->where('status_demand','modification')
            ->get();
            return response()->json($conges,200);
    }


    public function conges_attent()
    {
        $conges = DB::table('conges')
            ->where('status','attent')
            ->get();
            return response()->json($conges,200);
    }


    public function conges_attent_creation()
    {
        $conges = DB::table('conges')
            ->where('status','attent')
            ->where('status_demand','creation')
            ->get();
            return response()->json($conges,200);
    }


    public function conges_attent_modification()
    {
        $conges = DB::table('conges')
            ->where('status','attent')
            ->where('status_demand','modification')
            ->get();
            return response()->json($conges,200);
    }


    public function conges_refus()
    {
        $conges = DB::table('conges')
            ->where('status','refus')
            ->get();
            return response()->json($conges,200);
    }


    public function conges_refus_creation()
    {
        $conges = DB::table('conges')
            ->where('status','refus')
            ->where('status_demand','creation')
            ->get();
            return response()->json($conges,200);
    }

    public function conges_refus_modification()
    {
        $conges = DB::table('conges')
            ->where('status','refus')
            ->where('status_demand','modification')
            ->get();
            return response()->json($conges,200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    
// CALCULER LE NOMBRE DE JOURS 
   public static function NbJours($debut, $fin)
    {
        $tDeb = explode("-", $debut) ;
        $tFin = explode("-", $fin) ;
        $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
        mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
        return(($diff / 86400));
    }


// AJOUTER UN CONGE 
    public function store(Request $request)
    {
     
    try {
     $conges= new conge() ; 
     $conges->id_employee= $request->idUser;
     $conges->code_typeC= $request->code;
     $conges->date_start= $request->date_debutConge;
     $conges->date_end= $request->datefinConge;
     $conges->certificat=$request->image;
     $conges->nbJourPris= $request->nbJourEffectue;
     $conges->status= $request->result;
     $conges->description= $request->raison;
     $conges->date_creation=$request->date_creation;
     $conges->comment_response=$request->reponse;
     $conges->save();
    return response()->json('added succefuly',200);}
    catch (ModelNotFoundException $exception) {
    return back()->withError($exception->getMessage())->withInput();
    } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

// SHOW ONE CONGE 
    public function show($id)
    {
        $conges=conge::findOrFail($id);
        $user = DB::table('users')
        ->where('id',$conges->id_employee)
        ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS Employe"))
        ->get();
        $conges->name=$user[0]->Employe;
        $conges->adresse=$user[0]->email;
        $conges->date_debutConge=$conges->date_start;
        $conges->datefinConge=$conges->date_end;
        $conges->raison=$conges->description;
        $conges->nbJourEffectue=$conges->nbJourPris;
        $conges->result=$conges->status;
        $conges->reponse=$conges->comment_response;
        $conges->updateComment=$conges->commentUpdate;
        $typeconge = DB::table('typeconges')
        ->where('actif',$conges->code_typeC )
        ->select("*")
        ->get();
        $conges->typeconge=$typeconge[0]->name;
        return response()->json($conges,200);
    }

// SHOW CONGE USER CONNECTE
    public function affiche_my_conge($id)
    {
        $conges = DB::table('conges')
            ->where('id_employee',$id)
            ->paginate(5);
            foreach ( $conges as $c) {
                $typeconge = DB::table('typeconges')
                ->where('actif',$c->code_typeC )
                ->select("*")
                ->get();
               if( $typeconge !=[]) {
                $c->TypeConge=$typeconge[0]->name;
               }else{
                $c->Employe='';
               }
               }
              
            return response()->json($conges,200);
    }

// SHOW CONGE USER   
    public function affiche_conge_user($id)
    {
        $conges = DB::table('conges')
            ->where('id_employee','=', $id)
            ->get();
            return response()->json($conges,200) ;
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 //UPDATE CONGE
    public function update(Request $request,$id)
    {  $code=DB::table('typeconges')
        ->where('name','=',$request->codeTypeConge)
        ->select('actif')->value('actif'); 
        $etatconge = DB::table('conges')
        ->where('id',$id)
        ->select('status')->value('status');
        $conges=conge::findOrFail($id);
       
        if($request->result){
            $conges->status=$request->result;
            $conges->commentUpdate=$request->comment_reponse;
        }else{
            $conges->code_typeC=$code ;
            $conges->status=$request->status;
            $conges->certificat=$request->image;
            $conges->date_start= $request->date_start;
            $conges->date_end= $request->date_end;
            $conges->status= $etatconge;
            $conges->description= $request->description;
        }
       
        $conges->save();
        return response()->json($conges,200);
        
    }

    public function verif_status($id)
    {
        $etatconge = DB::table('conges')
        ->where('id',$id)
        ->select('status')->value('status');
        return response()->json($etatconge,200);
    }

    public function verif_demand($id)
    {
        $etatconge = DB::table('conges')
        ->where('id',$id)
        ->select('status_demand')->value('status_demand');
        return response()->json($etatconge,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


// DESTROY CONGE
    public function destroy($id)
    {
        //get conge where id 
        $conges=conge::findOrFail($id);
        $conges->delete(); 
        return response()->json('deleted succefuly',200); 
    }

    public function destroy_demand($id)
    {
        //get conge where id 
        $conges=conge::findOrFail($id);
        if ($conges->status == 'accept') 
        {
            $conges->status = 'attente';
            $conges->status_demand = 'suppression';
        }
        return response()->json($conges,200); 
    }


// ACCEPT CONGE
    public function AcceptConge ($id, Request $request)
    { 
        $conges=conge::findOrFail($id);
        $conges->status='accept' ; 
        $conges->status_demand='modification';
        $conges->comment_response= $request->comment_response; 
        $conges->save() ; 
        return response()->json('conge accepted',200); 
    }


// REFUS CONGE
    public function RefusConge ($id, Request $request)
    {
        $conges=conge::findOrFail($id);
        $conges->status='refus' ; 
        $conges->status_demand='modification';
        $conges->comment_response= $request->comment_response;
        $conges->save() ; 
        return response()->json('conge refused',200); 
    }

// sendemail_accept_conge
    public function sendemail_accept_conge()
    {
     $messages = "votre demande de congé a été validé par l`administrateur";
     Mail::raw($messages, function($message )
    { 
     $email = DB::table('conges')
       ->where('conges.id',11)
       ->join('users', 'users.id', '=', 'conges.id_employee')
       ->select('users.email')->value('users.email');
          $message->from('meriamdimassi115@gmail.com','GESTION DES CONGES');
          $message->to( $email)->subject('REPONSE DEMANDE CONGE');
    });
    }

// sendemail_refus_conge
    public function sendemail_refus_conge()
    {
     $messages = "votre demande de congé a été réfusé par l`administrateur";
     Mail::raw($messages, function($message )
     { 
     $email = DB::table('conges')
       ->where('conges.id',11)
       ->join('users', 'users.id', '=', 'conges.id_employee')
       ->select('users.email')->value('users.email');
          $message->from('meriamdimassi115@gmail.com','GESTION DES CONGES');
          $message->to( $email)->subject('REPONSE DEMANDE CONGE');
      });
    }
    
    
    public function search_conge(Request $request)
    {
         $date_debut=$request->query('date_debut');
         $date_fin=$request->query('date_fin');
         $ref=$request->query('ref');
         if (($date_debut != NULL) || ($date_fin != NULL) || ($ref != NULL) ){
         $conges = DB::table('conges')
         ->where('id', '=',$ref)
         ->orWhere('date_start', '=',$date_debut)
         ->orWhere('date_end', '=',$date_fin)
         ->orWhereBetween('date_start',[$date_debut, $date_fin])
         ->orWhereBetween('date_end',[$date_debut, $date_fin])
         ->paginate(5) ; 
         return response()->json($conges,200); 
         }
         else
         {         $allconges= conge::paginate(5);
                   return response()->json($allconges,200); }
    }

    public static function congesPris() 
    {
      $congesPris = DB::table('conges')
      ->where('id_employee',Auth::user()->id)
      ->where('status','accept')  
      ->sum('nbrJourPris');
      return ($congesPris) ;
    }

    public function congesRestants() 
    {
        $nb= $this->congesPris();
        return ($nb) ;
    }

   


        public function verifExistOtheridUserInProjetofSameVacation($projets,$date_debut,$date_fin) {
            $conge=DB::table('conges')
                            ->WhereBetween('date_start',[$date_debut, $date_fin])
                            ->orWhereBetween('date_end',[$date_debut, $date_fin])
                            ->get();
                             if(count($conge)>0){
                                foreach($conge as $c){
                                   $projet= $this->verifIdUserInPlanning($projets,$c->id_employee);
                                      return count($projet);
                                }
                             }else{
                                   return 0;
                             }
        }

        public function CalculHeureAuto (Request $request) 
        {
            $rapport= new Rapport ;
            $paramsHeure = DB::table('typeconges')
            ->where('actif','=',5)
            ->select('maxHeureAuto','nbFoisMois','id')
            ->get() ;
                 
            if ( ($request->nbHeure <= $paramsHeure[0]->maxHeureAuto)  && ($paramsHeure[0]->nbFoisMois > 0 ))
            { 
              $rapport->test= true;
              $rapport->result='Acceptation' ;
              $a = ($paramsHeure[0]->nbFoisMois)-1 ;
              $conges=conge::findOrFail($request->idUser);
              $conges->nbFoisAuto=$a ; 
              $conges->save() ;
            }
            else
            {
             $rapport->test=false;
             $rapport->result='Refus' ;
             $rapport->reponse='Vous avez dépassé le nombre maximale des heures possibles ! ';
            }
            return response()->json($rapport,200);
        }
    
        public function CalculHeureRTT ()
         {
            $rapport= new Rapport ;
            $params = DB::table('parametres')
             ->get('NbHeureJour');
            
            $totalHeureSupp = DB::table('pointages')
             ->where('id_employee','=',$request->idUser)
             ->sum('NB_heures_supplementaires');
    
             $to_date =$request->date_debut ;
             $from_date = $request->date_fin ;
             $diffJour = $to_date->diffInDays($from_date);
             $nbDAY = $totalHeureSupp / $params[0] ;
             $restHeure =$totalHeureSupp  % $params[0] ; 
        
            if ( $diffJour > $nbDAY )
            { 
              $rapport->test =false;
              $rapport->result ='Refus';
              $rapport->reponse='Désolée vous avez pas de jour RTT!';
            }
            else {
             $rapport->test=true;
             $rapport->result='Acceptation';
             $a = ($paramsHeure[0]->nbFoisMois)-1 ;
             $conges=conge::findOrFail($request->idUser);
             $conges->nbFoisAuto=$a ; 
             $conges->save() ;
            }
    
            return response()->json($rapport,200);
    
         }
    
         public function verifIdUserInPlanning($projets,$idUser) 
         {   $i=0 ; 
             $obj = array();
             foreach ($projets as $p) 
             {              
              $ArrayLIsteEmp=json_decode($p->liste_employees, true);
              foreach ($ArrayLIsteEmp as $a)
               { if ($a['id'] == $idUser) { $obj[$i]=$p; $i++; } } 
             }
              return ($obj);
         }
            public function resultatRapport(Request $request)
            {  
                $paramsType= DB::table('typeconges')
                ->Where('name', '=',$request->codeTypeConge)
                ->select('name','anciennete','maxJourPris','nbJourAn','actif')
                ->get();
                $ch=$paramsType[0]->actif;
                
                $current_date =Carbon::today()->format('Y-m-d');
        
                $dateArrive = DB::table('users')
                ->where('id','=',$request->idUser)
                ->get('date_arrive');
                $user = DB::table('users')
                ->where('id',$request->idUser)
                ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) AS full_name "))
                ->get();
                  $nameUser=$user[0]->full_name;
                  $adresse=$user[0]->email;
                $nbTotal = DB::table('conges')
                ->where('id_employee','=',$request->idUser)
                ->where('code_typeC','=',$ch)
                ->whereYear('date_end','=', date('Y'))
                ->sum('nbJourPris'); 
                $date_debut=$request->date_debut;
                $date_fin=$request->date_fin; 
        
                $to_date = Carbon::createFromFormat('Y-m-d', $date_debut);
                $from_date = Carbon::createFromFormat('Y-m-d',  $date_fin);
        
                $diffJourConge = $to_date->diffInDays($from_date);
        
                $date_arrive = Carbon::createFromFormat('Y-m-d', $dateArrive[0]->date_arrive);
        
                $diffAnciennete= $date_arrive->diffInDays( $to_date);
        
        
                switch($ch) {
        
                    case('2'):
                        $rapport = $this->verifDateAnciennete($diffJourConge,$diffAnciennete,$paramsType,$nbTotal) ;
                        
                        if($rapport->test==true)
                        {
                        $projet= DB::table('plannings')
                        ->WhereBetween('date_debut',[$request->date_debut, $request->date_fin])
                        ->orWhereBetween('dateProd',[$request->date_debut, $request->date_fin])
                        ->get(); 
                        $projets= $this->verifIdUserInPlanning($projet,$request->idUser);
                        
                        if(count($projets)>0)
                        {   $nb=$this->verifExistOtheridUserInProjetofSameVacation($projets,$request->date_debut,$request->date_fin);
                            $verifplaning=$this->verifDateProdMinPerso($projets,$request->date_debut,$request->date_fin,$nb) ;
                            $verifplaning->name= $nameUser; 
                            $verifplaning->date_debutConge=$date_debut;
                            $verifplaning->datefinConge=$date_fin;
                            $verifplaning->code='2';
                            $verifplaning->typeconge=$paramsType[0]->name;
                            $verifplaning->nbJourEffectue=$diffJourConge;
                            $verifplaning->adresse=$adresse;
                            $verifplaning->raison=$request->description;
                            return response()->json($verifplaning,200);
                        }
                        else
                        { 
                            $rapport->reponse ="vous n'avez pas des projets , votre demande est acceptée par notre systeme";
                            $rapport->result ='Acceptation' ;
                            $rapport->name= $nameUser; 
                            $rapport->date_debutConge=$date_debut;
                            $rapport->datefinConge=$date_fin;
                            $rapport->nbJourEffectue=$diffJourConge;
                            $rapport->code='2';
                            $rapport->typeconge=$paramsType[0]->name;
                            $rapport->adresse=$adresse;
                            $rapport->raison=$request->description;
                            return response()->json($rapport,200);   
                        }
                        }
        
                        else {
                            $rapport->anciennete =$diffAnciennete; 
                            $rapport->name= $nameUser; 
                            $rapport->date_debutConge=$date_debut;
                            $rapport->nbJourEffectue=$diffJourConge;
                            $rapport->datefinConge=$date_fin;
                            $rapport->typeconge=$paramsType[0]->name;
                            $rapport->raison=$request->description;
                            $rapport->adresse=$adresse;
                            $rapport->code='2';
                            return response()->json($rapport,200); } 
                   
                    break;
        
                    case('1'):
                         $rapport=$this->verifDateAnciennete($diffJourConge,$diffAnciennete,$paramsType,$nbTotal ) ;
                         $rapport->result='Attente';
                         $rapport->reponse='Votre demande a été prise en compte et sera traitée dès que possible';
                         $rapport->name= $nameUser; 
                         $rapport->date_debutConge=$date_debut;
                         $rapport->nbJourEffectue=$diffJourConge;
                         $rapport->datefinConge=$date_fin;
                         $rapport->raison=$request->description;
                         $rapport->adresse=$adresse;
                         $rapport->code='1';
                         $rapport->typeconge=$paramsType[0]->name;
                         return response()->json($rapport,200);
                    break;
        
                   case('3'):
                        $rapport=$this->verifDateAnciennete($diffJourConge,$diffAnciennete,$paramsType,$nbTotal) ;
                        $rapport->name= $nameUser; 
                        $rapport->date_debutConge=$date_debut;
                        $rapport->nbJourEffectue=$diffJourConge;
                        $rapport->datefinConge=$date_fin;
                        $rapport->raison=$request->description;
                        $rapport->adresse=$adresse;
                        $rapport->code='1';
                        $rapport->typeconge=$paramsType[0]->name;
                        return response()->json($rapport,200);
                   break;
    
                   case('4'):
                    $rapport=$this->CalculHeureRTT() ;
                    return response()->json($rapport,200);
                    break;
               
                   case('5'):
                    $rapport=$this->CalculHeureAuto() ;
                    return($rapport);
                    break;
        
                    default: 
                        
                }
         
                
            }
            public function verifDateProdMinPerso($array,$date_debut,$date_fin,$nb){
                $b=true;
                $i=0;
                $rapport=new Rapport();
                while ( ($b==true) && ($i<count($array)) )
                {  
                    $ArrayLIsteEmp=json_decode($array[$i]->liste_employees, true);
                    $nbPersonne=count($ArrayLIsteEmp)-$nb;  
                    $nbMinPersonne=$array[$i]->MinPersonne;
        
                    if ((($array[$i]->dateProd < $date_debut ) || ($array[$i]->dateProd > $date_fin)))
                    {   
                       if ($nbPersonne-1 >= $array[$i]->MinPersonne)
                         { $i++;} 
                        else {
                        $b=false ;
                        $rapport->test=false ;
                        $rapport->result='Refus' ;
                        $rapport->reponse="Le nombre minimal d'une équipe projet doit être au minimum ". $array[$i]->MinPersonne." pour accomplir la plupart des tâches dans un projet";
                         }
                    }
                    else
                    {
                     $b=false ;
                     $rapport->test=false ;
                     $rapport->result='Refus' ;
                     $rapport->reponse='Vous avez une journée de production le '.$array[$i]->dateProd.' pendant la periode que vous voulez prendre en congé ';
                    }   
                }
                   if ($b==true)
                   { 
                    $rapport->result='Acceptation' ;
                    $rapport->reponse='votre demande est acceptée par notre systeme' ;
                    $rapport->test=true ;
                    return ($rapport) ;
                   }
                    else { return ($rapport) ; }
            }
        
        
            public function verifDateAnciennete (int $diffJourConge,$diffAnciennete,$paramsType,int $nbTotal) {
        
               
                $rapport=new Rapport();
                $diffJour = $diffJourConge + $nbTotal ;
        
                if ($paramsType[0]->anciennete <= $diffAnciennete) 
                {
                    if ($diffJourConge <= $paramsType[0]->nbJourAn) 
                    {
                       if  ($diffJourConge <= $paramsType[0]->maxJourPris) 
                        {
                            if ($diffJour <= $paramsType[0]->nbJourAn)
                                { $rapport->test=true; }
                            else
                            { $rapport->result='Refus' ;
                              $rapport->reponse='Vous avez déjà pris le nombre de jours possibles par an, qui est de '.$paramsType[0]->nbJourAn.' jours pour ce type '. $paramsType[0]->name ;
                              $rapport->test=false; }
                        }      
        
                            else
                        
                        {  $rapport->result='Refus' ;
                            $rapport->reponse='Vous avez dépasser le maximum de nombre de jours à prendre, qui est de '.$paramsType[0]->maxJourPris.' jours pour ce type '. $paramsType[0]->name ;
                            $rapport->test=false; }
                    }
                    else
                        {   $rapport->result='Refus' ;
                            $rapport->reponse='Vous avez dépasser le nombre de jours à prendre par an pour ce type '. $paramsType[0]->name ;
                            $rapport->test=false; } 
                }
                else
                    {   $rapport->result='Refus' ;
                        $rapport->reponse="Vous ne pouvez prendre le congé qu'après ".$paramsType[0]->anciennete." jours de votre date arrive à l'entreprise ";
                        $rapport->test=false; }
                return ($rapport) ;
                
            }
  
    }    
