<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobApplication extends Model
{
    // use HasFacory;

    public function addJob(){
        return $this->belongsTo(AddJob::class, 'add_job_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function employer(){
        return $this->belongsTo(User::class,'employer_id');
    }
}
