<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignCreative extends Model
{
  protected $fillable = ['campaign_id', 'file_name', 'file_path', 'file_type', 'dimensions', 'size', 'percentage'];

  public function campaign()
  {
    return $this->belongsTo(Campaign::class);
  }
}
