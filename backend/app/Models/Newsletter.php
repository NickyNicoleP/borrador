<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $table = 'newsletter_suscriptores';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'activo'
    ];
}
