<!-- resources/views/resources/index.blade.php -->
@extends('layouts.app')

@section('content')
<h2 class="mb-4 text-primary">All Resources</h2>
<div class="row mb-4">
    <div class="col-md-6 mb-2 mb-md-0">
        <input type="text" id="resource-search" class="form-control" placeholder="Search by name...">
    </div>
    <div class="col-md-4">
        <select id="category-filter" class="form-select">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
</div>
@if(auth()->check() && auth()->user()->role === 'admin')
    <div class="mb-4">
        <a href="{{ route('resources.create') }}" class="btn btn-success">Create Resource</a>
    </div>
@endif
<div id="resource-cards" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
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
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('resources.edit', $resource->id) }}" class="btn btn-outline-info btn-sm mt-auto me-2">Edit</a>
                        <form action="{{ route('resources.destroy', $resource->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this resource and all related bookings?')">
                            @csrf
                            @method('DELETE')
                            <br>
                            <button type="submit" class="btn btn-outline-danger mt-auto" >Delete</button>
                        </form>
                    @else
                        <a href="{{ route('bookings.create') }}?resource_id={{ $resource->id }}" class="btn btn-primary mt-auto">Book Now</a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
<div id="loading-spinner" class="text-center my-4" style="display:none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
@push('scripts')
<script>
$(function() {
    function fetchResources() {
        $('#loading-spinner').show();
        let search = $('#resource-search').val();
        let category = $('#category-filter').val();
        $.get("{{ route('resources.ajax') }}", { search: search, category_id: category }, function(data) {
            let html = '';
            if (data.length === 0) {
                html = '<div class="col"><div class="alert alert-info">No resources found.</div></div>';
            } else {
                data.forEach(function(resource) {
                    html += `<div class=\"col\">
                        <div class=\"card h-100 shadow-sm\">
                            <div class=\"card-body d-flex flex-column\">
                                <h5 class=\"card-title\">${resource.name}</h5>
                                <h6 class=\"card-subtitle mb-2 text-muted\">${resource.category ? resource.category.name : 'Uncategorized'}</h6>
                                <p class=\"card-text mb-2\">${resource.description ?? ''}</p>
                                <ul class=\"list-unstyled mb-3\">
                                    <li><strong>Capacity:</strong> ${resource.capacity ?? 'N/A'}</li>
                                    <li><strong>Location:</strong> ${resource.location ?? 'N/A'}</li>
                                </ul>
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    <a href=\"${'{{ route('resources.edit', ':id') }}'.replace(':id', resource.id)}\" class=\"btn btn-outline-info btn-sm mt-auto me-2\">Edit</a>
                                    <form action=\"${'{{ route('resources.destroy', ':id') }}'.replace(':id', resource.id)}\" method=\"POST\" style=\"display:inline;\" onsubmit=\"return confirm('Delete this resource and all related bookings?')\">
                                        <input type=\"hidden\" name=\"_token\" value=\"${$('meta[name=csrf-token]').attr('content')}\">
                                        <input type=\"hidden\" name=\"_method\" value=\"DELETE\">
                                        <button type=\"submit\" class=\"btn btn-outline-danger mt-auto\">Delete</button>
                                    </form>
                                @else
                                    <a href=\"${'{{ route('bookings.create') }}?resource_id='}${resource.id}\" class=\"btn btn-primary mt-auto\">Book Now</a>
                                @endif
                            </div>
                        </div>
                    </div>`;
                });
            }
            $('#resource-cards').html(html);
            $('#loading-spinner').hide();
        });
    }
    $('#resource-search').on('input', fetchResources);
    $('#category-filter').on('change', fetchResources);
});
</script>
@endpush
@endsection
