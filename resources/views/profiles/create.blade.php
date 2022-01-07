@extends('components.layouts.app')
@section('title', 'Create your profile')
@section('bodyClass', 'profile-create-page')
@section('content')
    <form class="form" action="{{ route('profiles.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <h2 class="text">Fill in your profile</h2>
        {{-- TODO: style file selection field --}}
        <x-form.input name="avatar" type="file" placeholder="Profile picture"/>
        <x-form.input name="first_name" type="text" label="First name" placeholder="John"/>
        <x-form.input name="last_name" type="text" label="Last name" placeholder="Doe"/>
        <x-form.input name="phone_number" type="tel" label="Phone number" placeholder="(+371) 12345678"/>
        <h4>Fields below are only visible to company management</h4>
        <x-form.input name="address" type="text" label="Address" placeholder="Main Street 16, Main City"/>
        <button class="success" type="submit">Create profile</button>
    </form>
@endsection
