@extends('components.layouts.app')
@section('title', 'Create new event')
@section('bodyClass', 'event-create-page')
@section('content')
    @php
        // Minimal/maximum value of the event starting time
        $min = date('Y-m-d\TH:i');
        $max = date('Y-m-d\TH:i', strtotime('+100 years'));
    @endphp
    <h2 class="text">New event</h2>
    <img class="event-preview" src="" alt="Event preview">
    <form class="form" action="{{ route('events.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="form-content">
            <x-form.input name="preview" type="file" placeholder="Event preview"/>
            <x-form.input name="name" type="text" placeholder="Event name"/>
            <x-form.textarea name="description" placeholder="Describe the event..." label="Description"/>
            <x-form.input name="location" type="text" placeholder="Where event is taking place?" label="Location"/>
            <x-form.input name="meeting_point" type="text" placeholder="Where people should gather?" label="Meeting point"/>
            <x-form.input name="starting_time" type="datetime-local" placeholder="Starting time"
                          :additional="sprintf('min=%s max=%s', $min, $max)"/>
            <x-form.input name="ending_time" type="datetime-local" placeholder="Ending time (aprox.)"
                          :additional="sprintf('min=%s max=%s', $min, $max)"/>
            <x-form.input name="attendees_limit" type="number" placeholder="Attendees limit"/>
            <button class="success" type="submit">Create event</button>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            $('#preview').change(function(){
                let eventPreview = $('.event-preview');
                previewImageOnUpload(this, eventPreview);
                eventPreview.show();
            });

            let $startingTime = $('#starting_time'),
                $endingTime = $('#ending_time');

            $startingTime.on('change', function () {
                if (new Date($endingTime.val()) <= new Date($startingTime.val())) {
                    alert('Event starting time must be before ending time!');
                    $(this).val('');
                }
            });

            $endingTime.on('change', function () {
                if (new Date($endingTime.val()) <= new Date($startingTime.val())) {
                    alert('Event ending time must be after the starting time!');
                    $(this).val('');
                }
            });
        });
    </script>
@endsection
