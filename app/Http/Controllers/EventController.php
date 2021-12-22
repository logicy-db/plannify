<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EventController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Mapping of the profile policies
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('events.index', ['events' => Event::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Minimal/maximum value of the event starting time
        $min = date('Y-m-d\TH:i');
        $max = date('Y-m-d\TH:i', strtotime('+100 years'));

        $request->validate([
            'preview' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'description' => 'required',
            // TODO: event can be remote :(
            'location' => 'required',
            // TODO: refactor
            // TODO: timezones???
            'starting_time' => "required|date_format:\"Y-m-d\TH:i\"|after:{$min}|before:{$max}",
            'attendees_limit' => 'required|numeric',
        ]);

        $event = new Event();
        $event->name = $request->name;
        $event->description = $request->description;
        $event->location = $request->location;
        $event->starting_time = date('Y-m-d H:i:s', strtotime($request->starting_time));
        $event->attendees_limit = $request->attendees_limit;
        $event->save();

        $image = $request->file('preview');
        $imageName = sprintf('%s_%s.%s', $event->id, time(), $image->getClientOriginalExtension());
        $image->move(
            public_path(Event::IMAGE_FOLDER),
            $imageName
        );

        $event->preview = $imageName;
        $event->save();

        return redirect()->route('events.show', $event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return view('events.show', ['event' => $event]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return view('events.edit', ['event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        // Minimal/maximum value of the event starting time
        $min = date('Y-m-d\TH:i');
        $max = date('Y-m-d\TH:i', strtotime('+100 years'));

        $request->validate([
            'preview' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'description' => 'required',
            // TODO: event can be remote :(
            'location' => 'required',
            // TODO: refactor
            // TODO: timezones???
            'starting_time' => "required|date_format:\"Y-m-d\TH:i\"|after:{$min}|before:{$max}",
            'attendees_limit' => 'required|numeric',
        ]);

        $event->name = $request->name;
        $event->description = $request->description;
        $event->location = $request->location;
        $event->starting_time = date('Y-m-d H:i:s', strtotime($request->starting_time));
        $event->attendees_limit = $request->attendees_limit;

        if ($image = $request->file('preview')) {
            $imageName = sprintf('%s_%s.%s', $event->id, time(), $image->getClientOriginalExtension());
            $image->move(
                public_path(Event::IMAGE_FOLDER),
                $imageName
            );
            $event->preview = $imageName;
        }

        // TODO: remove old picture
        $event->save();

        return redirect()->route('events.show', $event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event was deleted');
    }

    /**
     * Signing user in for the event.
     */
    public function participate(Event $event) {
        $this->authorize('participate', $event);

        $event->users()->attach(
            Auth::id(), ['participation_type_id' => Event::USER_GOING]
        );

        return back()->with('success', 'You have been singed for the event');
    }

    /**
     * Signing user in for the event.
     */
    public function cancelParticipation(Event $event, User $user = null) {
        if (is_null($user)) {
            $this->authorize('cancelParticipation', $event);

            $user = $event->usersGoing()->firstWhere('id', Auth::id());
            $msg = 'You have canceled your participation in the event';
        } else {
            $this->authorize('cancelUserParticipation', [$event, $user]);

            $user = $event->usersGoing()->firstWhere('id', $user->id);
            $msg = "You have canceled {$user->getFullname()} participation in the event";
        }

        $user->pivot->participation_type_id = Event::USER_CANCELED;
        $user->pivot->save();

        // After user cancels their participation, sign in for the event first user from event queue
        if ($queuedUser = $event->usersQueued()->first()) {
            $queuedUser->pivot->participation_type_id = Event::USER_GOING;
            $queuedUser->pivot->save();
        }

        return back()->with('success', $msg);
    }

    /**
     * Signing user in for the event.
     */
    public function queue(Event $event) {
        $this->authorize('queue', $event);

        $event->users()->attach(
            Auth::id(), ['participation_type_id' => Event::USER_QUEUED]
        );

        return back()->with('success', 'You have been put in the event queue.');
    }

    /**
     * Signing user in for the event.
     */
    public function cancelQueue(Event $event) {
        $this->authorize('cancelQueue', $event);

        $event->users()->detach(Auth::id());

        return back()->with('success', 'You have exited the event queue.');
    }
}
