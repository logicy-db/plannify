@extends('components.layouts.app')
@section('title', $event->name)
@section('bodyClass', 'event-view-page')
@section('content')
    @can('update', $event)
        <div class="action-bar">
            <button class="alert update-event">
                {{-- TODO: wrong route --}}
                <a href="{{ route('events.create') }}">Update event</a>
            </button>
        </div>
    @endcan
    @php
        $goingUsers = $event->usersGoing();
        $participantCount = sizeof($goingUsers);
        $queuedUsers = $event->usersQueued();
        $canceledUsers = $event->usersCanceled();
    @endphp
    <div class="event-card card">
        <img class="event-preview" src="{{ $event->getPreviewUrl() }}" alt="Event preview">
        <div class="content">
            <div class="name">{{ $event->name }}</div>
            <div class="description">{{ $event->description }}</div>
            <div class="event-info-wrapper">
                <table class="event-info">
                    <tr>
                        <td><b>Date: </b>{{ date('Y-m-d', strtotime($event->starting_time)) }}</td>
                    </tr>
                    <tr>
                        <td><b>Time: </b>{{ date('H:i', strtotime($event->starting_time)) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>Ending time (aprox.)</b><br/>
                            {{ date('Y-m-d H:i', strtotime($event->ending_time)) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>Location</b><br/>
                            {{ $event->location }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>Meeting point</b><br/>
                            {{ $event->meeting_point }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <b>Statistics</b><br/>
                            Allowed participant count: <b>{{ $event->attendees_limit }}</b><br/>
                            Participant count: <b>{{ $participantCount }}</b><br/>
                            @if (!$event->isFull())
                                Free spots: <b>{{ $event->attendees_limit - $participantCount }}</b><br/>
                            @endif
                            @if ($event->isFull())
                                Queued-in people count: <b>{{ sizeof($queuedUsers) }}</b><br/>
                            @endif
                        </td>
                    </tr>
                    <tr class="action-row">
                        <td colspan="2">
                            <b>Actions</b><br/>
                            @if ($goingUsers->contains(Auth::id()))
                                You are participant! See you on the event!<br/><br/>
                                <form id="form" action="{{ route('events.cancelParticipation', $event) }}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    Do you want to cancel your attendance?<br/>
                                    <b>You will need to contact event manager to sign-in again for the event!</b>
                                    <button class="danger btn">Cancel my attendance</button><br/>
                                </form>
                            @elseif ($event->usersCanceled()->contains(Auth::id()))
                                Your previous attendance for the event was canceled.
                                If you want to sign-up for the event once more, please contact event manager.
                            @else
                                @if (!$event->isFull())
{{--                                    @if--}}
                                    <form id="form" action="{{ route('events.participate', $event) }}" method="POST">
                                        @csrf
                                        <button class="btn success">Participate</button>
                                    </form>
                                @else
                                    @if (!$event->usersQueued()->contains(Auth::id()))
                                        <form id="form" action="{{ route('events.queue', $event) }}" method="POST">
                                            @csrf
                                            <button class="btn success">Queue me in</button>
                                        </form>
                                    @else
                                        <form id="form" action="{{ route('events.cancelQueue', $event) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn danger">Exit queue</button>
                                        </form>
                                    @endif
                                @endif
                            @endif
                        </td>
                    </tr>
                </table>
                @if (sizeof($goingUsers))
                    {{-- TODO: add toggler--}}
                    <div><b>Participants</b></div>
                    <table class="event-table">
                        @foreach($goingUsers as $index => $participant)
                            <tr>
                                <td>{{ ++$index }}.</td>
                                <td>
                                    {{-- TODO: add link to profile --}}
                                    @if ($participant->id === Auth::id())
                                        <b>{{ $participant->getFullname() }} (you)</b>
                                    @else
                                        {{ $participant->getFullname() }}
                                    @endif
                                </td>
                                @can('cancelUserParticipation', [$event, $participant])
                                    <td>
                                        <form class="cancel-participation" action="{{ route('events.cancelParticipation', [$event, $participant]) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="danger">Cancel attendance</button>
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </table>
                @else
                    <tr>Be first to sign-up for the event!</tr>
                @endif
                @if (sizeof($queuedUsers))
                    <br/>
                    <div><b>Queued-in people</b></div>
                    <table class="event-table">
                        @foreach($queuedUsers as $index => $queuedUser)
                            <tr>
                                <td>{{ ++$index }}.</td>
                                <td>
                                    {{-- TODO: add link to profile --}}
                                    @if ($queuedUser->id === Auth::id())
                                        <b>{{ $queuedUser->getFullname() }} (you)</b>
                                    @else
                                        {{ $queuedUser->getFullname() }}
                                    @endif
                                </td>
                                {{-- TODO: add possibility to remove user from queue--}}
                                @can('cancelUserQueue', [$event, $queuedUser])
                                    <td>
                                        <form class="cancel-queue" action="{{ route('events.cancelQueue', [$event, $queuedUser]) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="danger">Remove from queue</button>
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </table>
                @endif
                @can('update', $event)
                    <br/>
                    <div><b>Canceled attendance list</b></div>
                    @if (sizeof($canceledUsers))
                        <table class="event-table">
                            @foreach($canceledUsers as $index => $canceledUser)
                                <tr>
                                    <td>{{ ++$index }}.</td>
                                    <td>
                                        {{-- TODO: add link to profile --}}
                                        @if ($canceledUser->id === Auth::id())
                                            <b>{{ $canceledUser->getFullname() }} (you)</b>
                                        @else
                                            {{ $canceledUser->getFullname() }}
                                        @endif
                                    </td>
                                    @can('allowParticipation', [$event, $canceledUser])
                                        <td>
                                            <form class="allow-attendance" action="{{ route('events.allowParticipation', [$event, $canceledUser->id]) }}" method="POST">
                                                @method('PUT')
                                                @csrf
                                                <button type="submit" class="success">Allow attendance</button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </table>
                    @else
                        No one has canceled their attendance yet.
                    @endif
                @endcan
            </div>
        </div>
    </div>
    @can('delete', $event)
        <h3>Dangerous and unrecoverable actions</h3>
        <div class="action-bar">
            <form action="{{ route('events.destroy', $event) }}" method="POST">
                @method('DELETE')
                @csrf
                <button class="delete-button danger" type="submit">Delete event</button>
            </form>
        </div>
    @endcan
@endsection
