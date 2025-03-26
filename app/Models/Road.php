<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Road extends Model
{
    protected $casts=[
        'image'=>'array',
    ];
    public function Street(){
        return $this->hasMany(Street::class);
    }
    public function Area()
    {
        return $this->belongsTo(Area::class);
    }

    public function Victim()
    {
        return $this->hasManyThrough('App\Models\Victim', 'App\Models\Street');
    }
}
