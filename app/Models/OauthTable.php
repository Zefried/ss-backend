<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthTable extends Model
{
    // protected $table = 'oauth_tables';
 
    protected $fillable = [
        'user_id',
        'provider',
        'access_token',
        'refresh_token',
        'scope',
        'profile_link',
    ];

    /**
     * Get the user that owns the OAuth record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
