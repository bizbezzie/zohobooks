<?php

namespace Bizbezzie\Zohobooks\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zohoauth extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'organization_id',
        'client_id',
        'client_secret',
        'token',
        'refresh_token',
        'token_expires_in',
    ];
}
