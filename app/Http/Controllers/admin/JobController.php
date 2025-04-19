<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\AddJob;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(){
        $user = User::find(Auth::user()->id);

        $jobs = AddJob::orderBy('created_at', 'DESC')->with('user','applications')->paginate();
        return view('admin.users.jobs',[
            'user' => $user,
            'jobs' => $jobs
        ]);
    }

    public function edit($id){
        $user = User::find(Auth::user()->id);
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        $job = AddJob::findorFail($id);

        if($job == null){
            abort(404);
        }

        return view('admin.users.edit-job', [
            'user' => $user,
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job,
        ]);
    }

    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(),
        [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'experience' => 'required',
            'company_name' => 'required|min:5|max:75',
        ]);

        if($validator->fails()){
            return redirect()->route('admin.jobs.edit')
            ->withErrors($validator)
            ->withInput();
        }

        $job = AddJob::find($id);
        $job->title = $request->title;
        $job->category_id = $request->category;
        $job->job_type_id = $request->jobType;
        $job->vacancy = $request->vacancy;
        $job->salary = $request->salary;
        $job->location = $request->location;
        $job->description = $request->description;
        $job->benefits = $request->benefits;
        $job->responsibility = $request->responsibility;
        $job->qualifications = $request->qualifications;
        $job->experience = $request->experience;
        $job->keywords = $request->keywords;
        $job->company_name = $request->company_name;
        $job->company_location = $request->company_location;
        $job->company_website = $request->company_website;
        $job->save();

        return redirect()->route('admin.jobs')
            ->with('success','Job Updated Successfully');
        
    }

    public function destroy(Request $request){
        $id = $request->id;

        $job = AddJob::findorFail($id);

        if($job == null){
            session()->flash('error', 'Either job deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }

        $job->delete();
        session()->flash('success', 'Job deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }
}
