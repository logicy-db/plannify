@extends('components.layouts.app')
@section('title', 'Create your profile')
@section('bodyClass', 'create-profile-page')
@section('content')
    <form class="form" action="{{ route('events.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <h2 class="text">New event</h2>
        <x-form.input name="preview" type="file" placeholder="Event preview"/>
        <x-form.input name="name" type="text" placeholder="Event name"/>
        <x-form.input name="description" type="text" placeholder="Description"/>
        <x-form.input name="location" type="text" placeholder="Location"/>
        <x-form.input name="starting_time" type="time" placeholder="Starting time"/>
        <x-form.input name="starting_date" type="date" placeholder="Starting date"/>
        <x-form.input name="attendees_limit" type="number" placeholder="Attendees limit"/>
        <button type="submit">Create event</button>
    </form>
@endsection
