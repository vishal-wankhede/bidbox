<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
      'name',
      'parent',
      'child',
      'child_name',
      'parent_master',
      'male',
      'female',
      'other',
    ];

}
