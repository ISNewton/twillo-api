<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'name' ,
        'bedrooms',
        'bathrooms',
        'parking'  ,
        'furnished',
        'address' ,
        'offer',
        'regular_price' ,
        'discounted_price' ,
        'latitude' ,
        'longitude' 
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }
}
