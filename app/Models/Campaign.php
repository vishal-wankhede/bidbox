<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
  protected $fillable = [
    'campaign_name',
    'campaign_description',
    'brand_name',
    'brand_logo',
    'channel',
    'impressions',
    'ctr',
    'vtr',
    'start_date',
    'end_date',
    'projection_details',
    'locations',
    'gender',
    'filtervalues',
    'division_value',
    'status',
  ];

  protected $casts = [
    'projection_details' => 'array',
    'locations' => 'array',
    'gender' => 'array',
    'filtervalues' => 'array',
    'division_value' => 'array',
  ];

  public function creatives()
  {
    return $this->hasMany('App\Models\CampaignCreative', 'campaign_id');
  }
}
