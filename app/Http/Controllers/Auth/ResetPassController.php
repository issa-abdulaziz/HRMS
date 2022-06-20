<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetEmail;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPassController extends Controller
{
    //
    public function index(){
        $data['page_title'] = 'Reset Password';

        return view('Auth.ForgetPasswords',$data);
    }

    public function forgetPass(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $data['token'] = uniqid();
        $data['page_title'] = 'Reset Password';
        $data['user'] = User::where('email',$request->email)->first();
        DB::table('password_resets')->insert(
            [
            'email' => $request->email,
            'token' =>  $data['token'],
            'created_at' => Carbon::now()
             ]
        );

        // Mail::send('Auth.verify.reset-password-email',$data, function($message) use ($request) {
        //           $message->from(env('MAIL_USERNAME'));
        //           $message->to($request->email);
        //           $message->subject('Reset Password Notification');
        //        });

        $mailData = [
            "id" => $data['user']->id,
            "email" => $request->email,
            "token" =>$data['token'],
            "name" => $data['user']->name,
            "page_title" =>"Reset Password"

        ];

        Mail::to($request->email)->send(new ResetEmail($mailData));

        $data['message'] = 'Email sent successfully';
        return back()->with($data);
    }

    public function resetPassView()
    {

        // $query= PasswordReset::where('token',$token);
        // $row = $query->first();
        // if(!$row){
        //  abort(403,'Page Expired');
        // }
        // $data['token']=$token;
        $data['page_title']='Reset Password';

        return view('Auth.ResetPassword',$data);
    }


    public function resetPass(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        $query= PasswordReset::where('token',$request->token);
        $row = $query->first();
        if(!$row){
         abort(403,'Page Expired');
        }

        try{
            User::where('email',$row->email)->update(['password'=>Hash::make($request->password)]);
            $query->delete();

            return redirect()->route('login')->with('verified','Your Password Has Been Reset Successfully, Please Enter New Password');
        }catch(Exception $e){
             abort(500);
        }

    }
}
