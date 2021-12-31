@extends('components.layouts.app')
@section('title', 'User listing')
@section('bodyClass', 'user-listing-page')
@section('content')
    <h2 class="text">List of users</h2>
    @isset($users)
        <div class="table-wrapper">
            <table class="user-table">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Full name</th>
                    <th>Role</th>
                    <th></th>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->profile)
                                <button class="profile-link">
                                    <a href="{{ $user->getProfileUrl() }}">{{ $user->getFullName() }}</a>
                                </button>
                            @else
                                Profile not configured
                            @endif
                        </td>
                        <td>{{ $user->role->name }}</td>
                        <td>
                            <button class="alert">
                                <a class="view-btn" href="{{ route('users.show', $user->id) }}">Edit user</a>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endisset
@endsection
