<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'caption',
        'time',
        'channel_name',
        'channel_id',
        'video_id',
        'video_url',
        'channel_url'
    ];

    public function qualities()
    {
        $this->hasMany(Quality::class);
    }
}
