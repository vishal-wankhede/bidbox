<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationDetail extends Model
{
  use HasFactory;
  protected $fillable = [
    'location_id',
    'gender_id',
    'filter_id',
    'filter_value_id',
    'parent_detail_id',
    'parent_locations',
    'population_value',
  ];
}
