<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::with(['resource', 'user', 'status'])->get();
    }
public function store(Request $request)
{
    $request->validate([
        'resource_id' => 'required|exists:resources,id',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
    ]);

    // Check for overlapping approved bookings
    $overlappingBookings = Booking::where('resource_id', $request->resource_id)
        ->where('booking_status_id', 2) // Only check approved bookings
        ->where(function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('start_time', '<', $request->end_time)
                  ->where('end_time', '>', $request->start_time);
            });
        })
        ->exists();

    if ($overlappingBookings) {
        return response()->json([
            'error' => 'Booking cannot be created due to overlapping with existing approved bookings',
            'message' => 'There is already an approved booking for this resource during the requested time period'
        ], 409);
    }

    $booking = Booking::create([
        'user_id' => auth()->id() ?? 1,
        'resource_id' => $request->resource_id,
        'booking_status_id' => 1,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
    ]);

    return response()->json([
        'message' => 'Booking created successfully',
        'booking' => $booking->load(['resource', 'status'])
    ]);
}

    public function show($id)
    {
        $booking = Booking::with(['resource', 'user', 'status'])->find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json($booking);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $validated = $request->validate([
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'booking_status_id' => 'sometimes|exists:booking_statuses,id',
        ]);

        // If updating time, check for overlapping bookings
        if (isset($validated['start_time']) || isset($validated['end_time'])) {
            $startTime = $validated['start_time'] ?? $booking->start_time;
            $endTime = $validated['end_time'] ?? $booking->end_time;

            $overlappingBookings = Booking::where('resource_id', $booking->resource_id)
                ->where('id', '!=', $booking->id)
                ->where('booking_status_id', 2) // Only check approved bookings
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                    });
                })
                ->exists();

            if ($overlappingBookings) {
                return response()->json([
                    'error' => 'Booking cannot be updated due to overlapping with existing approved bookings',
                    'message' => 'There is already an approved booking for this resource during the requested time period'
                ], 409);
            }
        }

        $booking->update($validated);
        return response()->json([
            'message' => 'Booking updated successfully',
            'booking' => $booking->load(['resource', 'user', 'status'])
        ]);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $booking->delete();
        return response()->json(['message' => 'Booking deleted']);
    }
    public function approve($id)
{
     if (request()->user()->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $booking = Booking::find($id);
    if (!$booking) {
        return response()->json(['error' => 'Booking not found'], 404);
    }

    // Check for overlapping bookings
    if ($booking->hasOverlappingApprovedBookings()) {
        $overlappingBookings = $booking->getOverlappingApprovedBookings();
        
        return response()->json([
            'error' => 'Booking cannot be approved due to overlapping with existing approved bookings',
            'message' => 'There is already an approved booking for this resource during the requested time period',
            'overlapping_bookings' => $overlappingBookings->map(function ($overlapping) {
                return [
                    'id' => $overlapping->id,
                    'user' => $overlapping->user->name,
                    'resource' => $overlapping->resource->name,
                    'start_time' => $overlapping->start_time,
                    'end_time' => $overlapping->end_time
                ];
            })
        ], 409);
    }

    $booking->booking_status_id = 2; // Approved
    $booking->save();

    return response()->json(['message' => 'Booking approved']);
}

public function reject($id)
{
     if (request()->user()->role !== 'admin') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $booking = Booking::find($id);
    if (!$booking) {
        return response()->json(['error' => 'Booking not found'], 404);
    }

    $booking->booking_status_id = 3; // Rejected
    $booking->save();

    return response()->json(['message' => 'Booking rejected']);
}

    public function myBookings()
    {
        $bookings = Booking::with(['resource', 'status'])
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($bookings);
    }

    public function adminIndex()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $bookings = Booking::with(['resource', 'user', 'status'])
            ->get();

        return response()->json($bookings);
    }

}
