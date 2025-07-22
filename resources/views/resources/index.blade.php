<!-- resources/views/resources/index.blade.php -->
@extends('layouts.app')

@section('content')
<h2 class="mb-4 text-primary">All Resources</h2>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    @foreach($resources as $resource)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $resource->name }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $resource->category->name ?? 'Uncategorized' }}</h6>
                    <p class="card-text mb-2">{{ $resource->description }}</p>
                    <ul class="list-unstyled mb-3">
                        <li><strong>Capacity:</strong> {{ $resource->capacity ?? 'N/A' }}</li>
                        <li><strong>Location:</strong> {{ $resource->location ?? 'N/A' }}</li>
                    </ul>
                    <a href="{{ route('bookings.create') }}?resource_id={{ $resource->id }}" class="btn btn-primary mt-auto">Book Now</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
