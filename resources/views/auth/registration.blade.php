@extends('components.layouts.app')
@section('title', 'Registration Page')
@section('bodyClass', 'registration-page')
@section('content')
    <form class="form" action="#" method="POST">
        @csrf
        <h2 class="text">Register a new account!</h2>
        <x-form.input name="email" type="email" placeholder="Email address"/>
        <x-form.input name="firstname" type="text" placeholder="First name"/>
        <x-form.input name="lastname" type="text" placeholder="Last name"/>
        <x-form.input name="password" type="password" placeholder="Password"/>
        <x-form.input name="password_confirmation" type="password" placeholder="Confirm password"/>
        <button type="submit">Register</button>
    </form>
@endsection
