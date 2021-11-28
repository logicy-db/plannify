@extends('components.layouts.app')
@section('title', 'Reset Password Page')
@section('bodyClass', 'reset-password-page')
@section('content')
    <h2 class="text">Reset your password</h2>
    <form class="form" action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <x-auth.field name="email" type="email" placeholder="Email address"/>
            <x-auth.field name="password" type="password" placeholder="New Password"/>
            <x-auth.field name="password_confirmation" type="password" placeholder="Confirm new password"/>
        </div>
        <div>
            <button type="submit">Reset</button>
        </div>
    </form>
@endsection
