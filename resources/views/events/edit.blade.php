@extends('components.layouts.app')
@section('title', 'Edit event page')
@section('bodyClass', 'edit-event-page')
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
        <div class="preview-wrapper">
            <img class="preview" src="{{ url($event->getPreviewUrl()) }}" alt="Profile picture">
        </div>
        <x-form.input name="preview" type="file" placeholder="Event preview"/>
        <x-form.input name="name" type="text" placeholder="Event name" :inputValue="$event->name"/>
        <x-form.input name="description" type="text" placeholder="Description" :inputValue="$event->description"/>
        <x-form.input name="location" type="text" placeholder="Location" :inputValue="$event->location"/>
        <x-form.input name="starting_time" type="datetime-local" placeholder="Starting time"
                      :additional="sprintf('min=%s max=%s', $min, $max)" :inputValue="date('Y-m-d\TH:i', strtotime($event->starting_time))"/>
        <x-form.input name="attendees_limit" type="number" placeholder="Attendees limit" :inputValue="$event->attendees_limit"/>
        <button type="submit">Update event</button>
    </form>
    <script>
        $(document).ready(function () {
            function previewImage(input) {
                // TODO: refactor that later
                if (input.files && input.files[0]) {
                    let image = input.files[0];
                    console.log(image);
                    if (image.type.startsWith('image/')) {
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            $('img.preview').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(image);
                    } else {
                        $('img.preview').attr('src', '');
                    }
                }
            }

            $('#preview').change(function(){
                previewImage(this);
            });
        });
    </script>
@endsection
