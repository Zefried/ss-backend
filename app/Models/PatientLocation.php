<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientLocation extends Model
{
    protected $fillable = [
        'location_name',
        'disable_status',
    ];
}
