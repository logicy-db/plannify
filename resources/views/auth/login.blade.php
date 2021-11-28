@extends('components.layouts.app')
@section('title', 'Login Page')
@section('bodyClass', 'login-page')
@section('content')
    <h2 class="text">Log in to your account</h2>
    <form class="form" action="#" method="POST">
        @csrf
        <div>
            <x-auth.field name="email" type="email" placeholder="Email address"/>
            <x-auth.field name="password" type="password" placeholder="Password"/>
        </div>
        <div>
            <button type="submit">Log in</button>
        </div>
    </form>
    <br/>
@endsection
