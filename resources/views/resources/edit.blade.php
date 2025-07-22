<!-- resources/views/resources/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Edit Resource</h1>

    <form method="POST" action="{{ route('resources.update', $resource->id) }}">
        @csrf
        @method('PUT')

        <label>Name:</label>
        <input type="text" name="name" value="{{ $resource->name }}" required><br><br>

        <label>Description:</label>
        <textarea name="description">{{ $resource->description }}</textarea><br><br>

        <label>Capacity:</label>
        <input type="number" name="capacity" value="{{ $resource->capacity }}"><br><br>

        <label>Location:</label>
        <input type="text" name="location" value="{{ $resource->location }}"><br><br>

        <button type="submit">Update</button>
    </form>
@endsection
