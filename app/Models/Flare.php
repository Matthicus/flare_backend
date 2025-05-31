<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flare extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'note',
        'category',
        'place_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function place()
{
    return $this->belongsTo(Place::class);
}
}
