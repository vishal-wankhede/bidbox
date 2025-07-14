<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportLog extends Model
{
  use HasFactory;
  protected $table = 'table_report_logs';
  protected $fillable = [
    'campaign_id',
    'report_type',
    'impressions',
    'clicks',
    'video_views',
    'locations',
    'gender',
    'filter_values',
    'division_values',
    'creatives',
  ];

  protected $casts = [
    'locations' => 'array',
    'gender' => 'array',
    'filter_values' => 'array',
    'division_values' => 'array',
    'creatives' => 'array',
  ];
}
