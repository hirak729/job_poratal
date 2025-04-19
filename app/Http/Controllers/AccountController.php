<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\AddJob;
use App\Models\JobApplication;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function registration()
    {
        return view('front.account.registration');
    }

    public function processRegistration(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->route('account.registration')
            ->withErrors($validator)
            ->withInput();
        }

        $User = new User();
        $User->name = $request->name;
        $User->email = $request->email;
        $User->password = Hash::make($request->password);
        $User->save();

        return redirect()->route('account.login')
        ->with('success','Registration Successful');

    }

    public function login()
    {
        return view('front.account.login');
    }

    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
        else{
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return redirect()->route('account.profile');
            }
            else{
                return redirect()->route('account.login')
                ->with('error','Invalid Credentials')
                ->withInput();
            }
        }

    }

    public function profile()
    {

        $id = Auth::user()->id;

        $user = User::find(Auth::user()->id);

        return view('front.account.profile', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email',
            'designation' => 'required',
            'mobile' => 'required|digits:10',
        ]);

        if($validator->fails()){
            return redirect()->route('account.profile')
            ->withErrors($validator)
            ->withInput();
        }

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->designation = $request->designation;
        $user->mobile = $request->mobile;
        $user->save();

        return redirect()->route('account.profile')
        ->with('success','Profile Updated Successfully');
    }

    public function updateProfilePic(Request $request){
            
            $validator = Validator::make($request->all(),[
                'image' => 'required|image',
            ]);
    
            if($validator->fails()){
                return redirect()->route('account.profile')
                ->withErrors($validator)
                ->withInput();
            }
    
            $user = User::find(Auth::user()->id);
    
            $image = $request->image;
            $extension = $image->getClientOriginalExtension();
            $imageName = time().'.'.$extension;
            $image->move(storage_path('app/public/profile_pic'), $imageName);
            $user->image = $imageName;
            $user->save();
    
            return redirect()->route('account.profile')
            ->with('success','Profile Picture Updated Successfully');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function createJob(){
        $user = User::find(Auth::user()->id);
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        return view('front.account.job.create', [
            'user' => $user,
            'categories' => $categories,
            'jobTypes' => $jobTypes,
        ]);
    }

    public function saveJob(Request $request) {

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
            return redirect()->route('account.createJob')
            ->withErrors($validator)
            ->withInput();
        }

        $job = new AddJob();
        $job->title = $request->title;
        $job->category_id = $request->category;
        $job->job_type_id = $request->jobType;
        $job->user_id = Auth::user()->id;
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

        return redirect()->route('account.myJobs')
            ->with('success','Job Created Successfully');
        
    }

    public function myJobs(){
        $user = User::find(Auth::user()->id);
        $jobs = AddJob::where('user_id', Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);

        return view('front.account.job.my-jobs', [
            'user' => $user,
            'jobs' => $jobs,
        ]);
    }

    public function editJob(Request $request, $id){
        $user = User::find(Auth::user()->id);
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();

        $job = AddJob::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();

        if($job == null){
            abort(404);
        }

        return view('front.account.job.edit', [
            'user' => $user,
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job,
        ]);
        
    }

    public function updateJob(Request $request, $id) {

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
            return redirect()->route('account.createJob')
            ->withErrors($validator)
            ->withInput();
        }

        $job = AddJob::find($id);
        $job->title = $request->title;
        $job->category_id = $request->category;
        $job->job_type_id = $request->jobType;
        $job->user_id = Auth::user()->id;
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

        return redirect()->route('account.myJobs')
            ->with('success','Job Updated Successfully');
        
    }

    public function deleteJob(Request $request){

        $job = AddJob::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId,
        ])->first();

        if($job == null){
            session()->flash('error', 'Either job deleted or not found.');
            return response()->json([
                'status' => true
            ]);
        }

        AddJob::where('id', $request->jobId)->delete();
        session()->flash('success', 'Job deleted successfully');
        return response()->json([
            'status' => true
        ]);
    }

    public function myJobApplications(){
        $user = User::find(Auth::user()->id);
        $jobApplications = JobApplication::where('user_id', Auth::user()->id)
                ->with(['addJob','addJob.jobType','addJob.applications'])
                ->paginate(10);
        return view('front.account.job.my-job-application',[
            'jobApplications' => $jobApplications,
            'user' => $user,
        ]);

    }

    

    public function removeJobs(Request $request){
        $jobApplication = JobApplication::where(['id' => $request->id, 'user_id' => Auth::user()->id])->first();
        
        if($jobApplication == null){
            session()->flash('error', 'Job Application not found');
            return response()->json([
                'status' => false,   
            ]);
        }

        JobApplication::find($request->id)->delete();
        session()->flash('success', 'Job Application removed');
            return response()->json([
                'status' => true,   
            ]);
    }

    public function savedJobs(){
        $user = User::find(Auth::user()->id);
        $savedjobs = SavedJob::where(['user_id' => Auth::user()->id])
                ->with(['addJob','addJob.jobType','addJob.applications'])
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        return view('front.account.job.saved-jobs',[
            'savedjobs' => $savedjobs,
            'user' => $user,
        ]);
    }

    public function removesavedJobs(Request $request){
        $savedjob = SavedJob::where(['id' => $request->id, 'user_id' => Auth::user()->id])->first();
        
        if($savedjob == null){
            session()->flash('error', 'Job Application not found');
            return response()->json([
                'status' => false,   
            ]);
        }

        SavedJob::find($request->id)->delete();
        session()->flash('success', 'Job removed successfully');
            return response()->json([
                'status' => true,   
            ]);
    }

    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if($validator->fails()){
            return redirect()->route('account.profile')
            ->withErrors($validator)
            ->withInput();
        }

        $user = User::find(Auth::user()->id);

    // Check if old password is correct
    if (!Hash::check($request->old_password, Auth::user()->password)) {
        return redirect()->route('account.profile')->with('error', 'Old password is incorrect.');
    }

    // Update password
    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->route('account.profile')->with('success', 'Password updated successfully.');
    }

}
