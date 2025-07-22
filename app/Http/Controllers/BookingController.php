<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function adminindex() {
        $bookings = Booking::with(['resource', 'status', 'user'])->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create() {
        $resources = Resource::all();
        return view('bookings.create', compact('resources'));
    }

    public function store(Request $request) {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Booking::create([
            'user_id' => Auth::id() ?? 1, // fallback if no login yet
            'resource_id' => $request->resource_id,
            'booking_status_id' => 1, // default: pending
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking submitted.');
    }
 public function approve($id)
{
    if (Auth::user()->role !== 'admin') {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    $booking = Booking::findOrFail($id);
    $booking->booking_status_id = 2; // Approved
    $booking->save();

    return redirect()->back()->with('success', 'Booking approved.');
}

public function reject($id)
{
    if (Auth::user()->role !== 'admin') {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    $booking = Booking::findOrFail($id);
    $booking->booking_status_id = 3; // Rejected
    $booking->save();

    return redirect()->back()->with('success', 'Booking rejected.');
}



}
