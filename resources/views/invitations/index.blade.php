@extends('components.layouts.app')
@section('title', 'User invitations panel')
@section('bodyClass', 'invitation-listing-page')
@section('content')
    <div class="action-bar">
        <h2 class="text">User invitations</h2>
        <button class="create-invite">
            <a href="{{ route('system.invitations.create') }}">Invite new user</a>
        </button>
    </div>
    @isset($invites)
        <div class="table-wrapper">
            {{-- TODO: refactor --}}
            <table class="invite-list">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Full name</th>
                    <th>Role</th>
                    <th>Profile actions</th>
                    <th>User Actions</th>
                </tr>
                @foreach($invites as $invite)
                    <tr>
                        <td>{{ $invite->id }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endisset
    {{--    <div class="search-bar">--}}
{{--        @csrf--}}
{{--    </div>--}}
{{--    <div class="event-card-wrapper card-wrapper">--}}
{{--        @include('events.search')--}}
{{--    </div>--}}
@endsection
