@extends('components.layouts.app')
@section('title', sprintf('Viewing: %s', $profile->user->getFullname()))
@section('bodyClass', 'profile-view-page')
@section('content')
    @can('update', $profile)
        <button class="alert">
            <a href="{{ route('users.update', $profile) }}">Edit user profile</a>
        </button>
    @endcan
    <div class="profile-card">
        <img class="avatar" src="{{ $profile->getAvatarUrl() }}" alt="Profile picture">
        <div class="full-name">{{ $profile->user->getFullname() }}</div>
        <div class="job-position">{{ $profile->getJobName() }}</div>
        <div class="email">{{ $profile->user->email }}</div>
        <div class="phone">{{ $profile->phone_number }}</div>
    </div>
@endsection
