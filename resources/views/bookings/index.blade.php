<!-- resources/views/bookings/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>All Bookings</h1>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <!-- <a href="{{ route('bookings.create') }}">Make a New Booking</a> -->

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Resource</th>
            <th>User</th>
            <th>Status</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
@foreach($bookings as $booking)
    <tr>
        <td>{{ $booking->resource->name }}</td>
        <td>{{ $booking->user->name }}</td>
        <td>{{ $booking->status->status }}</td>
        <td>{{ $booking->start_time }}</td>
        <td>{{ $booking->end_time }}</td>
       <td>
    @if(Auth::check() && Auth::user()->role === 'admin')
    @if($booking->booking_status_id === 1)
        <form method="POST" action="{{ route('bookings.approve', $booking->id) }}" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-success btn-sm" onclick="disableButtons(this)">Approve</button>
        </form>

        <form method="POST" action="{{ route('bookings.reject', $booking->id) }}" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm" onclick="disableButtons(this)">Reject</button>
        </form>
    @endif
    @endif

</td>

    </tr>
@endforeach

    </table>
@endsection


<script>
    function disableButtons(button) {
        button.disabled = true;
        button.innerText = 'Processing...';
        button.form.submit();
    }
</script>
