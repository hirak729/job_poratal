<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;    

class UserController extends Controller
{
    public function index(){
        $user = User::find(Auth::user()->id);

        $users = User::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.users.list',[
            'users' => $users,
            'user' => $user
        ]);
    }

    public function edit($id){
        $user = User::find(Auth::user()->id);

        $edituser = User::findorfail($id);

        return view('admin.users.edit',[
            'user' => $user,
            'edituser' => $edituser
        ]);
    }

    public function update($id, Request $request){
        $user = User::find(Auth::user()->id);

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email',
            'designation' => 'required',
            'mobile' => 'required|digits:10',
        ]);

        if($validator->fails()){
            return redirect()->route('admin.users.edit',['user' => $user])
            ->withErrors($validator)
            ->withInput();
        }

        $useredit = User::find($id);
        $useredit->name = $request->name;
        $useredit->email = $request->email;
        $useredit->designation = $request->designation;
        $useredit->mobile = $request->mobile;
        $useredit->save();

        return redirect()->route('admin.users',['user' => $user])
        ->with('success','User data Updated Successfully');
    }

    public function destroy(Request $request){
        $id = $request->id;

        $user = User::find($id);

        if($user == null){
            session()->flash('error', 'User not found');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->delete();
        session()->flash('success', 'User deleted successfully');
            return response()->json([
                'status' => true,
            ]);

    }
}
