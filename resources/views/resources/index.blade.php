<!-- resources/views/resources/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>All Resources</h1>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('resources.create') }}">Add New Resource</a>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
        @foreach($resources as $resource)
            <tr>
                <td>{{ $resource->name }}</td>
                <td>{{ $resource->location }}</td>
                <td>{{ $resource->capacity }}</td>
                <td>
                    <a href="{{ route('resources.edit', $resource->id) }}">Edit</a>
                    <form action="{{ route('resources.destroy', $resource->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this resource?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
