<div class="cards">
    @if(sizeof($events))
        @foreach($events as $event)
            <div class="event-card card">
                <img class="event-preview" src="{{ $event->getPreviewUrl() }}" alt="Event preview">
                <div class="content">
                    <div class="name">{{ $event->name }}</div>
                    <div class="event-info-wrapper">
                        <table class="event-info">
                            <tr>
                                <td><b>Date: </b>{{ date('Y-m-d', strtotime($event->starting_time)) }}</td>
                            </tr>
                            <tr>
                                <td><b>Time: </b>{{ date('H:i', strtotime($event->starting_time)) }}</td>
                            </tr>
                            <tr>
                                <td><b>Participant count: </b>{{ sizeof($event->usersGoing()) }} out of {{$event->attendees_limit}}</td>
                            </tr>
                            <tr class="event-location">
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
                            {{-- TODO: Add attendees and people in queue info--}}
                        </table>
                    </div>
                </div>
                <button class="see-more"><a href="{{ route('events.show', $event) }}"><b>See more</b></a></button>
            </div>
        @endforeach
    @else
        No matching events were found.
    @endif
</div>
