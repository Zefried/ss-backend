<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAndWorker extends Model
{


    protected $fillable = [
        'user_type',
        'account_request',
        'name',
        'user_id',              // Foreign key reference
        'age',
        'sex',
        'relativeName',
        'phone',
        'email',
        'registrationNo',
        'village',
        'po',
        'ps',
        'pin',
        'district',
        'buildingNo',
        'landmark',
        'workDistrict',
        'state',
        'designation',
        'unique_user_id',
        'disable_status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
