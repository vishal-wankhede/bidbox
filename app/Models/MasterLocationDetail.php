<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLocationDetail extends Model
{
  use HasFactory;
  protected $table = 'master_location_details';

  protected $fillable = [
    'master_id',
    'filter_id',
    'parent_value_id',
    'filter_value_id',
    'location_id',
    'parent_locations',
    'male',
    'female',
    'other',
    'breadcrumb',
    'created_at',
    'updated_at',
  ];
}
