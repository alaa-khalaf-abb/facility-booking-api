@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title mb-4 text-primary">My Bookings</h3>
                <div id="alert-area">
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
                </div>
                @if($bookings->isEmpty())
                    <div class="alert alert-info">You have no bookings yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>Resource</th>
                                    <th>Status</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    <tr>
                                        <td class="fw-semibold">{{ $booking->resource->name ?? 'Unknown' }}</td>
                                        <td>
                                            @php
                                                $status = strtolower($booking->status->status ?? 'unknown');
                                                $badge = 'secondary';
                                                if ($status === 'pending') $badge = 'warning';
                                                elseif ($status === 'approved') $badge = 'success';
                                                elseif ($status === 'rejected') $badge = 'danger';
                                            @endphp
                                            <span class="badge bg-{{ $badge }} text-uppercase">{{ ucfirst($status) }}</span>
                                        </td>
                                        <td>{{ $booking->start_time }}</td>
                                        <td>{{ $booking->end_time }}</td>
                                        <td>
                                            @if(strtolower($booking->status->status ?? '') === 'pending')
                                                <form class="cancel-booking-form d-inline" data-booking-id="{{ $booking->id }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Cancel</button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if(auth()->check() && auth()->user()->role === 'user')
                    <a href="{{ route('bookings.create') }}" class="btn btn-primary mt-3">Make a New Booking</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.cancel-booking-form').on('submit', function(e) {
        e.preventDefault();
        if (!confirm('Cancel this booking?')) return;
        var form = $(this);
        var bookingId = form.data('booking-id');
        $.ajax({
            url: '/bookings/' + bookingId,
            type: 'POST',
            data: form.serialize() + '&_method=DELETE',
            success: function(response) {
                form.parents('tr').fadeOut(400, function() { $(this).remove(); });
                $('#alert-area').html('<div class="alert alert-success alert-dismissible fade show" role="alert">Booking cancelled successfully.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            },
            error: function(xhr) {
                let msg = 'An error occurred.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                $('#alert-area').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+msg+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            }
        });
    });
});
</script>
@endpush 