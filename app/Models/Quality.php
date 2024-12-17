<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quality extends Model
{
    use HasFactory;

    protected $table = 'qualities';

    protected $fillable = [
        'quality',
        'link_download',
        'videos_id'
    ];
}
