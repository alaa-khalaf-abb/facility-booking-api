<!-- resources/views/resources/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Create Resource</h1>

    <form method="POST" action="{{ route('resources.store') }}">
        @csrf

        <label>Name:</label>
        <input type="text" name="name" required><br><br>

        <label>Description:</label>
        <textarea name="description"></textarea><br><br>

        <label>Capacity:</label>
        <input type="number" name="capacity"><br><br>

        <label>Location:</label>
        <input type="text" name="location"><br><br>

        <label>Category:</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select><br><br>

        <button type="submit">Save</button>
    </form>
@endsection
