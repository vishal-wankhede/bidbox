<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterValue extends Model
{
  use HasFactory;

  protected $fillable = ['filter_id', 'title'];

  public function filter()
  {
    return $this->belongsTo(Filter::class);
  }
  
}
