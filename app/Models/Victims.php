<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Victims extends Model
{
    use HasFactory;
    protected $fillable = [
        'Name1','Name2','Name3','Name4',];
}
