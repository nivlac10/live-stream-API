<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreamDump extends Model
{
	use HasFactory;
	protected $table = 'stream_dump';
    protected $fillable = [
        'sports_type',
        'time',
        'uid',
        'league_name',
        'home_team',
        'away_team',
        'home_mark',
        'league_mark',
        'away_mark',
        'start_date',
        'status',
    ];
}
