<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use DB ; 
use Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{
    
//GET ALL USERS
    public function index(Request $request)
    {
        $allinfo= User::paginate(10);
        return response()->json($allinfo);
       /* $ref=$request->query('ref') ;
        $first_name=$request->query('first_name');
        $last_name=$request->query('last_name') ;
        $users = DB::table('users')
        ->where('id', '=',$ref)
        ->orWhere('first_name', '=',$first_name)
        ->orWhere('last_name', '=',$last_name)
        ->paginate(5) ;
        if ($users) 
        { return response()->json($users,200); }
        else
        { $alluser= User::where('id', '!=', Auth::user()->id)->paginate(5);
          return response()->json($alluser); } */
    }

// GET INFO USER CONNECTE
    public function get_my_info()
    {
        $user=User::findOrFail(Auth::user()->id);
        return response()->json($user);
    }

// GET INFO ONE USER 
    public function get_info_user($id)
    {
        $user=User::findOrFail($id);
        return response()->json($user);
    }

// CREATE ONE USER 
    public function add_user(Request $request)
    {
        return User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'first_name' => $request->first_name, 
                'last_name' => $request->last_name,
                'departement' => $request->departement,
                'role' => $request->role,
                'adress'=> $request->adress,
                'city'=> $request->city,
                'birth_date'=> $request->birth_date,
                'phoneHome'=> $request->phoneHome,
                'phonePro'=> $request->phonePro,
                'poste_id'=> $request->poste_id,
                'type_employee'=> $request->type_employee ,
                'date_arrive'=> $request->date_arrive ,
            ]);
            
            return response()->json('added succefuly',200);
    }
// UPDATE ONE USER 
    public function update_user(Request $request, $id)
    {
        $role = DB::table('users')
        ->where('id',Auth::user()->id)
        ->select('role')->value('role');

        $role_admin = DB::table("users")
        ->where('role',"=",'admin') 
        ->get();
         $nbr_admin = count ($role_admin) ;
         $user=User::findOrFail($id);
         $user->first_name= $request->first_name;
         $user->last_name= $request->last_name;
         $user->adress= $request->adress;
         $user->city= $request->city;
         $user->birth_date= $request->birth_date;
         $user->phoneHome= $request->phoneHome;
         $user->departement= $request->departement; 
         $user->phonePro= $request->phonePro;
         $user->poste_id= $request->poste_id;
         $user->type_employee= $request->type_employee;
         $user->date_arrive= $request->date_arrive;
         $user->save();
         return response()->json('updated succefuly',200);
        
    }
// DELETE ONE USER
    public function delete_user($id) {
        $user = User::findOrFail($id);
        if($user)
           $user->delete(); 
           return response()->json('deleted succefuly',200); 
    }
// SEND EMAIL FROM RH TO ONE USER 
    public function send_user_email(Request $request, $id) 
    {
        $message =$request->message;
        Mail::raw($message, function($message) use($request,$id)
        {  
            $emailreceiver= DB::table('users')
            ->where('id', "=",$id) 
            ->select('email')->value('email');

            $emailsender=DB::table('users')->where('role',"=","admin")
            ->select('email')->value('email');
            $message->from($emailsender,'RESSOURCES HUMAINE');
            $message->to($emailreceiver)->subject($request->subject);
         });
         return response()->json('email sended',200);
    }

// SEND EMAIL FROM USER CONNECTE TO RH 
    public function send_rh_email(Request $request) {
        $message =$request->message;
        Mail::raw($message, function($message) use($request) 
        {  
            $emailsender= DB::table('users')
            ->where('id',Auth::user()->id) 
            ->select('email')->value('email');

            $emailreceiver=DB::table('users')->where('role',"=","admin")
            ->select('email')->value('email');
            $message->from($emailsender,'DEMANDE EMPLOYEE');
            $message->to($emailreceiver)->subject($request->subject);
         });
         return response()->json('email sended',200);
    }

// SEND EMAIL FROM RH TO USERS 
    public function send_users_email(Request $request) {
        $message =$request->message;
        Mail::raw($message, function($message) use($request)
        {  
            $emailsender=DB::table('users')->where('role',"=","admin")
            ->select('email')->value('email');

            $arrayusers=array();
            $users=DB::table('users')->get('email');
            $users= DB::table('users')->get();
            $users= json_decode($users);
              if(!empty($users))
                 { foreach($users as $c)
                    {
                    $user_id=$c->id;
                    $user_email=$c->email;
                    $arrayusers[$user_id]= $user_email;
                     }
                 } 
            $message->from($emailsender,'RESSOURCES HUMAINE');
            $message->to($arrayusers)->subject($request->subject);
         });
         return response()->json('email sended',200);
    }

//SEARCH WITH REQUEST 
public function search_user(Request $request)
{
     $first_name=$request->first_name;
     $last_name=$request->last_name;
     $email=$request->email;
     $users = DB::table('users')
     ->Where('first_name', '=',$first_name)
     ->orWhere('last_name','=',$last_name)
     ->orWhere('email','=',$email)
     ->paginate(5);
     if ($users) 
     { return response()->json($users,200); }
     else
     { return response()->json( "n'existe pas ." ,202); }
}

}

