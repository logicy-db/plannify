@extends('components.layouts.app')
@section('title', 'User invitations panel')
@section('bodyClass', 'invitation-listing-page')
@section('content')
    <div class="action-bar">
        <h2 class="text">User invitations</h2>
        <button class="create-invite success">
            <a href="{{ route('system.invitations.create') }}">Invite new user</a>
        </button>
    </div>
    @isset($invites)
        <div class="table-wrapper">
            <table class="invite-list">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Invited on role</th>
                    <th>Inviter</th>
                    <th>Status</th>
                    <th>Expiration time</th>
                    <th></th>
                </tr>
                @foreach($invites as $invite)
                    <tr>
                        <td>{{ $invite->id }}</td>
                        <td>{{ $invite->email }}</td>
                        <td>{{ $invite->role->name }}</td>
                        <td>
                            <button class="profile-link">
                                <a href="{{ $invite->inviter->getProfileUrl() }}">{{ $invite->inviter->getFullName() }}</a>
                            </button>
                        </td>
                        <td>
                            <button class="status {{ $invite->getStatusCssClass() }}">{{ $invite->getStatus() }}</button>
                        </td>
                        <td class="date">{{ $invite->expires_at }}</td>
                        @can('update', $invite)
                        <td>
                            <form class="resend-invite" action="{{ route('system.invitations.resendInvite', $invite) }}" method="POST">
                                @csrf
                                <button class="danger">Resend invite</button><br/>
                            </form>
                        </td>
                        @endcan
                    </tr>
                @endforeach
            </table>
        </div>
    @endisset
@endsection
