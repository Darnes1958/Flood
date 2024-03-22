<?php

namespace App\Models;

use App\Enums\Subjects;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts =[
    'subjects' => Subjects::class,

  ];
}
