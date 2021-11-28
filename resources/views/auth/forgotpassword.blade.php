@extends('components.layouts.app')
@section('title', 'Forgot password?')
@section('bodyClass', 'forgot-password-page')
@section('content')
    <h2 class="text">Forgot password?</h2>
    <form class="form" action="#" method="POST">
        @csrf
        <div>
            <x-auth.field name="email" type="email" placeholder="Email address"/>
        </div>
        <div>
            <button type="submit">Restore</button>
        </div>
    </form>
    <br/>
    <div>
        Want to log in?<br/>
        <b><a href="{{ route('login') }}">Click here!</a></b>
    </div>
@endsection
