<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User ; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Mail;
class AuthController extends Controller
{
   
    public function register(Request $request) 
    {
            $data = $request->validate([
             'first_name' => 'required|string|max:191',
             'last_name' => 'required|string|max:191',
             'email' => 'required|email|max:191|unique:users,email',
             'password' => 'required|string', 
             'departement' => 'required|integer',
             'adress' => 'required|string' ,
             'city' => 'required|string', 
             'role' => 'required|string',
             'birth_date' => 'required|string',
             'phoneHome' => 'required|string' ,
             'phonePro' => 'required|string', 
             'poste_id' => 'required|integer',
             'type_employee' => 'required|integer',
             'date_arrive' =>'required|string',
         ]) ; 


              $user = User::create([
                'first_name' => $data['first_name'], 
                'last_name' => $data['last_name'], 
                'departement' => $data['departement'],
                'role'=> $data['role'],
                'city'=> $data['city'],
                'adress' => $data['adress'],
                'birth_date'=>$data['birth_date'],
                'phonePro' =>$data['phonePro'],
                'phoneHome' =>$data['phoneHome'] ,
                'poste_id' =>$data['poste_id'],
                'type_employee' =>$data['type_employee'],
                'date_arrive' =>$data['date_arrive'] ,
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
              
            ]) ;
           
// send email to employee
         $this->sendemail();
// get a token user 
         $token =  $user->createToken('fundaProjectToken')->plainTextToken ; 
          $response = [
              'user'=> $user, 
              'token'=>$token,
          ] ; 
          return response($response, 201) ; 
}

    public function sendemail()
    {
     $messages = "BIENVENUE CHER MONSIEUR/MADAME CI-JOINT VOS COORDONNEES D'AUTHENTIFICATION ";
     $email=Request('email');
     $password=Request('password');
     $message = $messages ."\n"."E-MAIL : ". $email ."\n"."MOT DE PASSE : ".$password;

     Mail::raw($message, function($message )
     { 
         $emailreceiver=Request('email');
         $emailsender=DB::table('users')->where('role',"=","admin")
         ->select('email')->value('email');
         $message->from($emailsender,'RESSOURCES HUMAINE');
         $message->to($emailreceiver)->subject('Coordonnés d`authentification');
      });
    }

    public function logout()
    {
         auth()->user()->tokens()->delete();
        return response (['message' => 'You have successfully logged out and the token was successfully deleted']);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:191',
            'password' => 'required|string', 

        ]) ; 

        $user = User::where('email',$data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) 
        { return response('Invalide Cridentials',401) ;}
        else 
        { $token = $user->createToken('auth_token')->plainTextToken;
         $response = 
            [
                'user' =>  $user,
                'token' => $token,
            ] ;
            return response ( $response, 200) ;
        }
    }
}
