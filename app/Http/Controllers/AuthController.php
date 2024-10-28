<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    function login(){
        return view('auth.login');
    }

    function loginPost(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $usercheck = User::where('email', $request->email)->first();
        if(isset($usercheck)){
            if($usercheck->status == '1'){
                $credentials = $request->only('email', 'password');
                if(Auth::attempt($credentials)){
                    return redirect()->intended(route('dashboard'));
                }else{
                    return redirect()->route('login')->with('delete', 'Invalid email or password');
                }
            }
            else{
                return redirect()->route('login')->with('delete', 'Your account is deactivated, please contact Admin.');
            }
        }else{
            return redirect()->route('login')->with('delete', 'Invalid email or password');
        }        
        
    }

    function logout(){
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}$/',
            'confirm_password' => 'required|same:password',
        ]);

        if(Hash::check($request->current_password,$user->password)){
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('profile.index')->with('success', 'Password updated successfully.');
        }else{
            return redirect()->route('profile.index')->with('err', 'Invalid current password.');            
        }        
    }
}
