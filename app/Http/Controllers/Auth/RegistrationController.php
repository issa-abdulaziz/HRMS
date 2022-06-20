<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Manager\UserManager;
use App\Mail\VerifyEmail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\VerificationAnswer;
use App\Models\VerificationQuestion;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    //
    public function index(){
        $data['page_title'] = 'Register';
         return view('Auth.CreateAccount',$data);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required','string'],
            'email' => ['required' ,'email','unique:users,email'],
            'password' => ['required', 'min:8','confirmed'],
            'password_confirmation' => ['required', 'min:8'],
        ]);


        try{
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'status' => '0',
                ]);

        }catch(Exception $e){
            throw ValidationException::withMessages([$e->getMessage()]);
        }


        $data['token'] = uniqid();
        $data['page_title'] = 'Email Verify';
        $data['user'] = $user;

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $data['token'], 'created_at' => Carbon::now()]
        );

        $mailData = [
            "id" => $data['user']->id,
            "email" => $request->email,
            "token" =>$data['token'],
            "name" => $data['user']->name,
            "page_title" =>"Reset Password"

        ];

        Mail::to($request->email)->send(new VerifyEmail($mailData));

        $data['message'] = 'Verification sent successfully';
        return back()->with($data);


    }

    public function verify($token){
       $query= PasswordReset::where('token',$token);

       $row = $query->first();
       if(!$row){
        abort(403,'Page Expired');
       }

       try{
           User::where('email',$row->email)->update(['status'=>'1']);
           $query->delete();

           return redirect()->route('login')->with('verified','Your Account Is verified Successfully, Please Login');
       }catch(Exception $e){
            abort(500);
       }

    }




}
