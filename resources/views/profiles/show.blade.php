@extends('components.layouts.app')
@section('title', sprintf('Viewing: %s', $profile->user->getFullname()))
@section('bodyClass', 'profile-view-page')
@section('content')
    <div class="profile-card">
        <img class="avatar" src="{{ $profile->getAvatarUrl() }}" alt="Profile picture">
        <div class="full-name">{{ $profile->user->getFullname() }}</div>
        <div class="job-position">{{ $profile->getJobName() }}</div>
        <div class="email">{{ $profile->user->email }}</div>
        <div class="phone">{{ $profile->phone_number }}</div>
    </div>
    <div class="visited-events">
        <p class="title">My events</p>
        {{-- TODO: Implement--}}
    </div>
@endsection
