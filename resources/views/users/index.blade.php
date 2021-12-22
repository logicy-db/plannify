@extends('components.layouts.app')
@section('title', 'User listing')
@section('bodyClass', 'user-listing-page')
@section('content')
    <h2 class="text">List of users</h2>
    @isset($users)
        <table class="user-table">
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Full name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getFullname() }}</td>
                    <td>{{ $user->role->name }}</td>
                    <td>
                        @if ($user->profile)
                            <a class="view-btn" href="{{ route('profiles.show', $user->profile) }}">View profile</a>
                        @else
                            Profile is not configured
                        @endif
                        <a class="view-btn" href="{{ route('users.show', $user->id) }}">Edit user</a>
                    </td>
                </tr>
            @endforeach
        </table>
    @endisset
@endsection
