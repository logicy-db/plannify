@extends('components.layouts.app')
@section('title', 'Create your profile')
@section('bodyClass', 'create-profile-page')
@section('content')
    <form class="form" action="{{ route('profiles.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <h2 class="text">Fill in your profile</h2>
        {{-- TODO: style file selection field --}}
        <x-form.input name="avatar" type="file" placeholder="Profile picture"/>
        <x-form.input name="first_name" type="text" placeholder="First name"/>
        <x-form.input name="last_name" type="text" placeholder="Last name"/>
        <x-form.input name="phone_number" type="tel" placeholder="Phone number"/>
        <x-form.input name="address" type="text" placeholder="Address"/>
        <x-form.input name="job_position" type="text" placeholder="Job Position"/>
        <button type="submit">Create profile</button>
    </form>
@endsection
