<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterData extends Model
{
  use HasFactory;

  protected $table = 'master_data';
  protected $fillable = [
    'master_id',
    'filter_id',
    'filter_value_id',
    'male',
    'female',
    'other',
    'breadcrumb',
    'created_at',
    'updated_at',
  ];
}
