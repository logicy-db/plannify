@extends('components.layouts.app')
@section('title', 'Registration Page')
@section('bodyClass', 'registration-page')
@section('content')
    <form class="form" action="#" method="POST">
        @csrf
        <h2 class="text">Register a new account!</h2>
        <x-form.input name="invitation_token" type="text" placeholder="Invitation token" :inputValue="$token"
                      :additional="'hidden'"/>
        <x-form.input name="email" type="email" placeholder="Email address" :inputValue="$email" :readonly="true"/>
        <x-form.input name="password" type="password" placeholder="Password"/>
        <x-form.input name="password_confirmation" type="password" placeholder="Confirm password"/>
        <button type="submit">Register</button>
    </form>
@endsection
