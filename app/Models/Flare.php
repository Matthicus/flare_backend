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
        'photo_path', // added here
    ];

    protected $appends = [
        'photo_url', // automatically include photo URL in responses
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function knownPlace()
    {
        return $this->belongsTo(KnownPlace::class, 'known_place_id');
    }

    // Accessor for the full URL to the photo
    public function getPhotoUrlAttribute()
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }
}
