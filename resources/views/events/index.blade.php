@extends('components.layouts.app')
@section('title', 'Event listing')
@section('bodyClass', 'event-listing-page')
@section('content')
    <h2 class="text">Events</h2>
    <button>
        <a href="{{ route('events.create') }}">Create event</a>
    </button>
    <div class="search-bar">
        @csrf
    </div>
    <div class="event-card-wrapper card-wrapper">
        @include('events.search')
    </div>
@endsection
