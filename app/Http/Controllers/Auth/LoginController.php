<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index(){
        $data['page_title'] = 'Login';

        return view("Auth.Login",$data);
    }

    public function SignIn(request $request){

      $cred =   $request->validate([
            'email' => ['required' ,'email','exists:users,email'],
            'password' => ['required', 'min:8']
        ]);
        $user=User::where('email',$request->email)->first();

            if(!Auth::attempt($cred)){
                throw ValidationException::withMessages(['Your Password is Incorrect']);
            }
            if($user->status == 0){
                return back()->with('verified','Not Verified');
            }

            return redirect('dashboard');
        }






}
