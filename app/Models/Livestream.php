<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestream extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $dates = ['from','to'];
    protected $casts = [
      'time' => 'datetime:H:i',
    ];
    
    public function sources()
    {
      return $this->hasMany(URL::class, "uid", "uid");
    }
}
