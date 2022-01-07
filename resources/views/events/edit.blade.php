@extends('components.layouts.app')
@section('title', 'Edit event page')
@section('bodyClass', 'event-editing-page')
@section('content')
    @php
        // Minimal/maximum value of the event starting time
        $min = date('Y-m-d\TH:i');
        $max = date('Y-m-d\TH:i', strtotime('+100 years'));
    @endphp
    <form class="form" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" method="POST">
        @method('PUT')
        @csrf
        <h2 class="text">Editing event</h2>
        <img class="event-preview" src="{{ url($event->getPreviewUrl()) }}" alt="Event preview">
        <x-form.input name="preview" type="file" placeholder="Event preview (optional)"/>
        <x-form.input name="name" type="text" placeholder="Event name" :inputValue="$event->name"/>
        <x-form.textarea name="description" placeholder="Description" label="Description" :content="$event->description"/>
        <x-form.input name="location" type="text" placeholder="Location" :inputValue="$event->location"/>
        <x-form.input name="meeting_point" type="text" placeholder="Meeting point" :inputValue="$event->meeting_point"/>
        <x-form.input name="starting_time" type="datetime-local" placeholder="Starting time"
                      :additional="sprintf('min=%s max=%s', $min, $max)" :inputValue="date('Y-m-d\TH:i', strtotime($event->starting_time))"/>
        <x-form.input name="ending_time" type="datetime-local" placeholder="Ending time (aprox.)"
                      :additional="sprintf('min=%s max=%s', $min, $max)" :inputValue="date('Y-m-d\TH:i', strtotime($event->ending_time))"/>
        <button class="success" type="submit">Update event</button>
    </form>
    <script>
        $(document).ready(function () {
            $('#preview').change(function(){
                previewImageOnUpload(this, $('.event-preview'));
            });

            // Set the description height based on the content
            $('#description').height($('#description')[0].scrollHeight + 20);
        });
    </script>
@endsection
