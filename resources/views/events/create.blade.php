@extends('components.layouts.app')
@section('title', 'Create new event')
@section('bodyClass', 'create-event-page')
@section('content')
    @php
        // Minimal/maximum value of the event starting time
        $min = date('Y-m-d\TH:i');
        $max = date('Y-m-d\TH:i', strtotime('+100 years'));
    @endphp
    <form class="form" action="{{ route('events.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <h2 class="text">New event</h2>
        <x-form.input name="preview" type="file" placeholder="Event preview"/>
        <x-form.input name="name" type="text" placeholder="Event name"/>
        <x-form.input name="description" type="text" placeholder="Description"/>
        <x-form.input name="location" type="text" placeholder="Location"/>
        <x-form.input name="starting_time" type="datetime-local" placeholder="Starting time"
                      :additional="sprintf('min=%s max=%s', $min, $max)"/>
        <x-form.input name="attendees_limit" type="number" placeholder="Attendees limit"/>
        <button type="submit">Create event</button>
    </form>
@endsection
