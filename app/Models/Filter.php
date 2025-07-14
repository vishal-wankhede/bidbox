<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
  use HasFactory;

  protected $fillable = ['title', 'parent_id', 'parent_master', 'child', 'filter_order', 'status', 'isFix'];

  public function filter_values()
  {
    return $this->hasMany(FilterValue::class, 'filter_id');
  }
}
