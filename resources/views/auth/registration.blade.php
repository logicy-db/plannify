@extends('components.layouts.app')
@section('title', 'Registration Page')
@section('bodyClass', 'registration-page')
@section('content')
    <h2 class="text">Register a new account!</h2>
    <form class="form" action="#" method="POST">
        @csrf
        <div>
            <x-auth.field name="email" type="email" placeholder="Email address"/>
            <x-auth.field name="firstname" type="text" placeholder="First name"/>
            <x-auth.field name="middlename" type="text" :isRequired="false" placeholder="Middle name (optional)"/>
            <x-auth.field name="lastname" type="text" placeholder="Last name"/>
            <x-auth.field name="password" type="password" placeholder="Password"/>
            <x-auth.field name="password_confirmation" type="password" placeholder="Confirm password"/>
        </div>
        <div>
            <button type="submit">Register</button>
        </div>
    </form>
    <br/>
    <div>
        Want to log in?<br/>
        <b><a href="{{ url('login') }}">Click here!</a></b>
    </div>
@endsection
