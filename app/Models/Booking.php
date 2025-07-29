<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'resource_id',
        'booking_status_id',
        'start_time',
        'end_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function status()
    {
        return $this->belongsTo(BookingStatus::class, 'booking_status_id');
    }

    /**
     * Check if this booking overlaps with any approved bookings for the same resource
     */
    public function hasOverlappingApprovedBookings()
    {
        return static::where('resource_id', $this->resource_id)
            ->where('id', '!=', $this->id)
            ->where('booking_status_id', 2) // Approved status
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('start_time', '<', $this->end_time)
                      ->where('end_time', '>', $this->start_time);
                });
            })
            ->exists();
    }

    /**
     * Get overlapping approved bookings for this booking
     */
    public function getOverlappingApprovedBookings()
    {
        return static::where('resource_id', $this->resource_id)
            ->where('id', '!=', $this->id)
            ->where('booking_status_id', 2) // Approved status
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('start_time', '<', $this->end_time)
                      ->where('end_time', '>', $this->start_time);
                });
            })
            ->with(['user', 'resource'])
            ->get();
    }
}
