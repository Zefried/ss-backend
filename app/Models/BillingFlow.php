<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingFlow extends Model
{
    use HasFactory;

    protected $table = 'billing_flows';

    protected $fillable = [
        'patient_id', // Fetch all bills against the patient
        'associated_user_id', // Fetch all bills against the associated user
        'final_amount', 
        'discount',
        'selected_employee_id', // Fetch all bills against the selected employee
        'tests',
        'lab_id',
        'patient_assign_flow_id', // Fetch all bills against the single patient assign flow
        'bill_file',
        'transaction_id',
    ];

    protected $casts = [
        'tests' => 'array', // JSON field should be cast to an array
    ];

    // Relationships from both sides
    public function patient()
    {
        return $this->belongsTo(PatientData::class, 'patient_id');
    }

    public function associatedUser()
    {
        return $this->belongsTo(User::class, 'associated_user_id');
    }

    public function selectedEmployee()
    {
        return $this->belongsTo(Employee::class, 'selected_employee_id');
    }

    public function patientAssignFlow()
    {
        return $this->belongsTo(PatientAssignFlow::class, 'patient_assign_flow_id');
    }

    
}
