@extends('components.layouts.app')
@section('title', 'Event listing')
@section('bodyClass', 'event-listing-page')
@section('content')
    @can('create', \App\Models\Event::class)
        <h2>Actions</h2>
        <div class="action-bar">
            <button class="create-event success">
                <a href="{{ route('events.create') }}">Create event</a>
            </button>
        </div>
    @endcan
    <h2>Planned events</h2>
    <div class="search-bar">
        <form class="form event-search">
            @csrf
            <input name="event_name" maxlength="50" type="text" placeholder="Search by event name"/>
        </form>
    </div>
    <div class="event-card-wrapper card-wrapper planned-events">
        @include('events.search', ['events' => $plannedEvents])
    </div>
    <h2>Past events</h2>
    <div class="event-card-wrapper card-wrapper">
        @include('events.search', ['events' => $pastEvents])
    </div>
    <script>
        $(document).ready(function () {
            // Search for planned events
            $("input[name='event_name']").on('input', function () {
                $.ajax({
                    url: '{{ route('events.search') }}',
                    method: 'POST',
                    data: {
                        event_name: $(this).val(),
                        _token:$("input[name='_token']").val(),
                    },
                    success: function (data) {
                        $('.event-card-wrapper.planned-events').fadeOut(500, function () {
                            $(this).html(data).fadeIn();
                        });
                    }
                });
            });
        });
    </script>
@endsection
