@extends('components.layouts.app')
@section('title', 'Forgot password?')
@section('bodyClass', 'forgot-password-page')
@section('content')
    <form class="form" action="#" method="POST">
        @csrf
        <h2 class="text">Forgot password?</h2>
        <x-form.input name="email" type="email" placeholder="Email address"/>
        <button class="success" type="submit">Restore</button>
        <button type="button"><a href="{{ route('login') }}">Want to log in?</a></button>
    </form>
@endsection
