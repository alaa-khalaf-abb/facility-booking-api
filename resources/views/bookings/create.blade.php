@extends('layouts.app')

@section('content')
    <h1>Create a Booking</h1>

    <div id="message"></div>

    <form id="booking-form">
        @csrf

        <label>Choose Resource:</label>
        <select name="resource_id" required>
            @foreach($resources as $resource)
                <option value="{{ $resource->id }}">{{ $resource->name }}</option>
            @endforeach
        </select><br><br>

        <label>Start Time:</label>
        <input type="datetime-local" name="start_time" required><br><br>

        <label>End Time:</label>
        <input type="datetime-local" name="end_time" required><br><br>

        <button type="submit">Submit Booking</button>
    </form>

    <script>
        $(document).ready(function () {
            $('#booking-form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('bookings.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        window.location.href = "{{ url('/my-bookings') }}";
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '<ul style="color: red;">';
                        $.each(errors, function (key, value) {
                            errorMsg += '<li>' + value[0] + '</li>';
                        });
                        errorMsg += '</ul>';
                        $('#message').html(errorMsg);
                    }
                });
            });
        });
    </script>
@endsection
