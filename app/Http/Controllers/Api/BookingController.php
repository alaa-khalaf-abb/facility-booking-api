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

    Booking::create([
        'user_id' => auth()->id() ?? 1,
        'resource_id' => $request->resource_id,
        'booking_status_id' => 1,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
    ]);

    return response()->json(['message' => 'Booking created']);
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

        $booking->update($validated);
        return response()->json($booking);
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
