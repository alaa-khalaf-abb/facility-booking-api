<!-- resources/views/resources/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Create Resource</h1>

    <form method="POST" action="{{ route('resources.store') }}">
        @csrf
<table>
       <tr>
        <td> <label>Name:</label></td>
       <td> <br><input type="text" name="name" required><br><br></td>
       </tr>
    <tr>
       <td> <label>Description:</label></td>
       <td> <br> <textarea name="description"></textarea><br><br></td>
       </tr>
    <tr>
       <td> <label>Capacity:</label></td>
       <td> <br> <input type="number" name="capacity"><br><br></td>
       </tr>
    <tr>
       <td> <label>Location:</label></td>
       <td> <br> <input type="text" name="location"><br><br></td>

    </tr>

       <tr><td> <label>Category:</label></td>
       <td> <br> <select name="category_id" required> 
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select><br><br></td>
       </tr>
    <tr>
       <td> <button type="submit">Save</button></td>
       </tr>

    </form>
@endsection
