@extends('components.layouts.app')
@section('title', $event->name)
@section('bodyClass', 'event-view-page')
@section('content')
    <div class="event-card card">
        <div class="name">{{ $event->name }}</div>
        <div class="description">{{ $event->description }}</div>
    </div>
    <div class="event-participants">
        <p class="title">Participants ({{sizeof($participants = $event->usersGoing())}}/{{$event->attendees_limit}}):</p>
        @if (sizeof($participants))
            <ol>
            @foreach($participants as $participant)
                @if ($participant->id === Auth::id())
                    <li><b>{{ $participant->getFullname() }} (you)</b></li>
                @else
                    <li>{{ $participant->getFullname() }}</li>
                @endif
            @endforeach
            </ol>
            @if ($event->isFull())
                <p class="title">Event queue</p>
                <ol>
                    @foreach($event->usersQueued() as $queuedUser)
                        @if ($queuedUser->id === Auth::id())
                            <li><b>{{ $queuedUser->getFullname() }} (you)</b></li>
                        @else
                            <li>{{ $queuedUser->getFullname() }}</li>
                        @endif
                    @endforeach
                </ol>
            @endif
        @else
            Be first to sign-up for the event!
        @endif
{{--        {{ $event->users()->detach(Auth::id()) }}--}}
        @if ($participants->contains(Auth::id()))
            You are participant! See you on the event!
            <form id="form" action="{{ route('events.cancelParticipation', $event) }}" method="POST">
                @csrf
                Do you want to cancel your attendance? <b>You will not be able to sign-in again for the event.</b>
                <button class="btn">Cancel attendance</button>
            </form>
        @elseif ($event->usersCanceled()->contains(Auth::id()))
            You have canceled your previous attendance for the event, thus you cannot sign-up for the event anymore.
        @else
            @if (!$event->isFull())
                <form id="form" action="{{ route('events.participate', $event) }}" method="POST">
                    @csrf
                    <button class="btn">Participate</button>
                </form>
            @else
                @if (!$event->usersQueued()->contains(Auth::id()))
                    <form id="form" action="{{ route('events.queue', $event) }}" method="POST">
                        @csrf
                        <button class="btn">Queue me in</button>
                    </form>
                @else
                    <form id="form" action="{{ route('events.cancelQueue', $event) }}" method="POST">
                        @csrf
                        <button class="btn">Exit queue</button>
                    </form>
                @endif
            @endif
        @endif
    </div>
@endsection
