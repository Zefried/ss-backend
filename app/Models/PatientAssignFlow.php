<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientAssignFlow extends Model
{
    protected $fillable = [
        'patient_id',
        'tests',
        'billing_status',   
        'discount',          
        'visit_count',       
        'patient_card_id',   
    ];


    // Ensure Laravel automatically converts JSON to an array
    protected $casts = [
        'tests' => 'json',
    ];

    public function patientData()
    {
        return $this->belongsTo(PatientData::class, 'patient_id');
    }

    public function billingFlow()
    {
        return $this->hasOne(BillingFlow::class);
    }

}
