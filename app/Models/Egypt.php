<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egypt extends Model
{
    public function Victim()
    {
        return $this->belongsTo(Victim::class);
    }
    protected $casts=[
        'published'=>'boolean',
    ];
}
