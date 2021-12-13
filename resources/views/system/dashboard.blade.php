@extends('components.layouts.app')
@section('title', 'System dashboard')
@section('bodyClass', 'system-dashboard')
@section('content')
    <h2 class="text">System dashboard</h2>
    <ul>
        <li><a href="{{ route('system.users.index') }}">List of users</a></li>
    </ul>
@endsection
