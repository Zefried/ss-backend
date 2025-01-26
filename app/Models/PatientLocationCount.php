<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientLocationCount extends Model
{
    protected $fillable = [
        'location_id',
        'patient_card_id',
        'patient_id',
        'associated_user_id',
    ];

    public function patientData()
    {
        return $this->belongsTo(PatientData::class, 'patient_id');
    }
}
