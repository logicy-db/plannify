<div class="event-card cards">
    @if(sizeof($events))
        @foreach($events as $event)
            <div class="event-card card">
                <div class="content">
                    <div class="name">{{ $event->name }}</div>
                    <div class="description">{{ $event->description }}</div>
                    <div>Participants:</div>
                    <div>{{ sizeof($event->usersGoing()) }} out of {{$event->attendees_limit}}</div>
                </div>
                <button class="see-more"><a href="{{ route('events.show', $event) }}">More</a></button>
            </div>
        @endforeach
    @else
        No matching events were found.
    @endif
</div>
