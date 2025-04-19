<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\AddJob;
use App\Models\JobApplication;
use App\Models\SavedJob;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    public function index(Request $request){

        $categories = Category::where('status', 1)->get();
        $jobtypes = JobType::where('status', 1)->get();

        $jobs = AddJob::where('status', 1);

        // Search using keyword
        if(!empty($request->keyword)){
            $jobs = $jobs->where(function($query) use($request){
                $query->orWhere('title','like','%'.$request->keyword.'%');
                $query->orWhere('keywords','like','%'.$request->keyword.'%');
            });
        }

        //search using location
        if(!empty($request->location)){
            $jobs = $jobs->where('location', $request->location);
        }

        //search using category
        if(!empty($request->category)){
            $jobs = $jobs->where('category_id', $request->category);
        }

        //search using job_type
        if (!empty($request->job_type) && is_array($request->job_type)) {
            $jobs->whereIn('job_type_id', $request->job_type);
        }

        //search using experience
        if(!empty($request->experience)){
            $jobs = $jobs->where('experience', $request->experience);
        }

        // search using latest and oldest

        if ($request->sort == '0') {
            $jobs->orderBy('created_at', 'ASC'); // Oldest
        } else {
            $jobs->orderBy('created_at', 'DESC'); // Latest or default
        }
        
        $jobs = $jobs->with('JobType')->paginate(9);

        return view('front.jobs',[
            'categories' => $categories,
            'jobtypes' => $jobtypes,
            'jobs' => $jobs,
        ]);
    }

    public function details($id){

        $job = AddJob::where([
            'id'=> $id,
            'status' => 1
            ])->with(['jobType', 'category'])->first();

        if($job == null){
            abort(404);
        }

        $count = 0;
        if(Auth::user()){
            $count = SavedJob::where([
                'user_id' => Auth::user()->id,
                'add_job_id' => $id
            ])->count();
        }

        //fetch applicants

        $applications = JobApplication::where('add_job_id', $id)
                ->with('user')->get();

        return view('front.jobDetails',[
            'job' => $job,
            'count' =>$count,
            'applications' => $applications
        ]);
        

        return view('front.jobDetails',[
            'job' => $job,
            'count' => $count,
        ]);
    }

    public function applyJob(Request $request){
        $id = $request->id;

        $job = AddJob::where('id', $id)->first();

        // if job not found in db
        if($job == null){
            // session()->flash('error', 'Job does not exist');
            // return response()->json([
            //     'status' => false,
            //     'message' => 'Job does not exist',
            // ]);
            return redirect()->back()->with('error', 'Job does not exist');
        }

        // you can not apply on your own job
        $employer_id = $job->user_id;

        if($employer_id == Auth::user()->id){
            // session()->flash('error', 'You can not apply on you own Job');
            // return response()->json([
            //     'status' => false,
            //     'message' => 'You can not apply on you own Job',
            // ]);
            return redirect()->back()->with('error', 'You can not apply on you own Job');
        }

        // you can apply a job only once
        
        $jobApplicationCount = jobApplication::where([
            'user_id' => Auth::user()->id,
            'add_job_id' => $id
        ])->count();

        if($jobApplicationCount > 0){
            // session()->flash('error', 'You already applied on this job');
            // return response()->json([
            //     'status' => false,
            //     'message' => 'You already applied on this job',
            // ]);
            return redirect()->back()->with('error', 'You already applied on this job');
        }
        
        $application = new JobApplication();
        $application->add_job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();

        //send notification Email to Employer
        $employer = User::where('id', $employer_id)->first();
        $mailData = [
            'employer' => $employer,
            'user'=> Auth::user(),
            'job' => $job,
        ];

        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));

        return redirect()->back()->with('success', 'You have successfully applied');

        // session()->flash('success', 'You have successfully applied');
        //     return response()->json([
        //         'status' => true,
        //         'message' => 'You have successfully applied',
        //     ]);

    }

    public function saveJob(Request $request){
        $id = $request->id;

        $job = AddJob::where('id', $id)->first();

        // if job not found in db
        if($job == null){
            return redirect()->back()->with('error', 'Job does not exist');
        }

        // // you can not apply on your own job
        // $employer_id = $job->user_id;

        // if($employer_id == Auth::user()->id){

        //     return redirect()->back()->with('error', 'You can not save your own Job');
        // }

        // you can apply a job only once
        
        $count = SavedJob::where([
            'user_id' => Auth::user()->id,
            'add_job_id' => $id
        ])->count();

        if($count > 0){

            return redirect()->back()->with('error', 'You already saved this job');
        }
        
        $savejob = new SavedJob();
        $savejob->add_job_id = $id;
        $savejob->user_id = Auth::user()->id;
        $savejob->save();

        return redirect()->back()->with('success', 'Job is save successfully');

    }
}
