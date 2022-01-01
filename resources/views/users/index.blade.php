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
                    <th>Status</th>
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
                            @if($user->active)
                                <button class="success">Active</button>
                            @else
                                <button class="disabled" disabled>Disabled</button>
                            @endif
                        </td>
                        <td>
                            <div class="user-actions">
                                <button class="alert">
                                    <a class="view-btn" href="{{ route('users.show', $user->id) }}">Edit user</a>
                                </button>
                                @can('changeUserStatus', $user)
                                    <form action="{{ route('system.users.changeStatus', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="danger">
                                            @if($user->active)
                                                Disable user
                                            @else
                                                Enable user
                                            @endif
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endisset
@endsection
