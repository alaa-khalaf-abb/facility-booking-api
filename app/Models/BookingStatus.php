<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    protected $fillable = ['status'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
