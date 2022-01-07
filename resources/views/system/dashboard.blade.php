@extends('components.layouts.app')
@section('title', 'System dashboard')
@section('bodyClass', 'system-dashboard')
@section('content')
    <h2 class="text">System dashboard</h2>
    <ul>
        @can('viewAny', \App\Models\User::class)
            <li><a href="{{ route('system.users.index') }}">List of users</a></li>
        @endcan
        @can('viewAny', \App\Models\UserInvitation::class)
            <li><a href="{{ route('system.invitations.index') }}">User invitations</a></li>
        @endcan
    </ul>
@endsection
