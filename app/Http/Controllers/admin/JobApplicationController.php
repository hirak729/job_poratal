<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function index(){
        $user = User::find(Auth::user()->id);

        $applications = JobApplication::orderBy('created_at', 'DESC')->with(['addJob', 'user', 'employer'])->paginate(10);

        return view('admin.users.applications', [
            'user' => $user,
            'applications' => $applications
        ]);
    }

    public function destroy(Request $request){
        $id = $request->id;

        $application = JobApplication::findorFail($id);

        if($application == null){
            session()->flash('error', 'Either application deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }

        $application->delete();
        session()->flash('success', 'application deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
