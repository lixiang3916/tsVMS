<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function store(Request $request)
    {
      $this->validate($request,[
        'name' => 'required|max:50',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|confirmed|min:6'
      ]);

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);

      if (Auth::attempt($credentials, $request->has('remember'))) {
           session()->flash('success', '欢迎回来！');
           return redirect()->route('users.show', [Auth::user()]);
       } else {
           session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
           return redirect()->back();
       }

    }
}
