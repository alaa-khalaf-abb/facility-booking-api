<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'name',
        'description',
        'capacity',
        'location',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
