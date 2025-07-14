<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeRange extends Model
{
    use HasFactory;
     protected $fillable = [
        'name'
    ];
    protected $table = 'age_ranges';
}
