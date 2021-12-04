@extends('components.layouts.app')
@section('title', 'Admin dashboard')
@section('bodyClass', 'admin-dashboard')
@section('content')
    <h2 class="text">Admin dashboard</h2>
    <ul>
        <li><a href="{{ route('admin.user.index') }}">List of users</a></li>
    </ul>
@endsection
