<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    public function addJob(){
        return $this->belongsTo(AddJob::class);
    }
}
