<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'disable_status',
    ];


    public function tests()
    {
        return $this->hasMany(Test::class)->where('disable_status', '!=', '1');
    }
}
