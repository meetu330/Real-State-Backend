<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function registration(Request $req){
        $reg = new User();
        $reg->name = $req->username;
        $reg->email = $req->email;
        $reg->password = Hash::make($req->password);
        $reg->save();

        $data['Status']     = '1';
		$data['message']  	= 'Registration Successfully';

        echo json_encode($data);

    }
    function login(Request $req){
  
        $user_data = array(
            'email' => $req->email,
            'password' => $req->password
        );

        if(Auth::attempt($user_data))
        {
            $status = true;
        }else{
            $status = false;
        }

        if($status == true)
        {
            $data['Status']     = '1';
		    $data['message']  	= 'Login Successfully';
            $data['userId'] = Auth::user()->id; 
            
        }else{
            $data['Status']     = '0';
		    $data['message']  	= 'Login Faild';
        }
            echo json_encode($data);

    }

    function logout(){

        Auth::logout();

        $data['Status']     = '1';
		$data['message']  	= 'Logout Successfully';

        echo json_encode($data);
    }

    function loginuserid(){
        $user = Auth::user()->id;
    }

    // For forgot password 
    function checkemail(Request $request){

        $email = User::where('email',$request->email)->get();
        $onlyemail = $email[0]['email'];

        $digits = 4;
        $otp = rand(pow(10, $digits-1), pow(10, $digits)-1);

        if(count($email) > 0){
            $data['Status']     = '1';
		    $data['message']  	= 'Email Found successfully';
            $data['otp'] = $otp;  
            $data['email'] = $request->email;

        }else{
            $data['Status']     = '0';
		    $data['message']  	= 'Email Not Found'; 
        }
        echo json_encode($data);

    }

    public function changepassword(Request $req, $email){
        $user = User::where('email',$email)->get();
        $userid = $user[0]['id'];
        $users = User::find($userid);
        $users->password = Hash::make($req->password);
        $users->save();

        $data['Status']     = '1';
		$data['message']  	= 'Password Change successfully';

        echo json_encode($data);
    }
}   
