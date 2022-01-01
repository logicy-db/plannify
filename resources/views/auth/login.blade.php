@extends('components.layouts.app')
@section('title', 'Login Page')
@section('bodyClass', 'login-page')
@section('content')
    <form class="form" action="#" method="POST">
        @csrf
        <h2 class="text">Log in to your account</h2>
        <x-form.input name="email" type="email" placeholder="Email address"/>
        <x-form.input name="password" type="password" placeholder="Password"/>
        <button class="success" type="submit">Log in</button>
    </form>
    <br/>
@endsection
