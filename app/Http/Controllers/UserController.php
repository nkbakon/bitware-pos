<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{6,}$/',
            'confirm_password' => 'required|same:password',
        ]);

        $user = new User();
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if($user){
            return redirect()->route('users.index')->with('status', 'User registrated successfully.');
        }
        return redirect()->route('users.index')->with('delete', 'User registration faild, try again.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required',
        ]);

        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        $user->status = $request->status;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->data_id);
        if($user)
        {
            $user->delete();
            return redirect()->route('users.index')->with('delete', 'User deleted successfully.');
        }
        else
        {
            return redirect()->route('users.index')->with('delete', 'No user found!.');
        }    
    }

    public function emailcheck(Request $request)
    {
        $email = $request->input("email");
        $success = false;
        $user = User::where('email', $email)->first();
        if($user == null){
            $success = true;
        }else{
            $success = false;
        }

        return response()->json([
            "success" => $success
        ]);
    }
}