<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientData extends Model
{


    protected $fillable = [
        'name',
        'patient_location',
        'patient_location_id',
        'age',
        'sex',
        'relativeName',
        'phone',
        'email',
        'identityProof',
        'village',
        'po',
        'ps',
        'pin',
        'district',
        'state',
        'unique_patient_id',
        'request_status', 
        'associated_user_email', 
        'associated_user_id',
        'disable_status',
    ];
    

    public function patientLocationCount()
    {
        return $this->hasOne(PatientLocationCount::class, 'patient_id');
    }

    public function patientAssignFlow()
    {
        return $this->hasMany(PatientAssignFlow::class, 'patient_id');
    }

    public function billingFlow()
    {
        return $this->hasMany(BillingFlow::class, 'patient_id');
    }
}
