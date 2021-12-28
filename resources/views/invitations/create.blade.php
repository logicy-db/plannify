@extends('components.layouts.app')
@section('title', 'Invite user')
@section('bodyClass', 'invitation-create-page')
@section('content')
    <form class="form" action="{{ route('system.invitations.store') }}" method="POST">
        @csrf
        <h2 class="title">Invite user</h2>
        <x-form.input name="email" type="email" placeholder="Email address"/>
        @if (sizeof($roleOptions))
            <x-form.select name="role_id" placeholder="Role" :options="$roleOptions"/>
        @endif
        <button type="submit">Invite</button>
    </form>
@endsection
